<?php

namespace app\common;

use yii\log\Logger;

//把日志打印到stdout
class StdoutTarget extends \yii\log\Target
{
    public function export()
    {
        $file = @fopen('php://stdout', 'wb');
        if (false === $file) {
            return;
        }

        /** @var Logger::me $message */
        foreach ($this->messages as $message) {
            if (isset($message[2]) && !in_array($message[2], $this->categories, true)) {
                continue;
            }
            fwrite($file, $this->formatMessage($message).PHP_EOL);
        }
        fclose($file);
    }
}
