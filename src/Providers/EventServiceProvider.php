<?php
declare(strict_types=1);

namespace Woisks\Passport\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Woisks\Passport\Events\RegisterEvent;
use Woisks\Passport\Listeners\PassportLogListener;


/**
 * Class EventServiceProvider
 *
 * @package Woisks\Passport\Providers
 *
 * @Author  Maple Grove  <bolelin@126.com> 2019/5/10 12:15
 */
class EventServiceProvider extends ServiceProvider
{


    /**
     * listen  2019/5/10 12:15
     *
     * @var  array
     */
    protected $listen = [
        RegisterEvent::class => [
            PassportLogListener::class,
        ]
    ];

}
