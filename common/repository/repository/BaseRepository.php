<?php
/**
 * @Author     : aq340214388@gmail.com
 * @CreateTime 2019/11/26 15:10:37
 */

namespace app\common\repository\repository;

use app\common\repository\contracts\RepositoryInterface;
use Doctrine\Common\Annotations\AnnotationReader;
use Doctrine\Common\Annotations\AnnotationRegistry;
use yii\db\ActiveRecord;

class BaseRepository implements RepositoryInterface
{

    /**
     * @var ActiveRecord
     */
    public $model;

    /**
     * @var array
     */
    private $orderBy;

    /**
     * @var bool
     */
    private $asArray = false;

    /**
     * @var array
     */
    private $paginate = [];

    /**
     * @var bool
     */
    private $isCache = false;

    /**
     * @var string
     */
    private $cachePrefix = '';

    public function __construct()
    {
        AnnotationRegistry::registerFile(__DIR__ . '/../annotations/Model.php');
    }

    /**
     * @return $this
     * @CreateTime 2019/11/26 15:47:40
     * @Author     : aq340214388@gmail.com
     * @throws \Doctrine\Common\Annotations\AnnotationException
     * @throws \ReflectionException
     */
    public static function getInstance(): self
    {
        $repoName = get_called_class();
        $repo     = new $repoName;

        $reflClass = new \ReflectionObject($repo);
        $reader    = new AnnotationReader();

        /**
         * @var $modelName Model
         */
        $modelName   = $reader->getPropertyAnnotation($reflClass->getProperty('model'), Model::class);
        $repo->model = $modelName->class;

        return $repo;
    }

    /**
     * @param        $id
     * @param string $fields
     * @param int    $isDel
     *
     * @return ActiveRecord | array | null
     * @CreateTime 2019/11/26 14:51:19
     * @Author     : aq340214388@gmail.com
     */
    public function findById($id, $fields = '*', $isDel = 0)
    {
        return $this->getQuery(['id' => $id], $fields, $isDel)
                    ->one();
    }

    /**
     * @param array  $ids
     * @param string $fields
     * @param int    $isDel
     *
     * @return ActiveRecord | array | null
     * @CreateTime 2019/11/26 14:51:24
     * @Author     : aq340214388@gmail.com
     */
    public function findByIds(array $ids, $fields = '*', $isDel = 0)
    {
        return $this->getQuery(['id' => $ids], $fields, $isDel)
                    ->all();
    }

    /**
     * @param array  $conditions
     * @param string $fields
     * @param int    $isDel
     *
     * @return ActiveRecord | array | null
     * @CreateTime 2019/11/26 14:51:30
     * @Author     : aq340214388@gmail.com
     */
    public function findFirst(array $conditions, $fields = '*', $isDel = 0)
    {
        return $this->getQuery($conditions, $fields, $isDel)
                    ->limit(1)
                    ->one();
    }

    /**
     * @param        $field
     * @param string $sort
     *
     * @return $this the query object itself
     * @CreateTime 2019/11/26 14:51:34
     * @Author     : aq340214388@gmail.com
     */
    public function orderBy($field, $sort = 'DESC'): self
    {
        $this->orderBy = [
            'field' => $field,
            'sort'  => $sort
        ];

        return $this;
    }

    /**
     * @param int $limit
     * @param int $offset
     *
     * @return $this
     * @CreateTime 2019/11/26 14:51:39
     * @Author     : aq340214388@gmail.com
     */
    public function paginate($offset = 0, $limit = 10): self
    {
        $this->paginate = [
            'offset' => $offset,
            'limit'  => $limit
        ];

        return $this;
    }

    /**
     * @param array $conditions
     *
     * @return int
     * @CreateTime 2019/11/26 14:51:43
     * @Author     : aq340214388@gmail.com
     */
    public function count(array $conditions): int
    {
        return (int)($this->getQuery($conditions)
                          ->count(1));
    }

    /**
     * @param array $attributes
     *
     * @return bool
     * @CreateTime 2019/11/26 14:51:48
     * @Author     : aq340214388@gmail.com
     * @throws \Throwable
     */
    public function create(array $attributes): bool
    {
        /**
         * @var $model ActiveRecord
         */
        $model = new $this->model();
        $model->setAttributes($attributes, false);
        return $model->save();
    }

    /**
     * @param       $id
     * @param array $data
     * @param int   $isDel
     *
     * @return bool
     * @CreateTime 2019/11/26 14:51:57
     * @Author     : aq340214388@gmail.com
     */
    public function updateOneById($id, array $data = [], $isDel = 0): bool
    {
        return $this->updateAllByConditions(['id' => $id], $data, $isDel);
    }

    /**
     * @param array $ids
     * @param array $data
     * @param int   $isDel
     *
     * @return bool
     * @CreateTime 2019/11/26 14:52:05
     * @Author     : aq340214388@gmail.com
     */
    public function updateAllByIds(array $ids, array $data = [], $isDel = 0): bool
    {
        return $this->updateAllByConditions(['id' => $ids, $data, $isDel]);
    }

    /**
     * @param array $conditions
     * @param array $data
     * @param int   $isDel
     *
     * @return bool
     * @CreateTime 2019/11/26 14:52:09
     * @Author     : aq340214388@gmail.com
     */
    public function updateAllByConditions(array $conditions = [], array $data = [], $isDel = 0): bool
    {
        $conditions['is_del'] = $isDel;
        $data['created_time'] = time();

        return $this->model::updateAll($data, $conditions);
    }

    /**
     * @param $id
     *
     * @return bool
     * @CreateTime 2019/11/26 14:52:13
     * @Author     : aq340214388@gmail.com
     */
    public function deleteOneById($id): bool
    {
        return $this->updateOneById($id, ['is_del' => 1]);
    }

    /**
     * @param array $ids
     *
     * @return bool
     * @CreateTime 2019/11/26 14:52:21
     * @Author     : aq340214388@gmail.com
     */
    public function deleteAllByIds(array $ids): bool
    {
        return $this->updateAllByIds($ids, ['is_del' => 1]);
    }

    /**
     * @param array $conditions
     *
     * @return bool
     * @CreateTime 2019/11/26 14:52:25
     * @Author     : aq340214388@gmail.com
     */
    public function deleteAllByConditions(array $conditions): bool
    {
        return $this->updateAllByConditions($conditions, ['is_del' => 1]);
    }

    /**
     * @param     $id
     * @param     $field
     * @param int $count
     *
     * @return int
     * @CreateTime 2019/11/26 14:52:29
     * @Author     : aq340214388@gmail.com
     */
    public function incOne($id, $field, $count = 1): int
    {
        return $this->model::updateAllCounters(
            [$field => $count],
            ['id' => $id]
        );
    }

    /**
     * @param     $id
     * @param     $field
     * @param int $count
     *
     * @return int
     * @CreateTime 2019/11/26 15:06:41
     * @Author     : aq340214388@gmail.com
     */
    public function decOne($id, $field, $count = 1): int
    {
        return $this->model::updateAllCounters(
            [$field => -$count],
            ['id' => $id]
        );
    }

    /**
     * @param array $conditions
     * @param       $field
     * @param int   $count
     *
     * @return int
     * @CreateTime 2019/11/26 15:06:45
     * @Author     : aq340214388@gmail.com
     */
    public function incAll(array $conditions, $field, $count = 1): int
    {
        return $this->model::updateAllCounters(
            [$field => $count],
            $conditions
        );
    }

    /**
     * @param array $conditions
     * @param       $field
     * @param int   $count
     *
     * @return int
     * @CreateTime 2019/11/26 15:06:48
     * @Author     : aq340214388@gmail.com
     */
    public function decAll(array $conditions, $field, $count = 1): int
    {
        return $this->model::updateAllCounters(
            [$field => -$count],
            $conditions
        );
    }

    /**
     * @param bool $isArray
     *
     * @return $this
     * @CreateTime 2019/11/26 15:06:52
     * @Author     : aq340214388@gmail.com
     */
    public function asArray($isArray = true): self
    {
        $this->asArray = $isArray;

        return $this;
    }

    /**
     * @param bool   $isCache
     * @param string $prefix
     *
     * @return $this
     * @CreateTime 2019/11/26 15:06:55
     * @Author     : aq340214388@gmail.com
     */
    public function cache($isCache = true, $prefix = ''): self
    {
        $this->isCache     = $isCache;
        $this->cachePrefix = $prefix;

        return $this;
    }

    /**
     * @param array  $conditions
     * @param string $fields
     * @param int    $isDel
     *
     * @return mixed|\yii\db\ActiveQuery
     * @CreateTime 2019/11/26 16:32:35
     * @Author     : aq340214388@gmail.com
     */
    public function getQuery(array $conditions, $fields = '*', $isDel = 0)
    {
        $model = $this->model::find()
                             ->select($fields)
                             ->where(['is_del' => $isDel])
                             ->andWhere($conditions);

        if (!empty($this->orderBy)) {
            $model->orderBy($this->orderBy['field'] . ' ' . $this->orderBy['sort']);
        }

        if (!empty($this->paginate)) {
            $model->offset($this->paginate['offset'])
                  ->limit($this->paginate['limit']);
        }

        if ($this->asArray === true) {
            $model->asArray();
        }

        return $model;
    }
}