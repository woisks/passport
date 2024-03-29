<?php
declare(strict_types=1);


namespace Woisks\Passport\Http\Controllers;


use Illuminate\Http\JsonResponse;
use Woisks\Jwt\Services\JwtService;

/**
 * Class LogoutController
 *
 * @package Woisks\PassportEntity\Http\Controllers
 *
 * @Author  Maple Grove  <bolelin@126.com> 2019/5/17 17:57
 */
class LogoutController extends BaseController
{
    /**
     * logout 2019/5/17 17:57
     *
     *
     * @return JsonResponse
     */
    public function logout()
    {
        $info = JwtService::jwt_token_info();
        \Redis::del('token:' . $info['ide'] . ':' . $info['mac']);

        return res(200, 'logout success');
    }
}
