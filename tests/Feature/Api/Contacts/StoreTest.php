<?php

namespace Tests\Feature\Api\Contacts;

use App\Data\ContactData;
use App\Http\Controllers\Api\Contacts\StoreController;
use App\Models\User;
use Database\Factories\ContactFactory;
use Database\Factories\UserFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\CoversClass;
use Tests\TestCase;

#[CoversClass(StoreController::class)]
class StoreTest extends TestCase
{
//    use DatabaseTransactions;
    use RefreshDatabase;

    protected const PATH = '/api/contacts';

    protected User $user;

    public function setUp(): void
    {
        parent::setUp();

        $this->user = UserFactory::new()->create();
    }

    public function test_contacts_store_unauthorized(): void
    {
        $this->postJson(self::PATH, [])
            ->assertUnauthorized();
    }

    public function test_contacts_store_wrong_email_failed(): void
    {
        $data = ContactData::from(ContactFactory::new(['email' => 'email.com'])->make()->toArray());

        $this->actingAs($this->user)->postJson(self::PATH, $data->toArray())
            ->assertUnprocessable()
            ->assertJsonValidationErrors([
                'email'
            ]);
    }

    public function test_contacts_store_successful(): void
    {
        $data = ContactData::from(ContactFactory::new()->make()->toArray());

        $this->actingAs($this->user)->postJson(self::PATH, $data->toArray())
            ->assertCreated()
            ->assertJsonStructure([
                'data' => [
                    'id',
                    'name',
                    'email',
                    'city',
                    'created_at'
                ]
            ])
            ->assertJsonPath('data.user_id', $this->user->id)
            ->assertJsonPath('data.email', $data->email);
    }
}
