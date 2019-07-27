<?php
declare(strict_types=1);

namespace Woisks\Passport\Listeners;

use Woisks\Passport\Events\RegisterEvent;
use Woisks\Passport\Models\Repository\LogRepository;


/**
 * Class PassportLogListener
 *
 * @package Woisks\PassportRepository\Listeners
 *
 * @Author  Maple Grove  <bolelin@126.com> 2019/5/10 11:46
 */
class PassportLogListener
{


    /**
     * handle. 2019/7/26 23:24.
     *
     * @param \Woisks\Passport\Events\RegisterEvent $event
     *
     * @return void
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    public function handle(RegisterEvent $event)
    {

        $logRepo = app()->make(LogRepository::class);

        switch ($event->type) {
            case 'register':
                $logRepo->register($event->logID, $event->account_uid, $event->account_type, $event->ip, $event->system, $event->client, $event->brand_model, $event->device_type);
                break;
            case 'login':
                $logRepo->login($event->logID, $event->account_uid, $event->account_type, $event->ip, $event->system, $event->client, $event->brand_model, $event->device_type);
                break;
            case 'fail':
                $logRepo->loginFail($event->logID, $event->account_uid, $event->account_type, $event->ip, $event->system, $event->client, $event->brand_model, $event->device_type);
                break;
            default:
        }


    }

}
