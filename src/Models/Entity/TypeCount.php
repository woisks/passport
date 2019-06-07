<?php
declare(strict_types=1);

namespace Woisks\Passport\Models\Entity;


/**
 * Class TypeCount
 *
 * @package Woisks\Passport\Models\Entity
 *
 * @Author  Maple Grove  <bolelin@126.com> 2019/5/10 11:47
 */
class TypeCount extends Models
{


    /**
     * table  2019/5/10 11:47
     *
     * @var string
     */
    protected $table = 'passport_type_count';


    /**
     * fillable  2019/5/10 11:47
     *
     * @var array
     */
    protected $fillable = [
        'id',
        'alias',
        'name',
        'readme',
        'count',
        'created_at',
        'updated_at',
        'status'
    ];
}
