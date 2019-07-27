<?php
declare(strict_types=1);


namespace Woisks\Passport\Http\Controllers;

use Woisks\Jwt\Services\JwtService;
use Woisks\Passport\Http\Requests\UsernameRequest;
use Woisks\Passport\Models\Repository\PassportRepository;
use Woisks\Passport\Models\Repository\TypeCountRepository;

/**
 * Class PassportController
 *
 * @package Woisks\PassportEntity\Http\Controllers
 *
 * @Author  Maple Grove  <bolelin@126.com> 2019/5/14 14:33
 */
class PassportController extends BaseController
{
    /**
     * passportRepo.  2019/7/27 2:36.
     *
     * @var  \Woisks\Passport\Models\Repository\PassportRepository
     */
    private $passportRepo;
    /**
     * typeCountRepo.  2019/7/27 2:36.
     *
     * @var  \Woisks\Passport\Models\Repository\TypeCountRepository
     */
    private $typeCountRepo;


    /**
     * PassportController constructor. 2019/7/27 2:37.
     *
     * @param \Woisks\Passport\Models\Repository\PassportRepository  $passportRepo
     * @param \Woisks\Passport\Models\Repository\TypeCountRepository $typeCountRepo
     *
     * @return void
     */
    public function __construct(PassportRepository $passportRepo, TypeCountRepository $typeCountRepo)
    {
        $this->typeCountRepo = $typeCountRepo;
        $this->passportRepo = $passportRepo;
    }

    /**
     * get. 2019/7/27 2:36.
     *
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function get()
    {
        $db = $this->passportRepo->uidGet(JwtService::jwt_token_info()['ide'])->filter(function ($value) {
            if ($value->account_type == 'phone') {
                return $value->username = substr($value->username, 0, 3) . str_repeat('*', 6) . substr($value->username, -1 - 1);
            }

            if ($value->account_type == 'email') {

                $str = \Str::before($value->username, '@');

                return $value->username = substr($str, 0, 3) . str_repeat('*', strlen($str) - 3) . '@' . \Str::after($value->username, '@');
            }

            return $value->username;
        });

        $data = [];
        foreach ($db as $item) {

            if ($item->account_type == 'phone' || $item->account_type == 'email') {
                $data['bind'][] = $item;
            }
            if ($item->account_type == 'numeric' || $item->account_type == 'username') {
                $data['alias'][] = $item;
            }
        }

        return res(200, 'success', $data);
    }


    /**
     * add. 2019/7/27 2:37.
     *
     * @param \Woisks\Passport\Http\Requests\UsernameRequest $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function add(UsernameRequest $request)
    {
        $username = $request->input('username');
        if ($this->passportRepo->usernameExists($username)) {
            return res(422, 'username exists');
        }

        $info = JwtService::jwt_token_info();
        $account_type = account_type($username);

        $exists = $this->passportRepo->uidTypeExists($info['ide'], $account_type);
        if ($exists) {
            return res(422, 'your ' . account_type($username) . ' type exists');
        }

        $this->passportRepo->created($info['ide'], $username, $account_type);
        $this->typeCountRepo->typeIncrement($account_type);

        return res(200, 'success');
    }

    /**
     * del. 2019/7/27 2:37.
     *
     * @param \Woisks\Passport\Http\Requests\UsernameRequest $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function del(UsernameRequest $request)
    {
        $username = $request->input('username');

        if (!$this->passportRepo->usernameExists($username)) {
            return res(404, 'username not exists');
        }

        $info = JwtService::jwt_token_info();
        $account_type = account_type($username);

        $passport = $this->passportRepo->usernameFirst($username);

        if (!$passport) {
            return res(404, 'your ' . $account_type . ' type not exists');
        }

        if ($info['ide'] == $passport->account_uid) {
            $passport->delete();
            $this->typeCountRepo->typeDecrement($account_type);

            return res(200, 'success');
        }

        return res(404, 'your ' . $account_type . ' type not exists');
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

        if ($this->passportRepo->usernameExists($username)) {
            return res(422, 'username exists');
        }

        $info = JwtService::jwt_token_info();
        $account_type = account_type($username);

        $exists = $this->passportRepo->uidTypeExists($info['ide'], $account_type);

        if ($exists) {
            return res(422, 'your ' . $account_type . ' type exists');
        }

        $this->passportRepo->created($info['ide'], $username, $account_type);
        $this->typeCountRepo->typeIncrement($account_type);

        return res(200, 'success');
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
        if ($this->passportRepo->usernameExists($username)) {
            return res(422, 'username exists');
        }

        $info = JwtService::jwt_token_info();
        $account_type = account_type($username);

        $exists = $this->passportRepo->uidTypeExists($info['ide'], $account_type);

        if (!$exists) {
            return res(404, 'your ' . $account_type . ' type not exists');
        }

        $bool = $this->passportRepo->updated($info['ide'], $account_type, $username);

        return $bool
            ? res(200, 'update success')
            : res(500, 'Come back later');
    }

}
