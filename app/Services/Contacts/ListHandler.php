<?php

namespace App\Services\Contacts;

use App\Data\Filters\ContactListFilterData;
use App\Models\User;
use App\Repositories\ContactsRepository;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

/**
 * Handle user's request of contacts list.
 */
class ListHandler
{
    public function __construct(protected ContactsRepository $repository)
    {
    }

    public function list(User $user, ContactListFilterData $data, int $limit): LengthAwarePaginator
    {
        return $this->repository->sortedList($data->order->value)->where('user_id', $user->id)->paginate($limit);
    }
}
