<?php

namespace App\Http;

class Response
{
    private array $data = [];
    private bool  $status    = true;
    private int   $code       = 200;

    /**
     * @param array $data
     * @param bool $status
     * @param int $code
     */
    public function __construct(array $data, bool $status, int $code)
    {
        $this->data   = $data;
        $this->status = $status;
        $this->code   = $code;
    }

    public function __invoke()
    {
        http_response_code($this->code);
        header('Content-type: application/json');
        echo json_encode( $this->data );
    }
}
