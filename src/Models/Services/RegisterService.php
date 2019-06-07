<?php
declare(strict_types=1);

namespace Woisks\Passport\Models\Services;

use Woisks\Passport\Models\Entity\Account;
use Woisks\Passport\Models\Entity\Passport;
use Woisks\Passport\Models\Repository\AccountRepository;
use Woisks\Passport\Models\Repository\PassportRepository;
use Woisks\Passport\Models\Repository\TypeCountRepository;

/**
 * Class RegisterService
 *
 * @package Woisks\Passport\Models\Services
 * ------------------------------------------------------
 * @Author  : Maple Grove  <bolelin@126.com> 2019/5/7 11:46
 */
class RegisterService
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

    /**
     * account 2019/6/6 17:05
     *
     * @param int    $uid
     * @param string $password
     *
     * @return \Woisks\Passport\Models\Entity\Account
     */
    public function account(int $uid, string $password): Account
    {
        return $this->accountRepo->initAccount($uid, $password);
    }

    /**
     * usernameExists 2019/5/21 19:46
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
     * passport 2019/6/6 17:09
     *
     * @param int    $uid
     * @param string $username
     * @param string $account_type
     *
     * @return \Woisks\Passport\Models\Entity\Passport
     */
    public function passport(int $uid, string $username, string $account_type): Passport
    {
        return $this->passportRepo->created($uid, $username, $account_type);
    }


    /**
     * accountType 2019/5/21 20:14
     *
     * @param string $account_type
     *
     * @return int
     */
    public function accountType(string $account_type): int
    {
        return $this->accountTypeRepo->typeIncrement($account_type);
    }

}
