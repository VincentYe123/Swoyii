<?php

namespace app\middleware;

use app\component\Response;
use app\exception\RequestException;
use Yii;

class ResponseHashFilter extends ResponseInterface
{
    /**
     * @param $response Response
     *
     * @return mixed
     *
     * @throws RequestException
     */
    public function beforeSend($response)
    {
        $response->data = $this->encodeColumn($response->data);

        return $response;
    }

    /**
     * @param $data
     *
     * @return array
     *
     * @throws RequestException
     */
    public function encodeColumn($data): array
    {
        if (!is_array($data)) {
            return $data;
        }

        foreach ($data as $key => $value) {
            if (is_array($value)) {
                $data[$key] = $this->encodeColumn($value);
            }

            if (in_array($key, Yii::$app->params['responseHashFilter']['hashColumn'], true)) {
                $data[$key] = Yii::$app->hashid->encodeId($value);
            }
        }

        return $data;
    }
}
