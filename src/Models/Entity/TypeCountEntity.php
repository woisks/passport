<?php
declare(strict_types=1);

namespace Woisks\Passport\Models\Entity;


/**
 * Class TypeCountEntity
 *
 * @package Woisks\PassportEntity\Models\Entity
 *
 * @Author  Maple Grove  <bolelin@126.com> 2019/5/10 11:47
 */
class TypeCountEntity extends Models
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
        'type',
        'name',
        'readme',
        'count',
        'created_at',
        'updated_at',
        'status'
    ];
}
