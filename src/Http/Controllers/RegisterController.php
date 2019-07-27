<?php
declare(strict_types=1);

namespace Woisks\Passport\Http\Controllers;


use DB;
use Hash;
use Throwable;
use Woisks\Agent\AgentService;
use Woisks\Passport\Events\RegisterEvent;
use Woisks\Passport\Http\Requests\RegisterRequest;
use Woisks\Passport\Models\Repository\AccountRepository;
use Woisks\Passport\Models\Repository\PassportRepository;
use Woisks\Passport\Models\Repository\TypeCountRepository;

/**
 * Class RegisterController
 *
 * @package Woisks\PassportEntity\Http\Controllers
 *
 * @Author  Maple Grove  <bolelin@126.com> 2019/5/10 11:44
 */
class RegisterController extends BaseController
{

    /**
     * accountRepo  2019/5/10 11:28
     *
     * @var \Woisks\Passport\Models\Repository\AccountRepository
     */
    private $accountRepo;


    /**
     * passportRepo  2019/5/10 11:28
     *
     * @var \Woisks\Passport\Models\Repository\PassportRepository
     */
    private $passportRepo;


    /**
     * accountTypeRepo  2019/5/10 11:52
     *
     * @var  \Woisks\Passport\Models\Repository\TypeCountRepository
     */
    private $accountTypeRepo;


    /**
     * RegisterService constructor. 2019/5/10 11:28
     *
     * @param \Woisks\Passport\Models\Repository\AccountRepository   $accountRepo
     * @param \Woisks\Passport\Models\Repository\PassportRepository  $passportRepo
     * @param \Woisks\Passport\Models\Repository\TypeCountRepository $accountTypeRepo
     *
     * @return void
     */
    public function __construct(AccountRepository $accountRepo,
                                PassportRepository $passportRepo,
                                TypeCountRepository $accountTypeRepo)
    {
        $this->accountRepo = $accountRepo;
        $this->passportRepo = $passportRepo;
        $this->accountTypeRepo = $accountTypeRepo;
    }


    public function register(RegisterRequest $request)
    {
        $username = $request->input('username');

        if (!is_email($username) && !is_phone($username)) {

            return res(422, 'param error require china phone or proper email');
        }

        if ($this->passportRepo->usernameExists($username)) {
            return res(422, 'username exists');
        }

        return $this->services($request, $username);
    }

    /**
     * services 2019/5/24 20:25
     *
     * @param $request
     * @param $username
     *
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    private function services($request, $username)
    {
        try {
            DB::beginTransaction();

            $uid = create_numeric_uid();
            $password = Hash::make($request->input('password'));
            $account_type = account_type($username);

            $this->accountRepo->initAccount($uid, $password);
            $this->passportRepo->created($uid, $username, $account_type);
            $this->accountTypeRepo->typeIncrement($account_type);

            $agent = AgentService::info();
            event(new RegisterEvent('register', create_numeric_id(), $uid, $account_type, request()->getClientIp(),
                $agent['os'], $agent['client'], $agent['brand_model'], $agent['device']));

        } catch (Throwable   $e) {
            DB::rollback();
            dd($e);

            return res(422, 'Come back later');
        }
        DB::commit();

        return res(200, 'Register Success');
    }


}
