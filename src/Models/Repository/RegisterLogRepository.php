<?php
declare(strict_types=1);

namespace Woisks\Passport\Models\Repository;

use Woisks\Agent\AgentService;
use Woisks\Passport\Models\Entity\RegisterLog;


/**
 * Class RegisterLogRepository
 *
 * @package Woisks\Passport\Models\Repository
 *
 * @Author  Maple Grove  <bolelin@126.com> 2019/5/10 12:03
 */
class RegisterLogRepository
{


    /**
     * model  2019/5/10 12:03
     *
     * @var static \Woisks\Passport\Models\Entity\RegisterLog
     */
    private static $model;


    /**
     * RegisterLogRepository constructor. 2019/5/14 10:29
     *
     * @param \Woisks\Passport\Models\Entity\RegisterLog $registerLog
     *
     * @return void
     */
    public function __construct(RegisterLog $registerLog)
    {
        self::$model = $registerLog;
    }


    /**
     * created 2019/5/10 12:03
     *
     * @param int    $logID
     * @param int    $uid
     * @param string $account_type
     *
     * @return \Woisks\Passport\Models\Entity\RegisterLog
     */
    public function created(int $logID, int $uid, string $account_type): RegisterLog
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
