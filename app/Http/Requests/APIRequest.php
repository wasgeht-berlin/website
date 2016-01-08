<?php namespace App\Http\Requests;

class APIRequest extends Request
{
    public function rules()
    {
        return [
            'limit' => 'integer|min:1|max:50'
        ];
    }
}