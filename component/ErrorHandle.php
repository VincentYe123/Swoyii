<?php

namespace app\component;

use app\exception\BaseException;
use app\exception\RequestException;
use Yii;
use yii\base\ErrorException;
use yii\base\ErrorHandler;
use yii\helpers\VarDumper;
use yii\web\ForbiddenHttpException;
use yii\web\NotFoundHttpException;
use yii\web\UnauthorizedHttpException;

class ErrorHandle extends ErrorHandler
{
    public const EVENT_BEFORE_ERROR = 'beforeError';

    public function renderException($exception): void
    {
        $data['code'] = $exception->getCode();
        $data['msg'] = $exception->getMessage();

        if ($exception instanceof BaseException) {
            Yii::$app->response->setStatusCode($exception::getStatusCode($exception->getCode()));
        } elseif ($exception instanceof NotFoundHttpException) {
            Yii::$app->response->setStatusCode(404);
            $data['code'] = RequestException::URI_ERR;
            $data['msg'] = RequestException::getReason(RequestException::URI_ERR);
        } elseif ($exception instanceof ForbiddenHttpException) {
            $data['code'] = RequestException::PERMISSION_DENIED;
            $data['msg'] = RequestException::getReason(RequestException::PERMISSION_DENIED);
            Yii::$app->response->setStatusCode(403);
        } elseif ($exception instanceof UnauthorizedHttpException) {
            $data['code'] = RequestException::UNAUTHORIZED_TOKEN;
            $data['msg'] = RequestException::getReason(RequestException::UNAUTHORIZED_TOKEN);
            Yii::$app->response->setStatusCode(401);
        } else {
            Yii::$app->response->setStatusCode(500);
            $data['code'] = BaseException::SYSTEM_ERR;
            $data['msg'] = BaseException::getReason(BaseException::SYSTEM_ERR);
        }

        if (YII_DEBUG) {
            $data['debug'] = $this->getInfo();
        }

        if (YII_ENV === 'prod') {
            Yii::error(json_encode($this->getInfo()), 'ex');
        }

        //TODO http状态码 常量
        $this->trigger(self::EVENT_BEFORE_ERROR);

        Yii::$app->response->data = $data;
        Yii::$app->response->send();
    }

    public function getInfo(): array
    {
        return [
            'request_info' => Yii::$app->request->getInfo(),
            'error_code' => $this->exception->getCode(),
            'error_file' => $this->exception->getFile(),
            'error_line' => $this->exception->getLine(),
            'error_msg' => $this->exception->getMessage(),
            'error_trace' => explode(PHP_EOL, $this->exception->getTraceAsString()),
        ];
    }

    public function handleException($exception): void
    {
        $this->exception = $exception;
        try {
            $this->logException($exception);
            if ($this->discardExistingOutput) {
                $this->clearOutput();
            }
            $this->renderException($exception);
        } catch (\Exception $e) {
            $this->handleFallbackExceptionMessage($e, $exception);
        } catch (\Throwable $e) {
            $this->handleFallbackExceptionMessage($e, $exception);
        }

        $this->exception = null;
    }

    public function handleError($code, $message, $file, $line): bool
    {
        if (error_reporting() & $code) {
            if (!class_exists('yii\\base\\ErrorException', false)) {
                require_once Yii::getAlias('@yii/base/ErrorException.php');
            }
            $exception = new ErrorException($message, $code, $code, $file, $line);

            $trace = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS);
            array_shift($trace);

            foreach ($trace as $frame) {
                if ('__toString' === $frame['function']) {
                    $this->handleException($exception);
                }
            }
            throw $exception;
        }

        return false;
    }

    public function handleFatalError(): void
    {
        if (!class_exists('yii\\base\\ErrorException', false)) {
            require_once Yii::getAlias('@yii/base/ErrorException.php');
        }

        $error = error_get_last();
        if (ErrorException::isFatalError($error)) {
            $exception = new ErrorException($error['message'], $error['type'], $error['type'], $error['file'], $error['line']);
            $this->exception = $exception;

            $this->logException($exception);

            if ($this->discardExistingOutput) {
                $this->clearOutput();
            }
            $this->renderException($exception);

            Yii::getLogger()
               ->flush(true);
        }
    }

    public function handleFallbackExceptionMessage($exception, $previousException): void
    {
        $msg = "An Error occurred while handling another error:\n";
        $msg .= (string) $exception;
        $msg .= "\nPrevious exception:\n";
        $msg .= (string) $previousException;
        if (YII_DEBUG) {
            if (PHP_SAPI === 'cli') {
                echo $msg."\n";
            } else {
                echo '<pre>'.htmlspecialchars($msg, ENT_QUOTES, Yii::$app->charset).'</pre>';
            }
        } else {
            echo 'An internal server error occurred.';
        }
        $msg .= "\n\$_SERVER = ".VarDumper::export($_SERVER);
        error_log($msg);
    }
}
