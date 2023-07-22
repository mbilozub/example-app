<?php

namespace App\Http\Controllers\Api\Contacts;

use App\Data\Filters\ContactListFilterData;
use App\Http\Controllers\ApiController;
use App\Http\Resources\ContactResource;
use App\Models\Contact;
use App\Services\Contacts\ListHandler;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * Example URL: GET /v1/contacts
 */
final class IndexController extends ApiController
{
    public function __invoke(Request $request, ContactListFilterData $data, ListHandler $listHandler): JsonResponse
    {
        $this->authorize('viewAll', Contact::class);

        $paginator = $listHandler->list($request->user(), $data, $this->getPaginationLimit('contacts'));

        return $this->respondOK([
            'meta' => [
                'filters' => $data->toArray(),
            ],
            'pagination' => $this->buildPagination($paginator),
            'data' => ContactResource::collection($paginator)->toArray($request)
        ]);
    }
}
