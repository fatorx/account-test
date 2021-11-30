<?php

namespace App\Http;

use App\Account\Methods;

class Request implements IRequest
{
    private array   $server;
    private Methods $methods;

    public function __construct()
    {
        $this->server = $_SERVER;
    }

    /**
     * @return string
     */
    public function getMethodTarget(): string
    {
        $requestUri   = $this->getRequestUri();
        $method       = $this->getHttpMethod();
        $lengthUri    = strlen($requestUri);
        $uriForName   = ucfirst(substr($requestUri, 1, $lengthUri));
        return $method . $uriForName;
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
