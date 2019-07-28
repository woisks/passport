<?php
declare(strict_types=1);

namespace Woisks\Passport\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Woisks\Passport\Models\Repository\PassportRepository;
use Woisks\User\Models\Services\UserServices;


/**
 * Class CheckController
 *
 * @package Woisks\PassportEntity\Http\Controllers
 *
 * @Author  Maple Grove  <bolelin@126.com> 2019/5/10 11:43
 */
class CheckController extends BaseController
{


    /**
     * passportRepo.  2019/7/28 11:35.
     *
     * @var  PassportRepository
     */
    private $passportRepo;

    /**
     * CheckController constructor. 2019/7/28 11:35.
     *
     * @param PassportRepository $passportRepo
     *
     * @return void
     */
    public function __construct(PassportRepository $passportRepo)
    {
        $this->passportRepo = $passportRepo;
    }


    /**
     * check. 2019/7/28 11:35.
     *
     * @param $type
     * @param $username
     *
     * @return JsonResponse
     */
    public function check($type, $username)
    {
        switch ($type) {
            case 'register':
                $res = $this->register($username);
                break;
            case 'login':
                $res = $this->login($username);
                break;
            case 'passport':
                $res = $this->passport($username);
                break;
            default:
                $res = res(422, 'param Type Error');
        }

        return $res;
    }


    /**
     * register. 2019/7/26 22:35.
     *
     * @param string $username
     *
     * @return JsonResponse
     */
    private function register(string $username)
    {
        if (!is_email_and_check_dns($username) && !is_phone($username)) {
            return res(422, 'param error require china phone numeric or proper email');
        }

        return $this->passportRepo->usernameExists($username)
            ? res(422, 'Username Exists')
            : res(200, 'success');
    }


    /**
     * login 2019/5/10 11:25
     *
     * @param string $username
     *
     * @return JsonResponse
     */
    private function login(string $username)
    {
        $db = $this->passportRepo->usernameFirst($username);
        if (!$db) {
            return res(404, 'Username Not Exists');
        }

        return res(200, 'success', UserServices::avatar($db->account_uid));
    }


    /**
     * passport 2019/5/10 11:25
     *
     * @param string $username
     *
     * @return JsonResponse
     */
    private function passport(string $username)
    {
        return $this->passportRepo->usernameExists($username)
            ? res(422, 'Username Exists')
            : res(200, 'success');
    }

}
