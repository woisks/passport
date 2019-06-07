<?php
declare(strict_types=1);


namespace Woisks\Passport\Models\Services;


use Illuminate\Http\JsonResponse;
use Woisks\Passport\Models\Repository\PassportRepository;


/**
 * Class CheckService
 *
 * @package Woisks\Passport\Models\Services
 *
 * @Author  Maple Grove  <bolelin@126.com> 2019/5/10 11:14
 */
class CheckService
{

    /**
     * passport  2019/5/10 11:22
     *
     * @var \Woisks\Passport\Models\Repository\PassportRepository
     */
    private $passportRepo;


    /**
     * CheckService constructor. 2019/5/10 11:22
     *
     * @param \Woisks\Passport\Models\Repository\PassportRepository $passport
     *
     * @return void
     */
    public function __construct(PassportRepository $passport)
    {
        $this->passportRepo = $passport;
    }

    /**
     * register 2019/5/10 11:24
     *
     * @param string $username
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function register(string $username): JsonResponse
    {
        if (!is_email($username) && !is_phone($username)) {
            return res(422, 'param error require china phone or proper email');
        }

        return $this->passportRepo->usernameExists($username)
            ? res(422, 'Username Exists')
            : res(200, 'ok');
    }


    /**
     * login 2019/5/10 11:25
     *
     * @param string $username
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(string $username): JsonResponse
    {
        return $this->passportRepo->usernameExists($username) ?
            res(200, 'ok') :
            res(422, 'Username Not Exists');
    }


    /**
     * passport 2019/5/10 11:25
     *
     * @param string $username
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function passport(string $username): JsonResponse
    {
        return $this->passportRepo->usernameExists($username)
            ? res(422, 'Username Exists')
            : res(200, 'ok');
    }
}
