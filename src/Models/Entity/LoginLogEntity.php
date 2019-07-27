<?php
declare(strict_types=1);

namespace Woisks\Passport\Models\Entity;

/**
 * Class LoginLogEntity
 *
 * @package Woisks\PassportEntity\Models\Entity
 *
 * @Author  Maple Grove  <bolelin@126.com> 2019/5/10 11:47
 */
class LoginLogEntity extends Models
{


    /**
     * table  2019/5/10 11:47
     *
     * @var string
     */
    protected $table = 'passport_log_login';


    /**
     * fillable  2019/5/10 11:47
     *
     * @var array
     */
    protected $fillable = [
        'id',
        'account_uid',
        'account_type',
        'ip',
        'system',
        'client',
        'brand_model',
        'device_type'
    ];
    protected $hidden=[
        'id',
        'account_uid'
    ];

    /**
     *
     */
    public const UPDATED_AT = null;
}
