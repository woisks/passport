<?php
declare(strict_types=1);

namespace Woisks\Passport\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Woisks\Passport\Http\Requests\UsernameRequest;
use Woisks\Passport\Models\Services\CheckService;


/**
 * Class CheckController
 *
 * @package Woisks\Passport\Http\Controllers
 *
 * @Author  Maple Grove  <bolelin@126.com> 2019/5/10 11:43
 */
class CheckController extends BaseController
{

    /**
     * checkService  2019/5/10 11:43
     *
     * @var \Woisks\Passport\Models\Services\CheckService
     */
    private $checkService;


    /**
     * CheckController constructor. 2019/5/10 11:43
     *
     * @param \Woisks\Passport\Models\Services\CheckService $checkService
     *
     * @return void
     */
    public function __construct(CheckService $checkService)
    {
        $this->checkService = $checkService;
    }


    /**
     * check 2019/5/14 9:20
     *
     * @param \Woisks\Passport\Http\Requests\UsernameRequest $request
     * @param string                                        $type
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function check(UsernameRequest $request, string $type): JsonResponse
    {
        $username = $request->input('username');

        switch ($type) {
            case 'register':
                $res = $this->checkService->register($username);
                break;
            case 'login':
                $res = $this->checkService->login($username);
                break;
            case 'passport':
                $res = $this->checkService->passport($username);
                break;
            default:
                $res = res(422, 'parameter Type Error');
        }

        return $res;
    }

}
