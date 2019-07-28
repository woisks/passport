<?php
declare(strict_types=1);


namespace Woisks\Passport\Http\Controllers;


use Hash;
use Illuminate\Http\JsonResponse;
use Woisks\Jwt\Services\JwtService;
use Woisks\Passport\Http\Requests\ResetPasswordRequest;
use Woisks\Passport\Http\Requests\UpdatePasswordRequest;
use Woisks\Passport\Models\Repository\AccountRepository;
use Woisks\Passport\Models\Repository\PassportRepository;

/**
 * Class PasswordController
 *
 * @package Woisks\PassportEntity\Http\Controllers
 *
 * @Author  Maple Grove  <bolelin@126.com> 2019/5/17 19:55
 */
class PasswordController extends BaseController
{

    /**
     * accountRepo.  2019/7/28 11:49.
     *
     * @var  AccountRepository
     */
    public $accountRepo;

    /**
     * passportRepo.  2019/7/28 11:49.
     *
     * @var  PassportRepository
     */
    public $passportRepo;

    /**
     * PasswordController constructor. 2019/7/28 11:49.
     *
     * @param AccountRepository $accountRepository
     * @param PassportRepository $passportRepository
     *
     * @return void
     */
    public function __construct(AccountRepository $accountRepository, PassportRepository $passportRepository)
    {
        $this->accountRepo  = $accountRepository;
        $this->passportRepo = $passportRepository;
    }


    /**
     * update. 2019/7/28 11:49.
     *
     * @param UpdatePasswordRequest $request
     *
     * @return JsonResponse
     */
    public function update(UpdatePasswordRequest $request)
    {
        $old_password = $request->input('old_password');
        $new_password = $request->input('password');

        $info    = JwtService::jwt_token_info();
        $account = $this->accountRepo->uidFind($info['ide']);

        if ($old_password == $new_password) {
            return res(422, 'new password not cant old password equal');
        }

        $bool = Hash::check($old_password, $account->password);

        if ($bool) {
            $account->update(['password' => bcrypt($new_password)]);

            $this->offline_all($info['ide']);

            return res(200, 'password update success');
        }

        return res(401, 'old password error');
    }


    /**
     * reset. 2019/7/28 11:49.
     *
     * @param ResetPasswordRequest $request
     *
     * @return JsonResponse
     */
    public function reset(ResetPasswordRequest $request)
    {
        $username = $request->input('username');
        $password = $request->input('password');

        $passport = $this->passportRepo->usernameFirst($username);
        $account  = $this->accountRepo->uidFind($passport->account_uid);
        $bool     = $account->update(['password' => bcrypt($password)]);
        $this->offline_all($passport->account_uid);

        return $bool ? res(200, 'password reset success') : res(500, 'Come back later');
    }


    /**
     * offline_all 2019/6/7 22:36
     *
     * @param int $uid
     *
     * @return void
     */
    private function offline_all(int $uid): void
    {
        $collect = \Redis::keys('token:' . $uid . '*');
        $prefix  = config('database.redis.options.prefix');
        foreach ($collect as $item) {
            \Redis::del(preg_replace("/$prefix/", "", $item));
        }
    }

}
