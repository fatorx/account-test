<?php

namespace App\Http;

interface IRequest
{
    public function getStreamParameters(): array;

    public function getHttpMethod(): string;

    public function getRequestUri(): string;

    public function getQueryString(): string;

}
