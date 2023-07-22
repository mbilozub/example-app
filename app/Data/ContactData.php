<?php

namespace App\Data;

use Spatie\LaravelData\Data;
use Spatie\LaravelData\Support\Validation\ValidationContext;

class ContactData extends Data
{
    public function __construct(
        public string $name,
        public string $email,
        public ?string $city,
    ) {
    }

    public static function rules(ValidationContext $context): array
    {
        return [
            'name' => ['required', 'string', 'min:3', 'max:100'],
            'email' => ['required', 'email'],
            'city' => ['nullable', 'max:200'],
        ];
    }
}
