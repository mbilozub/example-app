<?php

namespace App\Repositories;

use App\Models\Contact;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

class ContactsRepository
{
    protected function baseQuery(): Builder
    {
        return Contact::query();
    }

    /**
     * @param Model|Model[]|Collection $entities
     */
    public function save($entities): void
    {
        foreach (Collection::wrap($entities) as $entity) {
            /* @var Model $entity */
            $entity->save();
        }
    }

    public function sortedList(string $order): Builder
    {
        return $this->baseQuery()->orderBy('id', $order);
    }
}
