<?php
declare(strict_types=1);


namespace Woisks\Passport\Models\Services;


use Carbon\Carbon;
use Hash;
use Illuminate\Http\JsonResponse;
use Woisks\Jwt\JWT;
use Woisks\Jwt\Services\JwtService;
use Woisks\Passport\Events\RegisterEvent;
use Woisks\Passport\Exceptions\DisableAccountException;
use Woisks\Passport\Exceptions\FreezeAccountException;
use Woisks\Passport\Exceptions\LockException;
use Woisks\Passport\Exceptions\PasswordErrorException;
use Woisks\Passport\Models\Entity\Account;
use Woisks\Passport\Models\Entity\Passport;
use Woisks\Passport\Models\Repository\AccountRepository;
use Woisks\Passport\Models\Repository\PassportRepository;


/**
 * Class LoginService
 *
 * @package Woisks\Passport\Models\Services
 *
 * @Author  Maple Grove  <bolelin@126.com> 2019/5/10 11:26
 */
class LoginService
{

    /**
     * passportRepo  2019/5/10 11:26
     *
     * @var \Woisks\Passport\Models\Repository\PassportRepository
     */
    private $passportRepo;

    /**
     * accountRepo  2019/5/10 11:26
     *
     * @var \Woisks\Passport\Models\Repository\AccountRepository
     */
    private $accountRepo;


    /**
     * LoginService constructor. 2019/5/10 11:26
     *
     * @param \Woisks\Passport\Models\Repository\PassportRepository $passportRepo
     * @param \Woisks\Passport\Models\Repository\AccountRepository  $accountRepo
     *
     * @return void
     */
    public function __construct(PassportRepository $passportRepo, AccountRepository $accountRepo)
    {
        $this->passportRepo = $passportRepo;
        $this->accountRepo = $accountRepo;
    }


    /**
     * usernameExists 2019/5/21 20:31
     *
     * @param string $username
     *
     * @return bool
     */
    public function usernameExists(string $username): bool
    {
        return $this->passportRepo->usernameExists($username);
    }


    /**
     * passport 2019/6/6 16:48
     *
     * @param string $username
     *
     * @return null|\Woisks\Passport\Models\Entity\Passport
     */
    public function passport(string $username): ?Passport
    {
        return $this->passportRepo->usernameFirst($username);
    }


    /**
     * account 2019/6/6 16:49
     *
     * @param int $account_uid
     *
     * @return null|\Woisks\Passport\Models\Entity\Account
     */
    public function account(int $account_uid): ?Account
    {
        return $this->accountRepo->uidFind($account_uid);
    }


    /**
     * checkLimitLoginTime 2019/6/6 16:50
     *
     * @param \Woisks\Passport\Models\Entity\Account $account
     *
     * @return null|\Illuminate\Http\JsonResponse
     */
    public function checkLimitLoginTime(Account $account): ?JsonResponse
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
     * checkPassword 2019/6/6 16:55
     *
     * @param string                                  $password
     * @param \Woisks\Passport\Models\Entity\Account  $account
     * @param \Woisks\Passport\Models\Entity\Passport $passport
     *
     * @return void
     * @throws \Woisks\Passport\Exceptions\LockException
     * @throws \Woisks\Passport\Exceptions\PasswordErrorException
     */
    public function checkPassword(string $password, Account $account, Passport $passport)
    {
        $password = Hash::check($password, $account->password);
        if (!$password) {

            if ($account->login_error_count >= 5) {

                event(new RegisterEvent('fail', create_numeric_id(), $passport->account_uid, $passport->account_type));
                $account->login_error_count++;
                $account->limit_login_time = Carbon::now()->addMinute(30)->timestamp;
                $account->save();

                throw new LockException('Too many errors, lock 30 minutes');
            }

            event(new RegisterEvent('fail', create_numeric_id(), $passport->account_uid, $passport->account_type));
            $account->login_error_count++;
            $account->save();

            throw new PasswordErrorException('Password Error');
        }
    }


    /**
     * checkStatus 2019/6/6 16:56
     *
     * @param \Woisks\Passport\Models\Entity\Account  $account
     * @param \Woisks\Passport\Models\Entity\Passport $passport
     *
     * @return void
     * @throws \Woisks\Passport\Exceptions\DisableAccountException
     * @throws \Woisks\Passport\Exceptions\FreezeAccountException
     */
    public function checkStatus(Account $account, Passport $passport): void
    {

        if ($account->status == config('woisk.passport.status.Disable') ){
            throw new DisableAccountException('Account Disable');
        }

        if ($account->status == config('woisk.passport.status.Freeze') ){
            throw new FreezeAccountException('Account Disable');
        }

        $account->login_error_count = 0;  //清零密码错误次数
        $account->last_login_account_type = $passport->account_type;
        $account->sum_login_count++;
        $account->save();

        $passport->login_count++;//账号登录次数累加
        $passport->save();
    }


    /**
     * token 2019/5/10 11:26
     *
     * @param int $uid
     * @param int $loginID
     * @param int $mac
     *
     * @return string
     */
    public function token(int $uid, int $loginID, int $mac): string
    {
        return JWT::encode_jwt([
            'ide' => $uid,//用户UID
            'iva' => $loginID,//登录日志记录ID
            'mac' => $mac,//mac用户唯一识别ID
        ], JwtService::jwt_secret_key());

    }
}
