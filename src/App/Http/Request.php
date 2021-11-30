<?php

namespace App\Http;

use App\Account\Methods;
use App\Factory;

class Request implements IRequest
{
    private array   $server;
    private Methods $methods;

    public function __construct()
    {
        $this->server = $_SERVER;
    }

    /**
     * @return void
     */
    public function addControl()
    {
        $this->methods = (new Factory())->buildClassMethods($this);;
    }

    /**
     * @return Response
     */
    public function run(): Response
    {
        $data = [];
        $isTarget = false;
        $status   = false;
        $message  = '';
        $code     = 200;

        if ($this->methods instanceof Methods) {
            $methodTarget = $this->getMethodTarget();
            $classMethods = get_class_methods(get_class($this->methods));
            
            foreach ($classMethods as $method) {
                $isTarget = ($method == $methodTarget);
                if ($isTarget) {
                    $this->methods->{$method}();
                    $status  = $this->methods->getStatus();
                    $message = $this->methods->getMessage();
                    $code    = $this->methods->getCode();
                }
            }
        }

        if (!$isTarget) {
            $data = [
                'status'  => $status,
                'message' => 'Method not found.'
            ];
        }

        if (!$status) {
            $data = [
                'status'  => false,
                'message' => $message
            ];
        }

        return $this->getResponse($data, $status, $code);
    }

    public function getMethodTarget(): string
    {
        $requestUri   = $this->getRequestUri();
        $method       = $this->getHttpMethod();
        $lengthUri    = strlen($requestUri);
        $uriForName   = ucfirst(substr($requestUri, 1, $lengthUri));
        return $method . $uriForName;
    }

    /**
     * @param array $data
     * @param bool $status
     * @param int $code
     * @return Response
     */
    public function getResponse(array $data, bool $status, int $code): Response
    {
        return new Response($data, $status, $code);
    }

    /**
     * @return array
     */
    public function getStreamParameters(): array
    {
        try {
            $jsonReqUrl    = "php://input";
            $reqJson       = file_get_contents($jsonReqUrl);
            return json_decode($reqJson, true);

        } catch (\Exception $e) {
            return [
                'message' => 'Invalid json parameters'
            ];
        }

    }

    /**
     * @return string
     */
    public function getHttpMethod(): string
    {
        return strtolower($this->server['REQUEST_METHOD']);
    }

    /**
     * @return string
     */
    public function getRequestUri(): string
    {
        $requestUri  = $this->server['REQUEST_URI'];
        if (strstr($requestUri, '?')) {
            $partsUri   = explode('?', $requestUri);
            $requestUri = reset($partsUri);
        }

        return $requestUri;
    }

    /**
     * @param string $name
     * @param int $returnDefault
     * @return string
     */
    public function getQueryString(string $name, $returnDefault = 0): string
    {
        $queryString = $this->server['QUERY_STRING'];
        if (null !== $queryString) {
            parse_str(urldecode($queryString), $pars);
            return $pars[$name] ?? $returnDefault;
        }

        return $returnDefault;
    }
}
