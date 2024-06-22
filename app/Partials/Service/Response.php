<?php

namespace App\Partials\Service;


use Illuminate\Contracts\Support\Responsable;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Illuminate\Http\Resources\Json\JsonResource;

class Response implements Responsable
{
    public int $code = 200;
    public ?string $message = null;
    public mixed $data = null;
    public int $status = 1;
    public bool $ok = true;

    public function __construct(int $status = null, string $message = null, mixed $data = null, $code = 200)
    {
        $this->code = $this->processStatusCode($code);
        $this->status = $status ?? 1;
        $this->message = $message;
        $this->data = $data;
        $this->ok = (bool) $status;
    }

    public function __serialize(): array
    {
        return [
            'status' => $this->status,
            'code' => $this->code,
            'message' => $this->message,
            'data' => $this->data
        ];
    }

    public function toResponse($request)
    {
        return $this->response($request);
    }

    protected function processStatusCode($code)
    {
        if (is_string($code)) {
            $code = (int)$code;
        }
        if ($code >= 200 && $code < 600) {
            return $code;
        } else {
            return 500;
        }
    }

    public function response($request)
    {
        if ($this->data instanceof StreamedResponse) {
            return $this->data;
        }
        if ($this->code == 204) {
            return response()->json(status: $this->code);
        }
        if ($this->status == 1) {
            if ($this->data instanceof JsonResource) {
                return $this->data->toResponse(request: $request);
            }
            return $this->data;
        } else {
            return response()->json(
                data: [
                    'message' => $this->message
                ],
                status: $this->code
            );
        }
    }
}
