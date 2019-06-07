<?php
declare(strict_types=1);


namespace Woisks\Passport\Http\Controllers;


use Illuminate\Http\JsonResponse;
use Woisks\Passport\Http\Requests\ResetPasswordRequest;
use Woisks\Passport\Http\Requests\UpdatePasswordRequest;
use Woisks\Passport\Models\Services\PasswordService;

/**
 * Class PasswordController
 *
 * @package Woisks\Passport\Http\Controllers
 *
 * @Author  Maple Grove  <bolelin@126.com> 2019/5/17 19:55
 */
class PasswordController extends BaseController
{
    /**
     * passwordService  2019/5/17 19:55
     *
     * @var  \Woisks\Passport\Models\Services\PasswordService
     */
    public $passwordService;

    /**
     * PasswordController constructor. 2019/5/17 19:56
     *
     * @param \Woisks\Passport\Models\Services\PasswordService $passwordService
     *
     * @return void
     */
    public function __construct(PasswordService $passwordService)
    {
        $this->passwordService = $passwordService;
    }

    /**
     * update 2019/6/7 22:01
     *
     * @param \Woisks\Passport\Http\Requests\UpdatePasswordRequest $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(UpdatePasswordRequest $request)
    {
        $old_password = $request->input('old_password');
        $new_password = $request->input('password');

        return $this->passwordService->update($old_password, $new_password);
    }

    /**
     * reset 2019/6/7 22:14
     *
     * @param \Woisks\Passport\Http\Requests\ResetPasswordRequest $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function reset(ResetPasswordRequest $request): JsonResponse
    {
        $username = $request->input('username');
        $password = $request->input('password');

        return $this->passwordService->reset($username, $password);
    }

}