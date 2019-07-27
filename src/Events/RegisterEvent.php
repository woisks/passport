<?php
declare(strict_types=1);

namespace Woisks\Passport\Events;


/**
 * Class RegisterEvent
 *
 * @package Woisks\PassportEntity\Events
 *
 * @Author  Maple Grove  <bolelin@126.com> 2019/5/10 11:42
 */
class RegisterEvent
{
    /**
     * type.  2019/7/26 23:08.
     *
     * @var
     */
    public $type;
    /**
     * logID.  2019/7/26 23:08.
     *
     * @var
     */
    public $logID;
    /**
     * account_uid.  2019/7/26 23:08.
     *
     * @var
     */
    public $account_uid;
    /**
     * account_type.  2019/7/26 23:08.
     *
     * @var
     */
    public $account_type;
    /**
     * ip.  2019/7/26 23:08.
     *
     * @var
     */
    public $ip;
    /**
     * system.  2019/7/26 23:08.
     *
     * @var
     */
    public $system;
    /**
     * client.  2019/7/26 23:08.
     *
     * @var
     */
    public $client;
    /**
     * brand_model.  2019/7/26 23:08.
     *
     * @var
     */
    public $brand_model;
    /**
     * device_type.  2019/7/26 23:08.
     *
     * @var
     */
    public $device_type;


    /**
     * RegisterEvent constructor. 2019/7/26 23:08.
     *
     * @param $type
     * @param $logID
     * @param $account_uid
     * @param $account_type
     * @param $ip
     * @param $system
     * @param $client
     * @param $brand_model
     * @param $device_type
     *
     * @return void
     */
    public function __construct($type, $logID, $account_uid, $account_type, $ip, $system, $client, $brand_model, $device_type)
    {
        $this->type = $type;
        $this->logID = $logID;
        $this->account_uid = $account_uid;
        $this->account_type = $account_type;
        $this->ip = $ip;
        $this->system = $system;
        $this->client = $client;
        $this->brand_model = $brand_model;
        $this->device_type = $device_type;
    }

}
