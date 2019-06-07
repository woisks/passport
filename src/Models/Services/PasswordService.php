<?php
declare(strict_types=1);


namespace Woisks\Passport\Models\Services;


use Hash;
use Woisks\Jwt\Services\JwtService;
use Woisks\Passport\Models\Repository\AccountRepository;
use Woisks\Passport\Models\Repository\PassportRepository;

/**
 * Class PasswordService
 *
 * @package Woisks\Passport\Models\Services
 *
 * @Author  Maple Grove  <bolelin@126.com> 2019/5/17 19:54
 */
class PasswordService
{
    /**
     * accountRepo  2019/5/17 19:54
     *
     * @var  \Woisks\Passport\Models\Repository\AccountRepository
     */
    public $accountRepo;
    /**
     * passportRepo  2019/6/7 22:15
     *
     * @var  \Woisks\Passport\Models\Repository\PassportRepository
     */
    public $passportRepo;


    /**
     * PasswordService constructor. 2019/6/7 22:15
     *
     * @param \Woisks\Passport\Models\Repository\AccountRepository  $accountRepository
     * @param \Woisks\Passport\Models\Repository\PassportRepository $passportRepository
     *
     * @return void
     */
    public function __construct(AccountRepository $accountRepository, PassportRepository $passportRepository)
    {
        $this->accountRepo = $accountRepository;
        $this->passportRepo = $passportRepository;
    }

    /**
     * update 2019/6/7 22:01
     *
     * @param string $old_password
     * @param string $new_password
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(string $old_password, string $new_password)
    {
        $info = JwtService::jwt_token_info();
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
     * reset 2019/6/7 22:15
     *
     * @param string $username
     * @param string $password
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function reset(string $username, string $password)
    {
        $passport = $this->passportRepo->usernameFirst($username);
        $account = $this->accountRepo->uidFind($passport->account_uid);
        $bool = $account->update(['password' => bcrypt($password)]);
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

        $prefix = config('database.redis.options.prefix');

        foreach ($collect as $item) {
            \Redis::del(preg_replace("/$prefix/", "", $item));
        }

    }
}