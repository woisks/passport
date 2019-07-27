<?php
declare(strict_types=1);

namespace Woisks\Passport\Models\Repository;

use Woisks\Passport\Models\Entity\PassportEntity;


/**
 * Class PassportRepository
 *
 * @package Woisks\PassportEntity\Models\Repository
 *
 * @Author  Maple Grove  <bolelin@126.com> 2019/5/10 12:01
 */
class PassportRepository
{
    /**
     * model  2019/5/10 12:01
     *
     * @var static \Woisks\PassportEntity\Models\Entity\PassportEntity
     */
    private static $model;


    /**
     * PassportRepository constructor. 2019/5/14 10:29
     *
     * @param \Woisks\Passport\Models\Entity\PassportEntity $passport
     * @return void
     */
    public function __construct(PassportEntity $passport)
    {
        self::$model = $passport;
    }


    /**
     * usernameExists. 2019/7/26 23:05.
     *
     * @param $username
     *
     * @return mixed
     */
    public function usernameExists($username)
    {
        return self::$model->where('username', $username)->exists();
    }


    /**
     * created. 2019/7/26 23:05.
     *
     * @param        $uid
     * @param        $username
     * @param        $account_type
     * @param string $password
     *
     * @return mixed
     */
    public function created($uid, $username, $account_type, $password = '')
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
     * uidTypeExists. 2019/7/26 23:05.
     *
     * @param $uid
     * @param $account_type
     *
     * @return mixed
     */
    public function uidTypeExists($uid, $account_type)
    {
        return self::$model->where('account_uid', $uid)->where('account_type', $account_type)->exists();
    }


    /**
     * usernameFirst. 2019/7/26 23:05.
     *
     * @param string $username
     *
     * @return mixed
     */
    public function usernameFirst(string $username)
    {
        return self::$model->where('username', $username)->first();
    }


    /**
     * updated. 2019/7/26 23:05.
     *
     * @param $uid
     * @param $account_type
     * @param $username
     *
     * @return mixed
     */
    public function updated($uid, $account_type, $username)
    {
        return self::$model->where('account_uid', $uid)
                           ->where('account_type', $account_type)
                           ->update(['username' => $username]);
    }


    /**
     * uidGet. 2019/7/26 23:05.
     *
     * @param $uid
     *
     * @return mixed
     */
    public function uidGet($uid)
    {
        return self::$model->where('account_uid', $uid)->get();
    }
}
