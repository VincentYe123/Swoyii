<?php

namespace app\component;

use Yii;
use yii\db\Exception;

class Response extends \yii\web\Response
{
    /**
     * @var \Swoole\Http\Response
     */
    private $_swResponse;

    public function setSwResponse(\Swoole\Http\Response $response): void
    {
        $this->_swResponse = $response;
    }

    public function getSwResponse(): \Swoole\Http\Response
    {
        return $this->_swResponse;
    }

    public function checkServerException(): void
    {
        if (500 === Yii::$app->response->getStatusCode()) {
            $exceptionInfo = Yii::$app->errorHandler->exception;
            if ($exceptionInfo instanceof Exception || $exceptionInfo instanceof \PDOException) {
                Yii::$app->sw->stop();
            }
        }
    }

    public function sendContent(): void
    {
        if (null === $this->stream) {
            $this->_swResponse->end($this->content);
            $this->checkServerException();

            return;
        }
        $chunkSize = 2 * 1024 * 1024; // 2MB per chunk swoole limit
        if (is_array($this->stream)) {
            [$handle, $begin, $end] = $this->stream;
            fseek($handle, $begin);
            while (!feof($handle) && ($pos = ftell($handle)) <= $end) {
                if ($pos + $chunkSize > $end) {
                    $chunkSize = $end - $pos + 1;
                }
                $this->_swResponse->write(fread($handle, $chunkSize));
                //flush(); // Free up memory. Otherwise large files will trigger php's memory limit.
            }
            fclose($handle);
        } else {
            while (!feof($this->stream)) {
                $this->_swResponse->write(fread($this->stream, $chunkSize));
                //flush();
            }
            fclose($this->stream);
        }
        $this->_swResponse->end();
        $this->checkServerException();
    }

    public function sendHeaders(): void
    {
        $headers = $this->getHeaders();
        if ($headers->count > 0) {
            foreach ($headers as $name => $values) {
                foreach ($values as $value) {
                    $this->_swResponse->header($name, $value);
                }
            }
        }
        $this->_swResponse->status($this->getStatusCode());
    }

    public function clear(): void
    {
        parent::clear();
        $_POST = [];
    }
}
