<?php
declare(strict_types=1);


namespace Woisks\Passport\Http\Controllers;


use Carbon\Carbon;
use DB;
use Hash;
use Illuminate\Http\JsonResponse;
use Throwable;
use Woisks\Agent\AgentService;
use Woisks\Jwt\JWT;
use Woisks\Jwt\Services\JwtService;
use Woisks\Passport\Events\RegisterEvent;
use Woisks\Passport\Exceptions\DisableAccountException;
use Woisks\Passport\Exceptions\FreezeAccountException;
use Woisks\Passport\Exceptions\LockException;
use Woisks\Passport\Exceptions\PasswordErrorException;
use Woisks\Passport\Http\Requests\LoginRequest;
use Woisks\Passport\Models\Repository\AccountRepository;
use Woisks\Passport\Models\Repository\LogRepository;
use Woisks\Passport\Models\Repository\PassportRepository;


/**
 * Class LoginController
 *
 * @package Woisks\PassportRepository\Http\Controllers
 *
 * @Author  Maple Grove  <bolelin@126.com> 2019/5/10 11:44
 */
class LoginController extends BaseController
{


    /**
     * passportRepo.  2019/7/28 11:47.
     *
     * @var  PassportRepository
     */
    private $passportRepo;

    /**
     * accountRepo.  2019/7/28 11:47.
     *
     * @var  AccountRepository
     */
    private $accountRepo;

    /**
     * logRepo.  2019/7/28 11:47.
     *
     * @var  LogRepository
     */
    private $logRepo;


    /**
     * LoginController constructor. 2019/7/28 11:47.
     *
     * @param PassportRepository $passportRepo
     * @param AccountRepository $accountRepo
     * @param LogRepository $logRepo
     *
     * @return void
     */
    public function __construct(PassportRepository $passportRepo, AccountRepository $accountRepo, LogRepository $logRepo)
    {
        $this->logRepo      = $logRepo;
        $this->accountRepo  = $accountRepo;
        $this->passportRepo = $passportRepo;
    }


    /**
     * login. 2019/7/28 11:47.
     *
     * @param LoginRequest $request
     *
     * @return JsonResponse|null
     */
    public function login(LoginRequest $request)
    {

        if (!$this->passportRepo->usernameExists($request->input('username'))) {
            return res(404, 'username not exists');
        }

        try {
            DB::beginTransaction();
            //object
            $passport = $this->passportRepo->usernameFirst($request->input('username'));

            $account = $this->accountRepo->uidFind($passport->account_uid);

            $this->checkPassword($request->input('password'), $account, $passport);

            if (!is_null($this->checkLimitLoginTime($account))) {
                return $this->checkLimitLoginTime($account);
            }

            $this->checkStatus($account, $passport);

            $res = $this->loginStatus($passport, $account);

        } catch (LockException $e) {
            $res = res(2001, 'Too many errors, lock 30 minutes');
        } catch (PasswordErrorException $e) {
            $res = res(401, 'Password Error');
        } catch (DisableAccountException $e) {
            $res = res(2002, 'AccountEntity Disable');
        } catch (FreezeAccountException $e) {
            $res = res(2003, 'AccountEntity Freeze');
        } catch (Throwable $e) {
            DB::rollBack();
            $res = res(422, 'Come back later');
        }
        DB::commit();

        return $res;
    }


    /**
     * checkPassword. 2019/7/28 11:47.
     *
     * @param string $password
     * @param $account
     * @param $passport
     *
     * @return void
     * @throws LockException
     * @throws PasswordErrorException
     */
    public function checkPassword(string $password, $account, $passport)
    {
        $password = Hash::check($password, $account->password);
        if (!$password) {

            if ($account->login_error_count >= 5) {

                $this->event('fail', create_numeric_id(), $passport->account_uid, $passport->account_type);
                $account->login_error_count++;
                $account->limit_login_time = Carbon::now()->addMinute(30)->timestamp;
                $account->save();

                throw new LockException('Too many errors, lock 30 minutes');
            }

            $this->event('fail', create_numeric_id(), $passport->account_uid, $passport->account_type);
            $account->login_error_count++;
            $account->save();

            throw new PasswordErrorException('Password Error');
        }
    }


    /**
     * checkLimitLoginTime. 2019/7/28 11:47.
     *
     * @param $account
     *
     * @return JsonResponse|null
     */
    public function checkLimitLoginTime($account)
    {
        if ($account->limit_login_time >= Carbon::now()->timestamp) {
            //验证限制登录时间
            $time = Carbon::parse($account->limit_login_time)->diffInMinutes();

            $account->login_error_count++;//登录错误次数加加
            $account->save();

            return res(201, 'account lock', ['time' => $time]);
        }

        return null;
    }


    /**
     * checkStatus. 2019/7/28 11:47.
     *
     * @param $account
     * @param $passport
     *
     * @return void
     * @throws DisableAccountException
     * @throws FreezeAccountException
     */
    public function checkStatus($account, $passport)
    {

        if ($account->status == 0) {
            //注销账号
            throw new DisableAccountException('Account Disable');
        }

        if ($account->status == 2) {
            //冻结账号
            throw new FreezeAccountException('Account Freeze');
        }

        $account->login_error_count       = 0;  //清零密码错误次数
        $account->last_login_account_type = $passport->account_type;
        $account->sum_login_count++;
        $account->save();

        $passport->login_count++;//账号登录次数累加
        $passport->save();

        $this->logRepo->loginFail_delete($account->id);

    }


    /**
     * loginStatus. 2019/7/28 11:47.
     *
     * @param $passport
     * @param $account
     *
     * @return JsonResponse
     */
    private function loginStatus($passport, $account)
    {
        $loginID = create_numeric_id();

        $this->event('login', $loginID, $passport->account_uid, $passport->account_type);

        $mac = Carbon::now()->timestamp;

        $redis = \Redis::setex('token:' . $account->id . ':' . $mac, config('woisk.jwt.expire_time') * 60, $loginID);

        return $redis
            ? res(200, 'success', [
                'token' => $this->token($passport->account_uid, $loginID, $mac)
            ])
            : res(422, 'Come back later');

    }


    /**
     * token. 2019/7/27 1:21.
     *
     * @param int $uid
     * @param int $loginID
     * @param int $mac
     *
     * @return mixed
     */
    public function token(int $uid, int $loginID, int $mac)
    {
        return JWT::encode_jwt([
            'ide' => $uid,//用户UID
            'iva' => $loginID,//登录日志记录ID
            'mac' => $mac,//mac用户唯一识别ID
        ], JwtService::jwt_secret_key());

    }

    /**
     * event. 2019/7/27 1:21.
     *
     * @param $type
     * @param $logID
     * @param $account_uid
     * @param $account_type
     *
     * @return void
     */
    public function event($type, $logID, $account_uid, $account_type)
    {
        $agent = AgentService::info();
        event(new RegisterEvent($type, $logID, $account_uid, $account_type, request()->getClientIp(),
            $agent['os'], $agent['client'], $agent['brand_model'], $agent['device']));
    }
}
