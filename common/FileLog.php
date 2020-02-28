<?php

namespace app\common;

use yii\base\Exception;
use yii\helpers\FileHelper;

//swoole_asyn_writefile use aio base mode, default 2 thread to write file.
//it flock file automatically when write file.
class FileLog extends \yii\log\FileTarget
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
        if ($this->enableRotation && @filesize($this->logFile) > $this->maxFileSize * 1024) {
            //don't uncomment this line, it will cause rotate bug when concurrent write log
            //$this->rotateFiles();
            swoole_async_writefile($this->logFile, $text, null, FILE_APPEND);
        } else {
            swoole_async_writefile($this->logFile, $text, null, FILE_APPEND);
        }
        if (null !== $this->fileMode) {
            @chmod($this->logFile, $this->fileMode);
        }
    }
}
