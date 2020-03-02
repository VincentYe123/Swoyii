<?php
/**
 * This is the template for generating the model class of a specified table.
 */

/* @var $this yii\web\View */
/* @var $generator yii\gii\generators\model\Generator */
/* @var $tableName string full table name */
/* @var $className string class name */
/* @var $queryClassName string query class name */
/* @var $tableSchema yii\db\TableSchema */
/* @var $properties array list of properties (property => [type, name. comment]) */
/* @var $labels string[] list of attribute labels (name => label) */
/* @var $rules string[] list of validation rules */
/* @var $relations array list of relations (name => relation declaration) */

echo "<?php\n";
?>

namespace <?= $generator->ns; ?>;

use Yii;
use yii\base\InvalidConfigException;

/**
 * This is the model class for table <?= $generator->generateTableName($tableName); ?>.
 *
<?php foreach ($properties as $property => $data): ?>
 * @property <?= "{$data['type']} \${$property}".($data['comment'] ? ' '.strtr($data['comment'], ["\n" => ' ']) : '')."\n"; ?>
<?php endforeach; ?>
<?php if (!empty($relations)): ?>
 *
<?php foreach ($relations as $name => $relation): ?>
 * @property <?= $relation[1].($relation[2] ? '[]' : '').' $'.lcfirst($name)."\n"; ?>
<?php endforeach; ?>
<?php endif; ?>
 */
class <?= $className; ?> extends <?= '\\'.ltrim($generator->baseClass, '\\')."\n"; ?>
{
    public static function tableName(): string
    {
        return '<?= $generator->generateTableName($tableName); ?>';
    }

    /**
    * @return object the database connection used by this AR class.
    * @throws InvalidConfigException
    */
    public static function getDb()
    {
        return Yii::$app->get('<?= $generator->db; ?>');
    }
}
