<?php

namespace App\Http\Controllers\ControllerTraits;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Pagination\CursorPaginator;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

trait ApiTrait
{
    private int $statusCode = Response::HTTP_OK;

    protected function getStatusCode(): int
    {
        return $this->statusCode;
    }

    protected function setStatusCode(int $statusCode): self
    {
        $this->statusCode = $statusCode;

        return $this;
    }

    /*
    |--------------------------------------------------------------------------
    | SUCCESS RESPONSES
    |--------------------------------------------------------------------------
    */

    /**
     * Respond 200 response code.
     * Usage: GET requests.
     */
    protected function respondOK(?array $data = []): JsonResponse
    {
        return $this->setStatusCode(Response::HTTP_OK)
            ->respondAbstract($data);
    }

    /**
     * Returns 201 response code.
     * Usage: POST requests, when entity has been created and returns data.
     */
    protected function respondCreated(?array $data = []): JsonResponse
    {
        return $this->setStatusCode(Response::HTTP_CREATED)
            ->respondAbstract($data);
    }

    /**
     * Returns 207 response code.
     * Usage: POST batch requests, which have multiple statuses each for corresponding entity
     */
    protected function respondMultiStatus(array $data = []): JsonResponse
    {
        return $this->setStatusCode(Response::HTTP_MULTI_STATUS)
            ->respondAbstract($data);
    }

    /**
     * Returns 200 response code.
     * Usage: PUT. Just an abstraction for PUT requests
     */
    protected function respondUpdated(?array $data = []): JsonResponse
    {
        return $this->setStatusCode(Response::HTTP_OK)
            ->respondAbstract($data);
    }

    /**
     * Returns 204 response code.
     * Usage: DELETE requests, when entity has been deleted.
     * Response body should be empty(!)
     */
    protected function respondDeleted(): JsonResponse
    {
        return $this->setStatusCode(Response::HTTP_NO_CONTENT)
            ->respondAbstract();
    }

    /*
    |--------------------------------------------------------------------------
    | ERROR RESPONSES
    |--------------------------------------------------------------------------
    */

    /**
     * Returns 404 response code
     * Usage: GET, PUT, DELETE. Resource was not found or deleted
     */
    protected function respondNotFound(string $message = 'Not Found'): JsonResponse
    {
        return $this->setStatusCode(Response::HTTP_NOT_FOUND)
            ->respondAbstract(['message' => $message]);
    }

    /**
     * Returns 403 response code.
     * Usage: GET, PUT, DELETE. Resource or entity exists but user has no access to it
     */
    protected function respondForbidden(string $message = 'Forbidden'): JsonResponse
    {
        return $this->setStatusCode(Response::HTTP_FORBIDDEN)
            ->respondAbstract(['message' => $message]);
    }

    /**
     * Returns 422 response code
     * Usage: POST, PUT, DELETE. Request failed validation or did not match some conditions.
     */
    protected function respondUnprocessable(string $message = null, array $data = []): JsonResponse
    {
        $message ??= trans('notify_actions.unprocessable_request');

        $response = array_merge(['message' => $message], $data);

        return $this->setStatusCode(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->respondAbstract($response);
    }

    protected function respondMisdirected(array $data = []): JsonResponse
    {
        return $this->setStatusCode(Response::HTTP_MISDIRECTED_REQUEST)
            ->respondAbstract($data);
    }

    /**
     * Returns 206 response code
     */
    protected function respondPartialContent(array $data = []): JsonResponse
    {
        return $this->setStatusCode(Response::HTTP_PARTIAL_CONTENT)
            ->respondAbstract($data);
    }

    /**
     * Returns 422 response code
     * Usage: POST, PUT. Request failed validation. Can be used as a helper with manual request validation
     */
    protected function respondValidationErrors(array $data = []): JsonResponse
    {
        return $this->setStatusCode(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->respondAbstract($data);
    }

    /**
     * Returns 422 response code
     * Usage POST, PUT, DELETE. Helper method for send message with custom response code
     */
    protected function respondWithMessage(
        string $message,
        int $code = Response::HTTP_UNPROCESSABLE_ENTITY
    ): JsonResponse {
        return $this->setStatusCode($code)
            ->respondAbstract(['message' => $message]);
    }

    /*
    |--------------------------------------------------------------------------
    | OTHER RESPONSES
    |--------------------------------------------------------------------------
    */

    /**
     * Returns 204|205 response code
     * Usage POST, PUT. Helper method for send respond without content
     */
    protected function respondWithoutContent(int $code = Response::HTTP_NO_CONTENT): Response
    {
        return response()->noContent($code);
    }

    protected function respondDownload(
        string $pathToFile,
        ?string $name = null,
        array $headers = [],
        string $disposition = 'attachment'
    ): BinaryFileResponse {
        return response()->download($pathToFile, $name, $headers, $disposition);
    }

    /*
    |--------------------------------------------------------------------------
    | HELPER METHODS
    |--------------------------------------------------------------------------
    */

    /**
     * Builds a pagination array
     */
    protected function buildPagination(LengthAwarePaginator|array $paginator): LengthAwarePaginator|array
    {
        return is_array($paginator) ? $paginator : [
            'current_page' => $paginator->currentPage(),
            'first_page_url' => $paginator->url(1),
            'from' => $paginator->firstItem(),
            'last_page' => $paginator->lastPage(),
            'last_page_url' => $paginator->url($paginator->lastPage()),
            'next_page_url' => $paginator->nextPageUrl(),
            'per_page' => $paginator->perPage(),
            'prev_page_url' => $paginator->previousPageUrl(),
            'to' => $paginator->lastItem(),
            'total' => $paginator->total(),
        ];
    }

    /**
     * Used same keys with League\Fractal\Pagination\Cursor, which needed for JSON API
     */
    protected function buildCursorPagination(CursorPaginator $paginator): array
    {
        return [
            'path' => $paginator->path(),
            'per_page' => $paginator->perPage(),
            'next_cursor' => optional($paginator->nextCursor())->encode(),
            'previous_cursor' => optional($paginator->previousCursor())->encode(),
            'cursor_name' => $paginator->getCursorName(),
            'current_cursor' => optional($paginator->cursor())->encode(),
        ];
    }

    /**
     * Abstract method for all responses.
     * Just takes response code and returns data.
     */
    protected function respondAbstract(?array $data = [], array $headers = []): JsonResponse
    {
        return response()->json($data, $this->getStatusCode(), $headers);
    }
}
