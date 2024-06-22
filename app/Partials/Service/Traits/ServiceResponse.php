<?php

namespace App\Partials\Service\Traits;

use App\Partials\Service\Response;
use Throwable;

trait ServiceResponse
{
    /**
     * HTTP response.
     * @var mixed
     */
    protected Response $response;

    protected bool $ok = true;

    protected function setNotOk(Throwable $th): void
    {
        $this->ok = false;
        $this->response = new Response(
            status: 0,
            message: $th->getMessage() . " on " . $th->getFile() . " line:" . $th->getLine(),
            code: $th->getCode(),
        );
    }

    protected function setOk(mixed $data = null, $code = 200): void
    {
        $this->ok = true;
        $this->response = new Response(
            code: $code,
            data: $data
        );
    }

    protected function setOkResponse(mixed $data  = null, $code = 200): void
    {
        $this->setOk($data, $code);
    }

    protected function setNotOkResponse(Throwable $th): void
    {
        $this->setNotOk($th);
    }

    protected function setOkReturnedData(mixed $data = null, $code = 200): void
    {
        $this->response = new Response(
            code: $code,
            data: $data
        );
    }

    protected function setNotOkReturnedData(Throwable $th): void
    {
        $this->setNotOk($th);
    }

    protected function setResponse(int $status = null, string $message = null, mixed $data = null, int $code = 200): void
    {
        $this->ok = (bool) $status;
        $this->response = new Response(
            status: $status,
            message: $message,
            code: $code,
            data: $data
        );
    }

    public function makeResponse(int $status = null, string $message = null, mixed $data = null, int $code = 200): Response
    {
        $this->ok = (bool) $status;
        return $this->response = new Response(
            status: $status,
            message: $message,
            code: $code,
            data: $data
        );
    }

    public function makeOkResponse(mixed $data, $code = 200): Response
    {
        $this->setOk($data, $code);
        return $this->response;
    }

    public function makeNotOkResponse(Throwable $th): Response
    {
        $this->setNotOk($th);
        return $this->response;
    }

    protected function response(): Response
    {
        return $this->response ?? new Response();
    }

    protected function return(): Response
    {
        return $this->response ?? new Response();
    }
}
