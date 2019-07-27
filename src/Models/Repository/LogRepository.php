<?php

declare(strict_types=1);

/*
 * +----------------------------------------------------------------------+
 * |                   At all timesI love the moment                      |
 * +----------------------------------------------------------------------+
 * | Copyright (c) 2019 www.Woisk.com All rights reserved.                |
 * +----------------------------------------------------------------------+
 * |  Author:  Maple Grove  <bolelin@126.com>   QQ:364956690   286013629  |
 * +----------------------------------------------------------------------+
 */

namespace Woisks\Passport\Models\Repository;


use Woisks\Passport\Models\Entity\LoginFailLogEntity;
use Woisks\Passport\Models\Entity\LoginLogEntity;
use Woisks\Passport\Models\Entity\RegisterLogEntity;

/**
 * Class LogRepository.
 *
 * @package Woisks\Passport\Models\Repository
 *
 * @Author  Maple Grove  <bolelin@126.com> 2019/7/26 23:20
 */
class LogRepository
{
    /**
     * register.  2019/7/26 23:20.
     *
     * @var static \Woisks\Passport\Models\Entity\RegisterLogEntity
     */
    private static $register;
    /**
     * login.  2019/7/26 23:20.
     *
     * @var static \Woisks\Passport\Models\Entity\LoginLogEntity
     */
    private static $login;
    /**
     * loginFail.  2019/7/26 23:20.
     *
     * @var static \Woisks\Passport\Models\Entity\LoginFailLogEntity
     */
    private static $loginFail;

    /**
     * LogRepository constructor. 2019/7/26 23:20.
     *
     * @param \Woisks\Passport\Models\Entity\RegisterLogEntity  $register
     * @param \Woisks\Passport\Models\Entity\LoginLogEntity     $login
     * @param \Woisks\Passport\Models\Entity\LoginFailLogEntity $loginFail
     *
     * @return void
     */
    public function __construct(RegisterLogEntity $register, LoginLogEntity $login, LoginFailLogEntity $loginFail)
    {
        self::$register = $register;
        self::$login = $login;
        self::$loginFail = $loginFail;
    }

    /**
     * loginFail_delete. 2019/7/27 1:29.
     *
     * @param $account_uid
     *
     * @return mixed
     */
    public function loginFail_delete($account_uid)
    {
        return self::$loginFail->where('account_uid', $account_uid)->delete();
    }

    /**
     * loginFail. 2019/7/26 23:20.
     *
     * @param $logID
     * @param $uid
     * @param $account_type
     * @param $ip
     * @param $system
     * @param $client
     * @param $brand_model
     * @param $device_type
     *
     * @return mixed
     */
    public function loginFail($logID, $uid, $account_type, $ip, $system, $client, $brand_model, $device_type)
    {
        return self::$loginFail->create([
            'id'           => $logID,
            'account_uid'  => $uid,
            'account_type' => $account_type,
            'ip'           => $ip,
            'system'       => $system,
            'client'       => $client,
            'brand_model'  => $brand_model,
            'device_type'  => $device_type
        ]);
    }

    /**
     * login. 2019/7/26 23:20.
     *
     * @param $logID
     * @param $uid
     * @param $account_type
     * @param $ip
     * @param $system
     * @param $client
     * @param $brand_model
     * @param $device_type
     *
     * @return mixed
     */
    public function login($logID, $uid, $account_type, $ip, $system, $client, $brand_model, $device_type)
    {
        return self::$login->create([
            'id'           => $logID,
            'account_uid'  => $uid,
            'account_type' => $account_type,
            'ip'           => $ip,
            'system'       => $system,
            'client'       => $client,
            'brand_model'  => $brand_model,
            'device_type'  => $device_type
        ]);
    }

    /**
     * register. 2019/7/26 23:20.
     *
     * @param $logID
     * @param $uid
     * @param $account_type
     * @param $ip
     * @param $system
     * @param $client
     * @param $brand_model
     * @param $device_type
     *
     * @return mixed
     */
    public function register($logID, $uid, $account_type, $ip, $system, $client, $brand_model, $device_type)
    {
        return self::$register->create([
            'id'           => $logID,
            'account_uid'  => $uid,
            'account_type' => $account_type,
            'ip'           => $ip,
            'system'       => $system,
            'client'       => $client,
            'brand_model'  => $brand_model,
            'device_type'  => $device_type
        ]);
    }
}