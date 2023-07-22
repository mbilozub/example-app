<?php

namespace App\Services\Contacts;

use App\Data\ContactData;
use App\Events\Contacts\ContactCreatedEvent;
use App\Models\Contact;
use App\Models\User;
use App\Repositories\ContactsRepository;

class StoreHandler
{
    public function __construct(protected ContactsRepository $repository)
    {
    }

    public function create(User $user, ContactData $data): Contact
    {
        $contact = new Contact();
        $contact->user()->associate($user);
        $contact->fill($data->all());

        $this->repository->save($contact);

        event(new ContactCreatedEvent($contact));

        return $contact;
    }
}
