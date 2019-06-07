<?php
declare(strict_types=1);

namespace Woisks\Passport\Models\Repository;

use Woisks\Passport\Models\Entity\Passport;


/**
 * Class PassportRepository
 *
 * @package Woisks\Passport\Models\Repository
 *
 * @Author  Maple Grove  <bolelin@126.com> 2019/5/10 12:01
 */
class PassportRepository
{
    /**
     * model  2019/5/10 12:01
     *
     * @var static \Woisks\Passport\Models\Entity\Passport
     */
    private static $model;


    /**
     * PassportRepository constructor. 2019/5/14 10:29
     *
     * @param \Woisks\Passport\Models\Entity\Passport $passport
     * @return void
     */
    public function __construct(Passport $passport)
    {
        self::$model = $passport;
    }


    /**
     * usernameExists 2019/5/10 12:01
     *
     * @param string $username
     *
     * @return bool
     */
    public function usernameExists(string $username): bool
    {
        return self::$model->where('username', $username)->exists();
    }


    /**
     * created 2019/6/6 16:23
     *
     * @param int    $uid
     * @param string $username
     * @param string $account_type
     * @param string $password
     *
     * @return \Woisks\Passport\Models\Entity\Passport
     */
    public function created(int $uid, string $username, string $account_type, string $password = ''): Passport
    {
        return self::$model->create([
            'id'           => create_numeric_id(),
            'account_uid'  => $uid,
            'username'     => $username,
            'account_type' => $account_type,
            'password'     => $password
        ]);
    }


    /**
     * uidTypeExists 2019/5/14 15:36
     *
     * @param int    $uid
     * @param string $account_type
     *
     * @return bool
     */
    public function uidTypeExists(int $uid, string $account_type): bool
    {
        return self::$model->where('account_uid', $uid)->where('account_type', $account_type)->exists();
    }


    /**
     * usernameFirst 2019/6/6 16:29
     *
     * @param string $username
     *
     * @return null|\Woisks\Passport\Models\Entity\Passport
     */
    public function usernameFirst(string $username): ?Passport
    {
        return self::$model->where('username', $username)->first();
    }


    /**
     * updated 2019/5/16 10:41
     *
     * @param int    $uid
     * @param string $account_type
     * @param string $username
     *
     * @return int
     */
    public function updated(int $uid, string $account_type, string $username): int
    {
        return self::$model->where('account_uid', $uid)
                           ->where('account_type', $account_type)
                           ->update(['username' => $username]);
    }

    /**
     * uidGet 2019/6/7 20:39
     *
     * @param int $uid
     *
     * @return mixed
     */
    public function uidGet(int $uid)
    {
        return self::$model->where('account_uid',$uid)->get();
    }
}
