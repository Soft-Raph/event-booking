<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use RefreshDatabase;
    #[Test]
    public function it_registers_and_logs_in()
    {
        $payload = [
            'name' => 'Ajoke',
            'email' => 'ajoke@example.com',
            'password' => 'secret1234',
            'phone' => '0700',
            'role' => 'customer',
        ];

        $this->postJson('/api/register', $payload)->assertStatus(201);

        $this->postJson('/api/login', [
            'email' => 'ajoke@example.com',
            'password' => 'secret1234',
        ])->assertOk()->assertJsonPath('success', true);
    }
}
