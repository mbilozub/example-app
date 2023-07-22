<?php

namespace App\Listeners\Log;

use App\Events\Contacts\ContactCreatedEvent;
use Illuminate\Support\Facades\Log;

class SaveLog
{
    public function handle(ContactCreatedEvent $event): void
    {
        Log::debug("New Contact Created: ID#{$event->contact->id}");
    }
}
