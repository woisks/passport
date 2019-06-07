<?php
declare(strict_types=1);

namespace Woisks\Passport\Models\Entity;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Model
 *
 * @package Woisks\Passport\Models\Entity
 *
 * @Author  Maple Grove  <bolelin@126.com> 2019/5/10 11:48
 */
class Models extends Model
{
    /**
     * incrementing  2019/5/22 22:10
     *
     * @var  bool
     */
    public $incrementing = false;
    /**
     * dateFormat  2019/6/6 16:21
     *
     * @var  string
     */
    protected $dateFormat = 'U';

    /**
     * getIpAttribute 2019/5/28 9:44
     *
     * @param $value
     *
     * @return string
     */
    public function getIpAttribute($value)
    {
        return ip_string_decode($value);
    }

    /**
     * setIpAttribute 2019/5/28 9:44
     *
     * @param $value
     *
     * @return void
     */
    public function setIpAttribute($value)
    {
        $this->attributes['ip'] = ip_string_encode($value);
    }

    /**
     * getCreatedAtAttribute 2019/6/8 1:12
     *
     * @param $value
     *
     * @return false|string
     */
    public function getCreatedAtAttribute($value)
    {
        return date('Y-m-d H:i:s',(int)$value);
    }

    /**
     * getUpdatedAtAttribute 2019/6/8 1:12
     *
     * @param $value
     *
     * @return false|string
     */
    public function getUpdatedAtAttribute($value)
    {
        return date('Y-m-d H:i:s',(int)$value);
    }

}
