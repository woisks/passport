<?php
declare(strict_types=1);

namespace Woisks\Passport\Http\Controllers;


use DB;
use Hash;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Throwable;
use Woisks\Passport\Events\RegisterEvent;
use Woisks\Passport\Http\Requests\RegisterRequest;
use Woisks\Passport\Models\Services\RegisterService;

/**
 * Class RegisterController
 *
 * @package Woisks\Passport\Http\Controllers
 *
 * @Author  Maple Grove  <bolelin@126.com> 2019/5/10 11:44
 */
class RegisterController extends BaseController
{

    /**
     * registerService  2019/5/10 11:44
     *
     * @var \Woisks\Passport\Models\Services\RegisterService
     */
    protected $registerService;


    /**
     * RegisterController constructor. 2019/5/10 11:44
     *
     * @param \Woisks\Passport\Models\Services\RegisterService $registerService
     *
     * @return void
     */
    public function __construct(RegisterService $registerService)
    {
        $this->registerService = $registerService;
    }


    /**
     * register 2019/5/14 15:16
     *
     * @param \Woisks\Passport\Http\Requests\RegisterRequest $request
     *
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function register(RegisterRequest $request): JsonResponse
    {
        $username = $request->input('username');

        if (!is_email($username) && !is_phone($username)) {

            return res(422, 'parameter error require china phone or proper email');
        }

        if ($this->registerService->usernameExists($username)) {
            return res(422, 'username exists');
        }

        return $this->repository($request, $username);
    }

    /**
     * repository 2019/5/24 20:25
     *
     * @param $request
     * @param $username
     *
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    private function repository($request, $username)
    {
        try {
            DB::beginTransaction();

            $this->service($request, $username);
        } catch (Throwable   $e) {
            DB::rollback();

            return res(500, 'Come back later');
        }
        DB::commit();

        return res(200, 'Register Success');
    }

    /**
     * registerServices 2019/5/14 13:44
     *
     * @param \Illuminate\Http\Request $request
     * @param string                   $username
     *
     * @return void
     */
    private function service(Request $request, string $username)
    {
        $uid = create_numeric_uid();
        $password = Hash::make($request->input('password'));
        $account_type = account_type($username);

        $this->registerService->account($uid, $password);
        $this->registerService->passport($uid, $username, $account_type);
        $this->registerService->accountType($account_type);

        event(new RegisterEvent('register', create_numeric_id(), $uid, $account_type));
    }
}
