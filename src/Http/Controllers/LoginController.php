<?php
declare(strict_types=1);


namespace Woisks\Passport\Http\Controllers;


use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Woisks\Passport\Events\RegisterEvent;
use Woisks\Passport\Exceptions\DisableAccountException;
use Woisks\Passport\Exceptions\FreezeAccountException;
use Woisks\Passport\Exceptions\LockException;
use Woisks\Passport\Exceptions\PasswordErrorException;
use Woisks\Passport\Http\Requests\LoginRequest;
use Woisks\Passport\Models\Services\LoginService;


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
     * loginService  2019/5/10 11:44
     *
     * @var \Woisks\Passport\Models\Services\LoginService
     */
    private $loginService;


    /**
     * LoginController constructor. 2019/5/10 11:44
     *
     * @param \Woisks\Passport\Models\Services\LoginService $loginService
     *
     * @return void
     */
    public function __construct(LoginService $loginService)
    {
        $this->loginService = $loginService;
    }


    /**
     * login 2019/5/24 20:33
     *
     * @param \Woisks\Passport\Http\Requests\LoginRequest $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(LoginRequest $request): JsonResponse
    {
        if (!$this->loginService->usernameExists($request->input('username'))) {
            return res(404, 'username not exists');
        }

        try {
            $res = $this->loginServices($request);
        } catch (LockException $e) {
            $res = res(2001, 'Too many errors, lock 30 minutes');
        } catch (PasswordErrorException $e) {
            $res = res(401, 'Password Error');
        } catch (DisableAccountException $e) {
            $res = res(2002, 'Account Disable');
        } catch (FreezeAccountException $e) {
            $res = res(2003, 'Account Freeze');
        }

        return $res;
    }


    /**
     * loginServices 2019/5/24 20:33
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     * @throws \Woisks\Passport\Exceptions\DisableAccountException
     * @throws \Woisks\Passport\Exceptions\FreezeAccountException
     * @throws \Woisks\Passport\Exceptions\LockException
     * @throws \Woisks\Passport\Exceptions\PasswordErrorException
     */
    private function loginServices(Request $request): JsonResponse
    {
        //object
        $passport = $this->loginService->passport($request->input('username'));
        $account = $this->loginService->account($passport->account_uid);

        //services
        $this->loginService->checkPassword($request->input('password'), $account, $passport);

        if (!is_null($this->loginService->checkLimitLoginTime($account))) {
            return $this->loginService->checkLimitLoginTime($account);
        }

        $this->loginService->checkStatus($account, $passport);

        return $this->loginStatus($passport, $account);
    }

    /**
     * loginStatus 2019/5/24 20:33
     *
     * @param $passport
     * @param $account
     *
     * @return \Illuminate\Http\JsonResponse
     */
    private function loginStatus($passport, $account)
    {
        $loginID = create_numeric_id();
        event(new RegisterEvent('login', $loginID, $passport->account_uid, $passport->account_type));

        $mac = Carbon::now()->timestamp;

        $redis = \Redis::setex('token:' . $account->id . ':' . $mac, config('woisk.jwt.expire_time') * 60, $loginID);

        return $redis
            ? res(200, 'success', [
                'token' => $this->loginService->token($passport->account_uid, $loginID, $mac)
            ])
            : res(500, 'Come back later');

    }
}
