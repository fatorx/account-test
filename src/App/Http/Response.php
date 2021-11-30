<?php

namespace App\Http;

class Response
{
    private array $data;
    private int   $code;

    /**
     * @param array $data
     * @param int $code
     */
    public function __construct(array $data, int $code)
    {
        $this->data   = $data;
        $this->code   = $code;
    }

    public function __invoke()
    {
        http_response_code($this->code);
        if ($this->code == 404) {
            echo 0;
            exit();
        }

        if (isset($this->data['value'])) {
            echo $this->data['value'];
            exit();
        }

        if ($this->data) {
            header('Content-type: application/json');
            echo json_encode( $this->data );
        }
    }
}
