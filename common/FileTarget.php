<?php

namespace app\common;

use yii\base\Exception;
use yii\helpers\FileHelper;

//it flock file automatically when write file.
class FileTarget extends \yii\log\FileTarget
{
    public $logDir;

    public $logFileName = 'app.log';

    /**
     * @throws Exception
     * @CreateTime 2018-12-17 10:37:24
     */
    public function export(): void
    {
        $this->logFile = call_user_func($this->logDir, $this->logFileName);

        $logPath = dirname($this->logFile);
        FileHelper::createDirectory($logPath, $this->dirMode, true);

        $text = implode("\n", array_map([
                $this,
                'formatMessage',
            ], $this->messages))."\n";
        if ($this->enableRotation) {
            clearstatcache();
        }

        go(function () use ($text) {
            file_put_contents($this->logFile, $text, FILE_APPEND);
        });

        if (null !== $this->fileMode) {
            @chmod($this->logFile, $this->fileMode);
        }
    }
}
