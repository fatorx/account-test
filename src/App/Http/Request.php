<?php

namespace App\Http;

use App\Account\Methods;
use App\Factory;

class Request implements IRequest
{
    private array   $server;
    private bool    $status = true;
    private string  $route = '/';
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
        if ($this->methods instanceof Methods) {
            $requestUri   = $this->getRequestUri();
            $method       = $this->getHttpMethod();
            $lengthUri    = strlen($requestUri);
            $uriForName   = ucfirst(substr($requestUri, 1, $lengthUri));
            $methodTarget = $method . $uriForName;
            $classMethods = get_class_methods(get_class($this->methods));

            foreach ($classMethods as $method) {
                if ($method == $methodTarget) {
                    $this->methods->{$method}();
                }
            }
        }

        return new Response($data);
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
            $partsUri = explode('?', $requestUri);
            $requestUri = reset($partsUri);
        }

        return $requestUri;
    }

    /**
     * @return string
     */
    public function getQueryString(): string
    {
        $queryString = $this->server['QUERY_STRING'];
        if (null !== $queryString) {
            parse_str(urldecode($queryString), $output);
            d($output);
        }
        return [];
    }
}
