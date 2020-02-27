<?php

namespace app\components;

use yii\base\NotSupportedException;
use yii\db\Exception;

class DbKeepAlive extends \yii\db\Command
{
    /**
     * @param null $forRead
     * @param int  $retry
     *
     * @throws Exception
     * @throws NotSupportedException
     * @CreateTime 2019/11/19 14:43:37
     * @Author     : yechangqing@styd.cn
     */
    public function prepare($forRead = null, $retry = 3): void
    {
        if ($this->pdoStatement) {
            $this->bindPendingParams();

            return;
        }

        $sql = $this->getSql();

        if ($this->db->getTransaction()) {
            // master is in a transaction. use the same connection.
            $forRead = false;
        }
        if ($forRead || (null === $forRead && $this->db->getSchema()->isReadQuery($sql))) {
            $pdo = $this->db->getSlavePdo();
        } else {
            $pdo = $this->db->getMasterPdo();
        }

        try {
            $this->pdoStatement = $pdo->prepare($sql);
            $this->bindPendingParams();
        } catch (\Exception $e) {
            $date = date('Y-m-d H:i:s');
            $message = $e->getMessage()."; Failed to prepare SQL: $sql";
            echo "[{$date} DB keep alive] ".$message.PHP_EOL;
            if ($this->checkDb($e->getMessage()) && $retry) {
                echo "[{$date} DB keep alive] Reconnect to mysql".PHP_EOL;
                $this->db->close();
                $this->prepare($forRead, --$retry);
            } else {
                echo "[{$date} DB keep alive] Reconnect to mysql failed".PHP_EOL;
                $errorInfo = $e instanceof \PDOException ? $e->errorInfo : null;
                throw new Exception($message, $errorInfo, (int) $e->getCode(), $e);
            }
        }
    }

    /**
     * 检查db连接是否丢失.
     *
     * @param $errMsg
     *
     * @return bool
     * @CreateTime 2019/11/24 20:18:40
     */
    public function checkDb($errMsg): bool
    {
        if (false !== strpos($errMsg, 'server has gone away')) {
            return true;
        }

        if (false !== strpos($errMsg, 'no connection to the server')) {
            return true;
        }

        if (false !== strpos($errMsg, 'Lost connection')) {
            return true;
        }

        if (false !== strpos($errMsg, 'is dead or not enabled')) {
            return true;
        }

        if (false !== strpos($errMsg, 'Error while sending')) {
            return true;
        }

        if (false !== strpos($errMsg, 'decryption failed or bad record mac')) {
            return true;
        }

        if (false !== strpos($errMsg, 'SSL connection has been closed unexpectedly')) {
            return true;
        }

        return false;
    }
}
