<?php namespace App\Http\Requests;

class APIRequest extends Request
{
    public function rules()
    {
        $minLimit = config('api.items_per_page.min');
        $maxLimit = config('api.items_per_page.max');

        $filters = [
            'limit' => "integer|min:{$minLimit}|max:{$maxLimit}",
            'page'  => 'integer|min:1',
            'query' => 'string|min:3|max:100',
        ];

        $routeUri = $this->route()->getUri();

        $modelName = ucfirst(substr($routeUri, strrpos($routeUri, '/') + 1));
        $modelClass = sprintf('App\Model\%s', $modelName);

        if (class_exists($modelClass)) {
            $instance = new $modelClass;
            $instance = call_user_func_array([$instance, 'newInstance'], [[], false]);

            $dates = $instance->getDates();

            collect($dates)->flatMap(function ($date_field) {
                return collect(['%s_after', '%s', '%s_before'])->map(function ($query) use ($date_field) {
                    return [sprintf($query, $date_field) => 'date'];
                })->toArray();
            })->each(function ($date_filter) use (&$filters) {
                $key = array_keys($date_filter)[0];

                $filters[$key] = $date_filter[$key];
            });
        }

        return $filters;
    }
}