<?php
declare(strict_types=1);

namespace Woisks\Passport\Events;


/**
 * Class RegisterEvent
 *
 * @package Woisks\Passport\Events
 *
 * @Author  Maple Grove  <bolelin@126.com> 2019/5/10 11:42
 */
class RegisterEvent
{
    /**
     * type  2019/5/10 11:42
     *
     * @var string
     */
    public $type;


    /**
     * logID  2019/5/10 11:42
     *
     * @var int
     */
    public $logID;


    /**
     * uid  2019/5/10 11:42
     *
     * @var int
     */
    public $uid;


    /**
     * account_type  2019/5/10 11:42
     *
     * @var string
     */
    public $account_type;


    /**
     * RegisterEvent constructor. 2019/5/10 11:42
     *
     * @param string $type
     * @param int    $logID
     * @param int    $uid
     * @param string $account_type
     *
     * @return void
     */
    public function __construct(string $type, int $logID, int $uid, string $account_type)
    {
        $this->type = $type;
        $this->logID = $logID;
        $this->uid = $uid;
        $this->account_type = $account_type;
    }

}
