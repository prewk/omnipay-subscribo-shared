<?php

namespace Subscribo\Omnipay\Shared\Traits;

use Subscribo\Omnipay\Shared\Traits\HttpMessageSendingTrait;
use Subscribo\PsrHttpMessageTools\Factories\RequestFactory;
use Subscribo\PsrHttpMessageTools\Parsers\ResponseParser;

/**
 * Trait SimpleRestfulRequestTrait
 * Intended for Omnipay Request messages (expecting attribute response to be present in using class)
 *
 * @package Subscribo\OmnipaySubscriboShared
 */
trait SimpleRestfulRequestTrait
{
    use HttpMessageSendingTrait;

    /**
     * @param array $data
     * @param int $httpStatusCode
     * @return \Omnipay\Common\Message\ResponseInterface
     */
    abstract protected function createResponse(array $data, $httpStatusCode);

    /**
     * @param $data
     * @return string
     */
    abstract protected function getEndpointUrl($data);

    /**
     * @param $data
     * @return mixed
     */
    protected function getHttpRequestData($data)
    {
        return $data;
    }

    /**
     * @param $data
     * @return array
     */
    protected function getHttpRequestQueryParameters($data)
    {
        return [];
    }

    /**
     * @param $data
     * @return array
     */
    protected function getHttpRequestHeaders($data)
    {
        return [];
    }

    /**
     * @param $data
     * @return null|string
     */
    protected function getHttpRequestMethod($data)
    {
        return null;
    }

    public function sendData($data)
    {
        $httpRequest = RequestFactory::make(
            $this->getEndpointUrl($data),
            $this->getHttpRequestData($data),
            $this->getHttpRequestQueryParameters($data),
            $this->getHttpRequestHeaders($data),
            $this->getHttpRequestMethod($data)
        );
        $httpResponse = $this->sendHttpMessage($httpRequest, false);

        $this->response = $this->createResponse(
            ResponseParser::extractDataFromResponse($httpResponse),
            $httpResponse->getStatusCode()
        );

        return $this->response;
    }
}
