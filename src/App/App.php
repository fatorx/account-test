<?php

namespace App;

use App\Account\Methods;
use App\Http\Request;
use App\Http\Response;

class App
{
    private Request $request;
    private Methods $methods;

    public function __construct()
    {
        $this->request = new Request();
    }

    /**
     * @return App
     */
    public function addControl(): App
    {
        $this->methods = (new Factory())->buildClassMethods($this->request);
        return $this;
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
            $methodTarget = $this->request->getMethodTarget();
            $classMethods = get_class_methods(get_class($this->methods));

            foreach ($classMethods as $method) {
                $isTarget = ($method == $methodTarget);
                if ($isTarget) {
                    $data    = $this->methods->{$method}();
                    $status  = $this->methods->getStatus();
                    $message = $this->methods->getMessage();
                    $code    = $this->methods->getCode();
                    break;
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

        return $this->getResponse($data, $code);
    }

    /**
     * @param array $data
     * @param int $code
     * @return Response
     */
    public function getResponse(array $data, int $code): Response
    {
        return new Response($data, $code);
    }
}
