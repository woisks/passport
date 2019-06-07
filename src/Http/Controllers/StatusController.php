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

namespace Woisks\Passport\Http\Controllers;


use Arr;
use Woisks\Jwt\Services\JwtService;
use Woisks\Passport\Http\Requests\OfflineRequest;
use Woisks\Passport\Models\Entity\LoginLog;

/**
 * Class StatusController
 *
 * @package Woisks\Passport\Http\Controllers
 *
 * @Author  Maple Grove  <bolelin@126.com> 2019/6/8 0:56
 */
class StatusController extends BaseController
{
    /**
     * online 2019/6/8 0:56
     *
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function online()
    {
        $info = JwtService::jwt_token_info();
        $redis_collect = \Redis::keys('token:' . $info['ide'] . '*');

        $prefix = config('database.redis.options.prefix');

        $_mac = $prefix . 'token:' . $info['ide'] . ':';

        $mac = [];
        $log_id = [];
        foreach ($redis_collect as $item) {
            $mac[] = preg_replace("/$_mac/", "", $item);
            $log_id[] = \Redis::get(preg_replace("/$prefix/", "", $item));
        }

        $db = LoginLog::find($log_id);

        $data = [];
        foreach ($db as $key => $value) {
            if ($mac[$key] == $info['mac']) {

                $data[$mac[$key]] = Arr::add($value, 'current', true);
            }
            $data[$mac[$key]] = $value;
        }

        return res(200, 'success', ['count' => count($redis_collect), 'online' => $data]);


    }

    /**
     * offline 2019/6/8 0:56
     *
     * @param \Woisks\Passport\Http\Requests\OfflineRequest $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function offline(OfflineRequest $request)
    {
        $mac = $request->input('mac');

        $info = JwtService::jwt_token_info();
        $redis_collect = \Redis::keys('token:' . $info['ide'] . '*');

        $prefix = config('database.redis.options.prefix') . 'token:' . $info['ide'] . ':';

        $macs = [];
        foreach ($redis_collect as $item) {
            $macs[] = preg_replace("/$prefix/", "", $item);
        }

        if (!Arr::has(array_flip($macs), $mac)) {
            return res(404, 'mac not exists');
        }

        $del = \Redis::del('token:' . $info['ide'] . ':' . $mac);

        return $del ? res(200, 'offline success') : res(500, 'Come back later');

    }
}