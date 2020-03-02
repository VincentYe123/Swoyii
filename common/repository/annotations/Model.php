<?php
/**
 * @Author     : aq340214388@gmail.com
 * @CreateTime 2019/11/26 17:34:45
 */

namespace app\common\repository\annotations;

use Doctrine\Common\Annotations\Annotation\Required;
use Doctrine\Common\Annotations\Annotation\Target;
use yii\db\ActiveRecord;

/**
 * @Annotation
 * @Target({"PROPERTY"})
 */
final class Model
{

    /**
     * @Required()
     */
    public $class;

    public function __construct(array $values)
    {
        if ((new $values['class']) instanceof ActiveRecord) {
            $this->class = $values['class'];
            return;
        }

        throw new \InvalidArgumentException(sprintf('Invalid Class "%s". Class must be ActiveRecord', $values['class']));
    }
}