<?php

namespace app\component;

use yii\base\InvalidParamException;
use yii\web\BadRequestHttpException;
use yii\web\RequestParserInterface;

/**
 * Parses a raw HTTP request.
 *
 * To enable parsing for XML requests you can configure [[Request::parsers]] using this class:
 *
 *
 * 'request' => [
 *     'parsers' => [
 *         'test/xml' => 'common\components\XmlParser',
 *         'application/xml' => 'common\components\XmlParser',
 *     ]
 * ]
 */
class XmlParser implements RequestParserInterface
{
    /**
     * @var bool whether to return objects in terms of associative arrays
     */
    public $asArray = true;
    /**
     * @var bool whether to throw a [[BadRequestHttpException]] if the body is invalid xml
     */
    public $throwException = true;

    /**
     * Parses a HTTP request body.
     *
     * @param string $rawBody     the raw HTTP request body
     * @param string $contentType the content type specified for the request body
     *
     * @return array parameters parsed from the request body
     *
     * @throws BadRequestHttpException if the body contains invalid xml and [[throwException]] is `true`
     */
    public function parse($rawBody, $contentType)
    {
        try {
            libxml_use_internal_errors(true);
            $result = simplexml_load_string($rawBody, 'SimpleXMLElement', LIBXML_NOCDATA);
            if (false === $result) {
                $errors = libxml_get_errors();
                $latestError = array_pop($errors);
                $error = [
                    'message' => $latestError->message,
                    'type' => $latestError->level,
                    'code' => $latestError->code,
                    'file' => $latestError->file,
                    'line' => $latestError->line,
                ];
                if ($this->throwException) {
                    throw new BadRequestHttpException($latestError->message);
                }

                return $error;
            }

            return get_object_vars($result);
        } catch (InvalidParamException $e) {
            if ($this->throwException) {
                throw new BadRequestHttpException('Invalid XML data in request body: '.$e->getMessage());
            }

            return [];
        }
    }
}
