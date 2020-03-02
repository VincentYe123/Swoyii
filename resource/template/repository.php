<?php
/**
 * This is the template for generating the model class of a specified table.
 */

/* @var $generator yii\gii\generators\model\Generator */
/* @var $className string class name */
/* @var $modelName string model name */

echo "<?php\n";
?>

namespace app\repositories;

class <?= $className; ?> extends <?= '\\'.ltrim($generator->baseClass, '\\')."\n"; ?>
{
    /**
     * @Model(class="<?= $modelName; ?>")
     */
    public $model;
}