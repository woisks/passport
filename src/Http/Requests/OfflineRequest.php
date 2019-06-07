<?php
declare(strict_types=1);


namespace Woisks\Passport\Http\Requests;


class OfflineRequest extends Requests
{

    public function rules()
    {
        return [
            'mac' => 'required|numeric|digits:10'
        ];
    }
}
