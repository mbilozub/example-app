<?php

namespace App\Policies\Contacts;

use App\Models\Contact;
use App\Models\User;
use Illuminate\Auth\Access\Response;

/**
 * Policy for {@see Contact}.
 */
class ContactPolicy
{
    public function viewAll(User $authenticated): Response
    {
        return Response::allow();
    }

    public function store(User $authenticated): Response
    {
        return Response::allow();
    }
}
