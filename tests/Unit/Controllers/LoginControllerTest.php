<?php

namespace Tests\Unit\Auth;

use App\Http\Controllers\Auth\LoginController;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Tests\TestCase;

class LoginControllerTest extends TestCase
{
    /** @test */
    public function login_returns_token_and_user_when_credentials_are_correct()
    {
        $user = $this->mock(User::class);

        $request = new Request([
            'email' => 'user@example.com',
            'password' => 'password123',
            'device_name' => 'android',
        ]);

        Auth::shouldReceive('attempt')->once()->andReturn(true);
        Auth::shouldReceive('user')->once()->andReturn($user);
        $user->shouldReceive('createToken')->once()->andReturn((object)['plainTextToken' => 'token']);

        $controller = new LoginController();
        $response = $controller->login($request);

        $this->assertEquals(200, $response->status());
        $this->assertArrayHasKey('token', $response->getData(true));
    }

    /** @test */
    public function login_returns_error_when_credentials_are_incorrect()
    {
        $request = new Request([
            'email' => 'user@example.com',
            'password' => 'wrongpassword',
            'device_name' => 'android',
        ]);

        Auth::shouldReceive('attempt')->once()->andReturn(false);

        $controller = new LoginController();
        $response = $controller->login($request);

        $this->assertEquals(401, $response->status());
        $this->assertEquals('Invalid login credentials.', $response->getData(true)['message']);
    }
}
