<?php
declare(strict_types=1);

namespace Woisks\Passport\Models\Entity;

/**
 * Class AccountEntity
 *
 * @property mixed limit_login_time
 * @property mixed login_error_count
 * @property mixed password
 * @property mixed status
 * @property mixed last_login_account_type
 * @property mixed sum_login_count
 * @package Woisks\PassportEntity\Models\Entity
 *
 * @Author  Maple Grove  <bolelin@126.com> 2019/5/10 11:46
 */
class AccountEntity extends Models
{


    /**
     * table  2019/5/10 11:46
     *
     * @var string
     */
    protected $table = 'passport_account';


    /**
     * fillable  2019/5/10 11:46
     *
     * @var array
     */
    protected $fillable = [
        'id',
        'limit_login_time',
        'sum_login_count',
        'login_error_count',
        'last_login_account_type',
        'created_at',
        'updated_at',
        'password',
        'status'
    ];
    /**
     * hidden  2019/6/7 20:41
     *
     * @var  array
     */
    protected $hidden =[
        'password'
    ];
}
