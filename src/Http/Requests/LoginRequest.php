<?php
declare(strict_types=1);

namespace Woisks\Passport\Http\Requests;

/**
 * Class LoginRequest
 *
 * @package Woisks\PassportEntity\Http\Requests
 *
 * @Author  Maple Grove  <bolelin@126.com> 2019/5/10 11:45
 */
class LoginRequest extends Requests
{

    /**
     * rules 2019/5/10 11:45
     *
     *
     * @return array|mixed
     */
    public function rules()
    {
        return [
            'username' => 'required|string|min:5|max:40',
            'password' => 'required|string|min:6|max:18'
        ];
    }


}
