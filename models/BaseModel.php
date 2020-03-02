<?php

namespace app\models;

use yii\db\ActiveRecord;

/**
 * @property int updated_time
 * @property int created_time
 */
class BaseModel extends ActiveRecord
{
    public function beforeSave($insert): bool
    {
        if (parent::beforeSave($insert)) {
            $this->updated_time = time();

            if ($insert) {
                $this->created_time = time();
            }

            return true;
        }

        return false;
    }
}
