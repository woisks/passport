<?php
declare(strict_types=1);

namespace Woisks\Passport\Listeners;

use Woisks\Passport\Events\RegisterEvent;
use Woisks\Passport\Models\Repository\LoginFailLogRepository;
use Woisks\Passport\Models\Repository\LoginLogRepository;
use Woisks\Passport\Models\Repository\RegisterLogRepository;


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
     * handle 2019/5/10 11:46
     *
     * @param \Woisks\Passport\Events\RegisterEvent $event
     *
     * @return void
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    public function handle(RegisterEvent $event)
    {

        switch ($event->type) {
            case 'register':
                app()->make(RegisterLogRepository::class)->created($event->logID, $event->uid, $event->account_type);
                break;
            case 'login':
                app()->make(LoginLogRepository::class)->created($event->logID, $event->uid, $event->account_type);
                break;
            case 'fail':
                app()->make(LoginFailLogRepository::class)->created($event->logID, $event->uid, $event->account_type);
                break;
            default:
        }


    }

}
