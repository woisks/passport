<?php
declare(strict_types=1);

namespace Woisks\Passport\Models\Repository;

use Woisks\Agent\AgentService;
use Woisks\Passport\Models\Entity\LoginFailLog;

/**
 * Class LoginFailLogRepository
 *
 * @package Woisks\Passport\Models\Repository
 *
 * @Author  Maple Grove  <bolelin@126.com> 2019/5/21 19:02
 */
class LoginFailLogRepository
{


    /**
     * model  2019/5/10 12:00
     *
     * @var static \Woisks\Passport\Models\Entity\LoginFailLog
     */
    private static $model;


    /**
     * LoginFailLogRepository constructor. 2019/5/14 10:28
     *
     * @param \Woisks\Passport\Models\Entity\LoginFailLog $loginFailLog
     *
     * @return void
     */
    public function __construct(LoginFailLog $loginFailLog)
    {
        self::$model = $loginFailLog;
    }


    /**
     * created 2019/6/6 16:37
     *
     * @param int    $logID
     * @param int    $uid
     * @param string $account_type
     *
     * @return \Woisks\Passport\Models\Entity\LoginFailLog
     */
    public function created(int $logID, int $uid, string $account_type): LoginFailLog
    {
        return self::$model->create([
            'id'           => $logID,
            'account_uid'  => $uid,
            'account_type' => $account_type,
            'ip'           => request()->getClientIp(),
            'system'       => AgentService::info()['os'],
            'client'       => AgentService::info()['client'],
            'brand_model'  => AgentService::info()['brand_model'],
            'device_type'  => AgentService::info()['device']
        ]);
    }
}
