<?php
declare(strict_types=1);

namespace Woisks\Passport\Models\Entity;

/**
 * Class RegisterLogEntity
 *
 * @package Woisks\PassportEntity\Models\Entity
 *
 * @Author  Maple Grove  <bolelin@126.com> 2019/5/10 11:48
 */
class RegisterLogEntity extends Models
{

    /**
     * table  2019/5/10 11:48
     *
     * @var string
     */
    protected $table = 'passport_log_register';


    /**
     * fillable  2019/5/10 11:48
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
    /**
     *
     */
    public const UPDATED_AT = null;
}
