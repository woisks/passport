<?php
declare(strict_types=1);


namespace Woisks\Passport\Models\Services;


use Illuminate\Http\JsonResponse;
use Woisks\Jwt\Services\JwtService;
use Woisks\Passport\Models\Repository\PassportRepository;
use Woisks\Passport\Models\Repository\TypeCountRepository;

/**
 * Class PassportService
 *
 * @package Woisks\passport\Models\Services
 *
 * @Author  Maple Grove  <bolelin@126.com> 2019/5/14 14:28
 */
class PassportService
{
    /**
     * passportRepo  2019/5/14 14:28
     *
     * @var  \Woisks\Passport\Models\Repository\PassportRepository
     */
    private $passportRepo;

    /**
     * accountTypeRepo  2019/5/14 15:39
     *
     * @var  \Woisks\Passport\Models\Repository\TypeCountRepository
     */
    private $accountTypeRepo;


    /**
     * PassportService constructor. 2019/5/14 15:08
     *
     * @param \Woisks\Passport\Models\Repository\PassportRepository  $passportRepository
     * @param \Woisks\Passport\Models\Repository\TypeCountRepository $accountTypeRepository
     *
     * @return void
     */
    public function __construct(PassportRepository $passportRepository,
                                TypeCountRepository $accountTypeRepository)
    {
        $this->passportRepo = $passportRepository;
        $this->accountTypeRepo = $accountTypeRepository;
    }

    /**
     * usernameExists 2019/6/8 9:22
     *
     * @param string $username
     *
     * @return bool
     */
    public function usernameExists(string $username): bool
    {
        return $this->passportRepo->usernameExists($username);
    }

    /**
     * add 2019/5/14 15:40
     *
     * @param string $username
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function add(string $username): JsonResponse
    {
        $info = JwtService::jwt_token_info();
        $account_type = account_type($username);

        $exists = $this->passportRepo->uidTypeExists($info['ide'], $account_type);
        if ($exists) {
            return res(422, 'your ' . account_type($username) . ' type exists');
        }

        $this->passportRepo->created($info['ide'], $username, $account_type);
        $this->accountTypeRepo->typeIncrement($account_type);

        return res(200, 'success');
    }


    /**
     * del 2019/6/6 17:02
     *
     * @param string $username
     *
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function del(string $username): JsonResponse
    {
        $info = JwtService::jwt_token_info();
        $account_type = account_type($username);

        $passport = $this->passportRepo->usernameFirst($username);

        if (!$passport) {
            return res(404, 'your ' . $account_type . ' type not exists');
        }

        if ($info['ide'] == $passport->account_uid) {
            $passport->delete();
            $this->accountTypeRepo->typeDecrement($account_type);

            return res(200, 'success');
        }

        return res(404, 'your ' . $account_type . ' type not exists');
    }

    /**
     * bind 2019/6/7 20:32
     *
     * @param string $username
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function bind(string $username): JsonResponse
    {
        $info = JwtService::jwt_token_info();
        $account_type = account_type($username);

        $exists = $this->passportRepo->uidTypeExists($info['ide'], $account_type);

        if ($exists) {
            return res(422, 'your ' . $account_type . ' type exists');
        }

        $this->passportRepo->created($info['ide'], $username, $account_type);
        $this->accountTypeRepo->typeIncrement($account_type);

        return res(200, 'success');

    }

    /**
     * update 2019/6/7 20:34
     *
     * @param string $username
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(string $username): JsonResponse
    {
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


    /**
     * get 2019/6/8 9:42
     *
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function get(): JsonResponse
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

}
