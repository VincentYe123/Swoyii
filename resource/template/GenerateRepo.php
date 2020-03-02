<?php
/**
 * Created by PhpStorm.
 *
 * @Author     : xingxiaohe@styd.cn
 * @CreateTime 2020/3/2 13:44:40
 */

namespace app\resource\template;

use Yii;
use yii\gii\CodeFile;

/**
 * Class Generator.
 *
 * @CreateTime 2020/3/2 11:58:15
 * @Author     : xingxiaohe@styd.cn
 */
class GenerateRepo extends \yii\gii\Generator
{
    public $ns = 'app\repositories';
    public $modelName;

    /**
     * {@inheritdoc}
     */
    public function getName(): string
    {
        return 'Repository Generator';
    }

    /**
     * {@inheritdoc}
     */
    public function getDescription(): string
    {
        return 'This generator generates an Repository class for Model class.';
    }

    /**
     * {@inheritdoc}
     */
    public function requiredTemplates(): array
    {
        return ['repository.php'];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels(): array
    {
        return array_merge(parent::attributeLabels(), [
            'ns' => 'Namespace',
            'modelName' => 'Model Class Name',
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function stickyAttributes(): array
    {
        return array_merge(parent::stickyAttributes(), ['ns', 'modelName']);
    }

    /**
     * {@inheritdoc}
     */
    public function generate(): array
    {
        $this->modelName = ucfirst($this->modelName);
        $files = [];
        $params = [
            'className' => $this->modelName.'Repository',
            'modelName' => '\app\models\\'.$this->modelName,
        ];
        $files[] = new CodeFile(
            Yii::getAlias('@'.str_replace('\\', '/', $this->ns)).'/'.$this->modelName.'Repository'.'.php',
            $this->render('repository.php', $params)
        );

        return $files;
    }
}
