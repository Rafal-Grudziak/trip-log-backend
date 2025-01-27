<?php

namespace Tests\Unit\Controllers;

use App\Http\Controllers\EnumController;
use App\Http\Resources\EnumResource;
use App\Models\TravelPreference;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Mockery;
use Tests\TestCase;

class EnumControllerTest extends TestCase
{
    public function testGetTravelPreferences(): void
    {
        $builder = $this->mock(Builder::class);
        $builder->shouldReceive('get')->once()->andReturn(collect());

        $this->mock(TravelPreference::class)
            ->shouldReceive('newQuery')->once()->andReturn($builder);

        $controller = app(EnumController::class);
        $response = $controller->getTravelPreferences();

        $this->assertSame(200, $response->getStatusCode());
    }

}
