<?php namespace App\Http\Requests;

class APIRequest extends Request
{
    public function rules()
    {
        $minLimit = config('api.items_per_page.min');
        $maxLimit = config('api.items_per_page.max');

        return [
            'limit' => "integer|min:{$minLimit}|max:{$maxLimit}",
            'page'  => 'integer|min:1',
            'query' => 'string|min:3|max:100'
        ];
    }
}