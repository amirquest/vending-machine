<?php

namespace App\Concerns;

use Illuminate\Contracts\Pagination\LengthAwarePaginator as LengthAwarePaginatorContract;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Symfony\Component\HttpFoundation\Response;

trait ResponseTrait
{
    private array $metas = [];

    private array $additional = [];

    private ?string $message = null;

    private bool $hasErrors = false;

    public function additional(): array
    {
        return $this->additional;
    }

    public function setAdditional(array $data): static
    {
        $this->additional = $data;

        return $this;
    }

    public function metas(): array
    {
        return $this->metas;
    }

    public function setMetas(array $metas): self
    {
        $this->metas = $metas;

        return $this;
    }

    public function message(): string
    {
        return $this->message ?? __('response.successful_message');
    }

    public function setMessage(string $message): static
    {
        $this->message = $message;

        return $this;
    }

    public function hasErrors(): bool
    {
        return $this->hasErrors;
    }

    public function setHasErrors(bool $hasErrors): self
    {
        $this->hasErrors = $hasErrors;

        return $this;
    }

    public function success(
        ResourceCollection|JsonResource|array|string $data = [],
        int $status = Response::HTTP_OK,
    ): JsonResponse {
        return $this->respond($data, statusCode: $status);
    }

    public function error(
        ?string $message = null,
        ResourceCollection|JsonResource|array|string $constraints = [],
        int $status = Response::HTTP_SERVICE_UNAVAILABLE,
        array $headers = [],
    ): JsonResponse {
        return $this->setHasErrors(true)
            ->setMessage($message ?? __('response.error'))
            ->respond($constraints, $status, $headers);
    }

    public function created(
        ResourceCollection|JsonResource|array|string $data = [],
        ?string $message = null
    ): JsonResponse {
        return $this
            ->setMessage($message ?? __('response.entity_created'))
            ->respond($data, Response::HTTP_CREATED);
    }

    public function removed(int $id): JsonResponse
    {
        return $this->setMessage(__('response.entity_removed'))->respond(['id' => $id]);
    }

    public function notFound(?string $message = null): JsonResponse
    {
        return $this->error(
            $message ?? __('response.not_found'),
            status: Response::HTTP_NOT_FOUND
        );
    }

    public function failedValidation(?string $message = null): JsonResponse
    {
        return $this->error(
            $message ?? __('response.failed_validation'),
            status: Response::HTTP_UNPROCESSABLE_ENTITY
        );
    }

    public function unauthorized(?string $message = null): JsonResponse
    {
        return $this->error(
            $message ?? __('response.unauthorized'),
            status: Response::HTTP_UNAUTHORIZED
        );
    }

    public function forbidden(?string $message = null): JsonResponse
    {
        return $this->error(
            $message ?? __('response.forbidden'),
            status: Response::HTTP_FORBIDDEN
        );
    }

    public function tooManyRequest(?string $message = null, array $headers = []): JsonResponse
    {
        return $this->error(
            $message ?? __('response.too_many_request'),
            status: Response::HTTP_TOO_MANY_REQUESTS,
            headers: $headers,
        );
    }

    public function notAcceptable(?string $message = null): JsonResponse
    {
        return $this->error(
            $message ?? __('response.not_acceptable'),
            status: Response::HTTP_NOT_ACCEPTABLE
        );
    }

    public function badRequest(?string $message = null): JsonResponse
    {
        return $this->error(
            $message ?? __('response.bad_request'),
            status: Response::HTTP_BAD_REQUEST
        );
    }

    private function respond(
        ResourceCollection|JsonResource|array|string $data = [],
        int $statusCode = Response::HTTP_OK,
        array $headers = []
    ): JsonResponse {
        if ($data instanceof ResourceCollection && $data->resource instanceof LengthAwarePaginatorContract) {
            return $this->respondWithPagination($data, [], $headers);
        }

        $response = [...[
            'succeed' => !$this->hasErrors(),
            'message' => $this->message(),
        ], ...$this->additional()];

        if ($data) {
            $response['results'] = $data;
        }

        if (!empty($this->metas)) {
            $response['metas'] = $this->metas;
        }

        return response()->json(
            $response,
            $statusCode,
            $headers
        );
    }

    private function respondWithPagination(
        ResourceCollection $resource,
        array $metas = [],
        array $headers = [],
    ): JsonResponse {
        $data = $resource->response()->getData(true);

        $metas['links'] = $data['links'];

        return $this->setMetas(array_merge($data['meta'], $metas, $this->metas()))
            ->respond($data['data'], Response::HTTP_OK, $headers);
    }

}
