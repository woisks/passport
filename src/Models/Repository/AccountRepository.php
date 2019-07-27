<?php
declare(strict_types=1);

namespace Woisks\Passport\Models\Repository;

use Carbon\Carbon;
use Woisks\Passport\Models\Entity\AccountEntity;


/**
 * Class AccountRepository
 *
 * @package Woisks\PassportEntity\Models\Repository
 *
 * @Author  Maple Grove  <bolelin@126.com> 2019/5/21 19:02
 */
class AccountRepository
{


    /**
     * model  2019/5/21 19:02
     *
     * @var static \Woisks\PassportEntity\Models\Entity\AccountEntity
     */
    private static $model;


    /**
     * AccountRepository constructor. 2019/5/14 10:28
     *
     * @param \Woisks\Passport\Models\Entity\AccountEntity $account
     * @return void
     */
    public function __construct(AccountEntity $account)
    {
        self::$model = $account;
    }


    /**
     * initAccount. 2019/7/26 22:59.
     *
     * @param $uid
     * @param $password
     *
     * @return mixed
     */
    public function initAccount($uid, $password)
    {
        return self::$model->create([
            'id'               => $uid,
            'limit_login_time' => Carbon::now()->addMinute(-1)->timestamp,
            'password'         => $password
        ]);
    }


    /**
     * uidFind. 2019/7/26 23:00.
     *
     * @param $uid
     *
     * @return mixed
     */
    public function uidFind($uid)
    {
        return self::$model->find($uid);
    }

}
