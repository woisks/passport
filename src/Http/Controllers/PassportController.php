<?php
declare(strict_types=1);


namespace Woisks\Passport\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Woisks\Passport\Http\Requests\UsernameRequest;
use Woisks\Passport\Models\Services\PassportService;

/**
 * Class PassportController
 *
 * @package Woisks\Passport\Http\Controllers
 *
 * @Author  Maple Grove  <bolelin@126.com> 2019/5/14 14:33
 */
class PassportController extends BaseController
{
    /**
     * passportService  2019/5/14 14:33
     *
     * @var  \Woisks\Passport\Models\Services\PassportService
     */
    public $passportService;

    /**
     * PassportController constructor. 2019/5/14 14:33
     *
     * @param \Woisks\Passport\Models\Services\PassportService $passportService
     *
     * @return void
     */
    public function __construct(PassportService $passportService)
    {
        $this->passportService = $passportService;
    }

    /**
     * get 2019/6/7 20:41
     *
     *
     * @return mixed
     */
    public function get()
    {
        return $this->passportService->get();
    }

    /**
     * add 2019/6/7 20:12
     *
     * @param \Woisks\Passport\Http\Requests\UsernameRequest $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function add(UsernameRequest $request): JsonResponse
    {
        $username = $request->input('username');

        return $this->passportService->add($username);
    }

    /**
     * del 2019/6/7 19:02
     *
     * @param \Woisks\Passport\Http\Requests\UsernameRequest $request
     *
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function del(UsernameRequest $request): JsonResponse
    {
        $username = $request->input('username');

        return $this->passportService->del($username);
    }


    /**
     * bind 2019/6/7 20:33
     *
     * @param \Woisks\Passport\Http\Requests\UsernameRequest $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function bind(UsernameRequest $request)
    {
        $username = $request->input('username');

        return $this->passportService->bind($username);
    }

    /**
     * update 2019/6/7 20:33
     *
     * @param \Woisks\Passport\Http\Requests\UsernameRequest $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(UsernameRequest $request)
    {
        $username = $request->input('username');

        return $this->passportService->update($username);
    }

}
