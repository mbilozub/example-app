<?php

namespace App\Http\Controllers\Api\Contacts;

use App\Data\ContactData;
use App\Http\Controllers\ApiController;
use App\Http\Resources\ContactResource;
use App\Models\Contact;
use App\Services\Contacts\StoreHandler;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

/**
 * Example URL: POST /v1/contacts
 */
final class StoreController extends ApiController
{
    public function __invoke(Request $request, ContactData $data, StoreHandler $storeHandler): JsonResponse
    {
        $this->authorize('store', Contact::class);

        $contact = DB::transaction(static fn() => $storeHandler->create($request->user(), $data));

        return $this->respondCreated([
            'data' => new ContactResource($contact)
        ]);
    }
}
