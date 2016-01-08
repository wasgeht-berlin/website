<?php namespace App\Model\Transformers;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use League\Fractal\TransformerAbstract;

abstract class BaseTransformer extends TransformerAbstract
{
    private $classFormatMap = [
        "CarbonCarbon" => 'formatDate',
    ];

    protected function format($value)
    {
        $type = gettype($value);

        if ($type === 'NULL') return null;


        if ($type === 'object') {
            $class = stripslashes(get_class($value));

            if (isset($this->classFormatMap[$class]))
                return $this->{$this->classFormatMap[$class]}($value);

            return $class;
        }

        if ($type == 'string')
        {
            if (is_numeric($value)) {
                if (preg_match('/\d+\.\d+/', $value)) return $this->formatFloat($value);
                if (preg_match('/\d+/', $value)) return $this->formatInteger($value);
            } else
            {
                return $this->formatString($value);
            }
        }

        $typeFormatMethod = sprintf('format%s', ucfirst($type));
        if (method_exists($this, $typeFormatMethod)) return $this->{$typeFormatMethod}($value);

        return $type;
    }

    protected function formatDate(Carbon $date)
    {
        return $date->format(Carbon::ISO8601);
    }

    protected function formatString($string)
    {
        return $string;
    }

    protected function formatInteger($integer)
    {
        return (int)$integer;
    }

    protected function formatFloat($float)
    {
        return (float)$float;
    }

    protected function listRelation(Collection $collection, array $fields)
    {
        return $collection->map(function ($item) use ($fields) {
            if (count($fields) == 1) {
                return $item->{$fields[0]};
            } else {
                $items = [];

                foreach ($fields as $field)
                    array_push($items, $item->{$field});

                return $items;
            }
        });
    }
}
