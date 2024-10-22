<?php

namespace Tests\Unit\Controllers;

use App\Http\Controllers\Auth\RegisterController;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class RegisterControllerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function register_creates_user_and_returns_token_when_data_is_valid(): void
    {
        $user = $this->mock(User::class);

        $request = new Request([
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'device_name' => 'iPhone',
        ]);

        $user->shouldReceive('create')->once()->andReturn($user);
        $user->shouldReceive('createToken')->once()->with('iPhone')->andReturn((object)['plainTextToken' => 'token']);

        Hash::shouldReceive('make')->once()->with('password123')->andReturn('hashed_password');

        $controller = new RegisterController();
        $response = $controller->register($request);

        $this->assertEquals(201, $response->status());
        $this->assertArrayHasKey('token', $response->getData(true));
    }

    /** @test */
    public function register_returns_error_when_data_is_invalid(): void
    {
        $request = new Request([
            'name' => '',
            'email' => 'invalid-email',
            'password' => 'password123',
            'password_confirmation' => 'wrong_confirmation',
            'device_name' => '',
        ]);

        $controller = new RegisterController();
        $response = $controller->register($request);

        $this->assertEquals(422, $response->status());
        $this->assertArrayHasKey('errors', $response->getData(true));
    }

}
