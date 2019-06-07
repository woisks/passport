<?php
declare(strict_types=1);


namespace Woisks\Passport\Http\Requests;


use Illuminate\Foundation\Http\FormRequest;


/**
 * Class Requests
 *
 * @package Woisks\PassportRepository\Http\Requests
 *
 * @Author  Maple Grove  <bolelin@126.com> 2019/5/10 11:45
 */
abstract class Requests extends FormRequest
{


    /**
     * authorize 2019/5/10 11:45
     *
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }


    /**
     * rules 2019/5/10 11:45
     *
     *
     * @return mixed
     */
    abstract public function rules();
}
