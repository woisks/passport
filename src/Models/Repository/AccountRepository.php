<?php
declare(strict_types=1);

namespace Woisks\Passport\Models\Repository;

use Carbon\Carbon;
use Woisks\Passport\Models\Entity\Account;


/**
 * Class AccountRepository
 *
 * @package Woisks\Passport\Models\Repository
 *
 * @Author  Maple Grove  <bolelin@126.com> 2019/5/21 19:02
 */
class AccountRepository
{


    /**
     * model  2019/5/21 19:02
     *
     * @var static \Woisks\Passport\Models\Entity\Account
     */
    private static $model;


    /**
     * AccountRepository constructor. 2019/5/14 10:28
     *
     * @param \Woisks\Passport\Models\Entity\Account $account
     * @return void
     */
    public function __construct(Account $account)
    {
        self::$model = $account;
    }


    /**
     * initAccount 2019/6/6 16:31
     *
     * @param int    $uid
     * @param string $password
     *
     * @return \Woisks\Passport\Models\Entity\Account
     */
    public function initAccount(int $uid, string $password): Account
    {
        return self::$model->create([
            'id'               => $uid,
            'limit_login_time' => Carbon::now()->addMinute(-1)->timestamp,
            'password'         => $password
        ]);
    }


    /**
     * uidFind 2019/6/6 16:34
     *
     * @param int $uid
     *
     * @return null|\Woisks\Passport\Models\Entity\Account
     */
    public function uidFind(int $uid): ?Account
    {
        return self::$model->find($uid);
    }

}
