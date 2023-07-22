<?php

namespace Tests\Feature\Api\Contacts;

use App\Http\Controllers\Api\Contacts\IndexController;
use App\Models\User;
use Database\Factories\ContactFactory;
use Database\Factories\UserFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\CoversClass;
use Tests\TestCase;

#[CoversClass(IndexController::class)]
class IndexTest extends TestCase
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

    public function test_contacts_with_wrong_order_value_returns_failed(): void
    {
        $this->actingAs($this->user)->getJson(self::PATH . '?order=xxx')
            ->assertUnprocessable()
            ->assertJsonValidationErrors([
                'order'
            ]);
    }

    public function test_contacts_returns_a_successful_response(): void
    {
        ContactFactory::new()->forUser($this->user)->count(5)->create();

        $this->actingAs($this->user)->getJson(self::PATH)
            ->assertOk()
            ->assertJsonStructure([
                'meta' => [
                    'filters' => [
                        'search',
                        'order',
                    ]
                ],
                'pagination' => [
                    'total'
                ],
                'data' => [
                    [
                        'id',
                        'name',
                        'email',
                        'city'
                    ]
                ]
            ])
            ->assertJsonPath('pagination.total', 5);
    }
}
