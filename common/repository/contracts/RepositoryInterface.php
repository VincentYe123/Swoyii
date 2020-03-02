<?php
/**
 * @Author     : aq340214388@gmail.com
 * @CreateTime 2019/11/26 14:50:24
 */

namespace app\common\repository\contracts;

use yii\db\ActiveRecord;

interface RepositoryInterface
{
    /**
     * @return $this
     * @CreateTime 2019/11/26 16:19:45
     * @Author     : aq340214388@gmail.com
     */
    public static function getInstance();

    /**
     * @param        $id
     * @param string $fields
     * @param int    $isDel
     *
     * @return ActiveRecord | array | null
     * @CreateTime 2019/11/26 14:51:19
     * @Author     : aq340214388@gmail.com
     */
    public function findById($id, $fields = '*', $isDel = 0);

    /**
     * @param array  $ids
     * @param string $fields
     * @param int    $isDel
     *
     * @return ActiveRecord | array | null
     * @CreateTime 2019/11/26 14:51:24
     * @Author     : aq340214388@gmail.com
     */
    public function findByIds(array $ids, $fields = '*', $isDel = 0);

    /**
     * @param array  $conditions
     * @param string $fields
     * @param int    $isDel
     *
     * @return ActiveRecord | array | null
     * @CreateTime 2019/11/26 14:51:30
     * @Author     : aq340214388@gmail.com
     */
    public function findFirst(array $conditions, $fields = '*', $isDel = 0);

    /**
     * @param        $field
     * @param string $sort
     *
     * @return $this the query object itself
     * @CreateTime 2019/11/26 14:51:34
     * @Author     : aq340214388@gmail.com
     */
    public function orderBy($field, $sort = 'DESC');

    /**
     * @param int $limit
     * @param int $offset
     *
     * @return $this the query object itself
     * @CreateTime 2019/11/26 14:51:39
     * @Author     : aq340214388@gmail.com
     */
    public function paginate($offset = 0, $limit = 10);

    /**
     * @param array $conditions
     *
     * @return int
     * @CreateTime 2019/11/26 14:51:43
     * @Author     : aq340214388@gmail.com
     */
    public function count(array $conditions): int;

    /**
     * @param array $attributes
     *
     * @return bool
     * @CreateTime 2019/11/26 14:51:48
     * @Author     : aq340214388@gmail.com
     */
    public function create(array $attributes): bool;

    /**
     * @param       $id
     * @param array $data
     * @param int   $isDel
     *
     * @return bool
     * @CreateTime 2019/11/26 14:51:57
     * @Author     : aq340214388@gmail.com
     */
    public function updateOneById($id, array $data = [], $isDel = 0): bool;

    /**
     * @param array $ids
     * @param array $data
     * @param int   $isDel
     *
     * @return bool
     * @CreateTime 2019/11/26 14:52:05
     * @Author     : aq340214388@gmail.com
     */
    public function updateAllByIds(array $ids, array $data = [], $isDel = 0): bool;

    /**
     * @param array $conditions
     * @param array $data
     * @param int   $isDel
     *
     * @return bool
     * @CreateTime 2019/11/26 14:52:09
     * @Author     : aq340214388@gmail.com
     */
    public function updateAllByConditions(array $conditions = [], array $data = [], $isDel = 0): bool;

    /**
     * @param $id
     *
     * @return bool
     * @CreateTime 2019/11/26 14:52:13
     * @Author     : aq340214388@gmail.com
     */
    public function deleteOneById($id): bool;

    /**
     * @param array $ids
     *
     * @return bool
     * @CreateTime 2019/11/26 14:52:21
     * @Author     : aq340214388@gmail.com
     */
    public function deleteAllByIds(array $ids): bool;

    /**
     * @param array $conditions
     *
     * @return bool
     * @CreateTime 2019/11/26 14:52:25
     * @Author     : aq340214388@gmail.com
     */
    public function deleteAllByConditions(array $conditions): bool;

    /**
     * @param     $id
     * @param     $field
     * @param int $count
     *
     * @return int
     * @CreateTime 2019/11/26 14:52:29
     * @Author     : aq340214388@gmail.com
     */
    public function incOne($id, $field, $count = 1): int;

    /**
     * @param     $id
     * @param     $field
     * @param int $count
     *
     * @return int
     * @CreateTime 2019/11/26 15:06:41
     * @Author     : aq340214388@gmail.com
     */
    public function decOne($id, $field, $count = 1): int;

    /**
     * @param array $conditions
     * @param       $field
     * @param int   $count
     *
     * @return int
     * @CreateTime 2019/11/26 15:06:45
     * @Author     : aq340214388@gmail.com
     */
    public function incAll(array $conditions, $field, $count = 1): int;

    /**
     * @param array $conditions
     * @param       $field
     * @param int   $count
     *
     * @return int
     * @CreateTime 2019/11/26 15:06:48
     * @Author     : aq340214388@gmail.com
     */
    public function decAll(array $conditions, $field, $count = 1): int;

    /**
     * @param bool $isArray
     *
     * @return $this
     * @CreateTime 2019/11/26 15:06:52
     * @Author     : aq340214388@gmail.com
     */
    public function asArray($isArray = true);

    /**
     * @param bool   $isCache
     * @param string $prefix
     *
     * @return $this
     * @CreateTime 2019/11/26 15:06:55
     * @Author     : aq340214388@gmail.com
     */
    public function cache($isCache = true, $prefix = '');

    /**
     * @param array  $conditions
     * @param string $fields
     * @param int    $isDel
     *
     * @return mixed
     * @CreateTime 2019/11/26 16:32:15
     * @Author     : aq340214388@gmail.com
     */
    public function getQuery(array $conditions, $fields = '*', $isDel = 0);
}
