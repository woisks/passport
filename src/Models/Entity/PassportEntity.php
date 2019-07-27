<?php
declare(strict_types=1);

namespace Woisks\Passport\Models\Entity;


/**
 * Class PassportEntity
 *
 * @property mixed account_uid
 * @property mixed account_type
 * @property mixed login_count
 * @package Woisks\PassportEntity\Models\Entity
 *
 * @Author  Maple Grove  <bolelin@126.com> 2019/5/10 11:48
 */
class PassportEntity extends Models
{


    /**
     * table  2019/5/10 11:48
     *
     * @var string
     */
    protected $table = 'passport';


    /**
     * fillable  2019/5/10 11:48
     *
     * @var array
     */
    protected $fillable   = [
        'id',
        'account_uid',
        'account_type',
        'username',
        'created_at',
        'updated_at',
        'login_count',
        'password'
    ];
    /**
     * hidden  2019/6/7 20:41
     *
     * @var  array
     */
    protected $hidden =[
        'password',
        'id',
        'account_uid'
    ];

}
