<?php
declare(strict_types=1);

namespace Woisks\Passport\Models\Repository;


use Woisks\Passport\Models\Entity\TypeCount;


/**
 * Class TypeCountRepository
 *
 * @package Woisks\Passport\Models\Repository
 *
 * @Author  Maple Grove  <bolelin@126.com> 2019/5/21 19:02
 */
class TypeCountRepository
{


    /**
     * model  2019/5/21 19:02
     *
     * @var static \Woisks\Passport\Models\Entity\TypeCount
     */
    private static $model;


    /**
     * TypeCountRepository constructor. 2019/5/14 10:28
     *
     * @param \Woisks\Passport\Models\Entity\TypeCount $accountType
     *
     * @return void
     */
    public function __construct(TypeCount $accountType)
    {
        self::$model = $accountType;
    }


    /**
     * typeIncrement 2019/5/21 20:15
     *
     * @param string $account_type
     *
     * @return int|bool
     */
    public function typeIncrement(string $account_type): int
    {
        return self::$model->where('alias', $account_type)->increment('count');
    }


    /**
     * typeDecrement 2019/5/21 20:15
     *
     * @param string $account_type
     *
     * @return int|bool
     */
    public function typeDecrement(string $account_type): int
    {
        return self::$model->where('alias', $account_type)->decrement('count');
    }
}
