<?php
declare(strict_types=1);

namespace Woisks\Passport\Http\Requests;


/**
 * Class UpdatePasswordRequest
 *
 * @package Woisks\PassportRepository\Http\Requests
 *
 * @Author  Maple Grove  <bolelin@126.com> 2019/5/10 11:46
 */
class UpdatePasswordRequest extends Requests
{

    /**
     * rules 2019/5/10 11:46
     *
     *
     * @return array|mixed
     */
    public function rules()
    {
        return [
            'old_password' => 'required|string|min:6|max:18',
            'password'     => 'required|string|min:6|max:18|confirmed',
        ];
    }
}
