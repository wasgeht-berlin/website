<?php

namespace App\Http\Requests;

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
            /* @var $instance \Illuminate\Database\Eloquent\Model */
            $instance = new $modelClass;
            $instance = call_user_func_array([$instance, 'newInstance'], [[], false]);

            $dates = $instance->getDates();

            collect($dates)->flatMap(function ($date_field) {
                return collect(['%s_after', '%s', '%s_before'])->map(function ($query) use ($date_field) {
                    // TODO: date filter fields really should be validated as date-ish, but the builtin filter kind of does not do that
                    return [sprintf($query, $date_field) => 'string'];
                })->toArray();
            })->each(function ($date_filter) use (&$filters) {
                $key = array_keys($date_filter)[0];

                $filters[$key] = $date_filter[$key];
            });

            $orderBy = $instance->getVisible();

            if (count($orderBy) > 1) {
                $orderBy = implode(',', $orderBy);

                $filters['order_by'] = "string|in:{$orderBy}";
                $filters['sort'] = "string|in:asc,desc";
            }
        }

        return $filters;
    }
}