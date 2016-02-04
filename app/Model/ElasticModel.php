<?php namespace App\Model;

use Cocur\Slugify\Slugify;
use Elasticsearch\ClientBuilder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

abstract class ElasticModel extends Model
{
    public static function boot()
    {
        $elasticClient = ClientBuilder::create()->build();

        $updateIndex = function (ElasticModel $modelInstance) use ($elasticClient) {
            $params = [
                'index' => config('search.index'),
                'type'  => $modelInstance->getElasticType(),
                'id'    => $modelInstance->getKey(),
                'body'  => $modelInstance->toElasticArray(),
            ];

            $elasticClient->index($params);
            return true;
        };

        $deleteIndex = function (ElasticModel $modelInstance) use ($elasticClient) {
            $reflectionClass = new \ReflectionClass($modelInstance);
            $traits = $reflectionClass->getTraits();
            if (in_array(SoftDeletes::class, array_keys($traits)) && $modelInstance->trashed()) {
                return true;
            }

            $params = [
                'index' => config('search.index'),
                'type' => $modelInstance->getElasticType(),
                'id' => $modelInstance->getKey(),
            ];

            $elasticClient->delete($params);
            return true;
        };

        static::created($updateIndex);
        static::updated($updateIndex);
        static::deleted($deleteIndex);
    }

    protected function getElasticType()
    {
        $slugify = Slugify::create();

        return $slugify->slugify(get_called_class());
    }

    abstract protected function toElasticArray();

    /**
     * Perform a full text search query on all fields.
     *
     * Returns an empty Collection on failure and a normal Eloquent result Collection
     * on success. The success results are ordered by hit score and the concrete score
     * is added to the models as a transient property.
     *
     * // TODO: result pagination
     *
     * @param string $query
     * @param array $filters
     * @param boolean $paginate
     * @return \Illuminate\Support\Collection|\Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public static function elasticFind($query = '', array $filters = [], $paginate = false)
    {
        $params = [
            "index" => config('search.index'),
            "body" => [
                "query" => [
                    "query_string" => [
                        "query" => $query,
                        "default_field" => "_all",
                    ],
                ],
            ],
        ];

        $elasticClient = ClientBuilder::create()->build();

        $result = $elasticClient->search($params);

        if ($result['hits']['total'] == 0) return collect();

        return collect($result['hits']['hits'])->map(function ($hit) {
            $entity = static::find($hit['_id']);

            if ($entity)
            {
                $entity->score = $hit['_score'];

                return $entity;
            }

            return null;
        })->filter(function ($entity) {
            return !is_null($entity);
        });
    }
}