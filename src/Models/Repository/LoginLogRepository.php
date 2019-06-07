<?php
declare(strict_types=1);

namespace Woisks\Passport\Models\Repository;

use Woisks\Agent\AgentService;
use Woisks\Passport\Models\Entity\LoginLog;


/**
 * Class LoginLogRepository
 *
 * @package Woisks\Passport\Models\Repository
 *
 * @Author  Maple Grove  <bolelin@126.com> 2019/5/10 12:01
 */
class LoginLogRepository
{


    /**
     * model  2019/5/10 12:01
     *
     * @var static \Woisks\Passport\Models\Entity\LoginLog
     */
    private static $model;

    /**
     * LoginLogRepository constructor. 2019/5/14 10:29
     *
     * @param \Woisks\Passport\Models\Entity\LoginLog $loginLog
     *
     * @return void
     */
    public function __construct(LoginLog $loginLog)
    {
        self::$model = $loginLog;
    }


    /**
     * created 2019/5/10 12:01
     *
     * @param int    $logID
     * @param int    $uid
     * @param string $account_type
     *
     * @return \Woisks\Passport\Models\Entity\LoginLog
     */
    public function created(int $logID, int $uid, string $account_type): LoginLog
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
