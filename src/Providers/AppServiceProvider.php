<?php
declare(strict_types=1);


namespace Woisks\Passport\Providers;


use Illuminate\Support\ServiceProvider;

/**
 * Class AppServiceProvider
 *
 * @package Woisks\PassportEntity\Providers
 *
 * @Author  Maple Grove  <bolelin@126.com> 2019/5/10 12:15
 */
class AppServiceProvider extends ServiceProvider
{

    /**
     * 2019/5/10 12:15
     *
     *
     * @return void
     */
    public function boot()
    {
        $this->loadRoutesFrom(__DIR__ . '/../routes.php');
    }
}
