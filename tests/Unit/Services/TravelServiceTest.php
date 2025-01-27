<?php

namespace Tests\Unit\Services;

use App\Dtos\TravelStoreDto;
use App\Models\Place;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Mockery;
use Tests\TestCase;
use App\Services\TravelService;
use App\Models\Travel;

class TravelServiceTest extends TestCase
{
    public function testStoreTravel(): void
    {
        Auth::shouldReceive('id')
            ->once()
            ->andReturn(123);

        $dto = new TravelStoreDto(...[
            'name' => 'Test name',
            'from' => '01-01-2025',
            'to' => '01-10-2025',
            'longitude' => 52.396335,
            'latitude' => 16.955996,
            'places' => [
                [
                    'name' => 'Place name',
                    'category_id' => 1,
                    'longitude' => 52.396335,
                    'latitude' => 16.955996,
                ],
            ],
            'description' => 'Test description',
        ]);

        $travel = $this->mock(Travel::class);
        $travel->shouldReceive('newInstance')->once()->andReturnSelf();
        $travel->shouldReceive('setAttribute')->with('name', $dto->name)->once()->andReturnSelf();
        $travel->shouldReceive('setAttribute')->with('description', $dto->description)->once()->andReturnSelf();
        $travel->shouldReceive('setAttribute')->with('from', $dto->from)->once()->andReturnSelf();
        $travel->shouldReceive('setAttribute')->with('to', $dto->to)->once()->andReturnSelf();
        $travel->shouldReceive('setAttribute')->with('longitude', $dto->longitude)->once()->andReturnSelf();
        $travel->shouldReceive('setAttribute')->with('latitude', $dto->latitude)->once()->andReturnSelf();
        $travel->shouldReceive('setAttribute')->with('user_id', 123)->once()->andReturnSelf();
        $travel->shouldReceive('setAttribute')->with('favourite', false)->once()->andReturnSelf();
        $travel->shouldReceive('getAttribute')->with('id')->once()->andReturn(1);
        $travel->shouldReceive('save')->once();

        $place = $this->mock(Place::class);
        $place->shouldReceive('newInstance')->once()->andReturnSelf();
        $place->shouldReceive('setAttribute')->with('travel_id', 1)->once()->andReturnSelf();
        $place->shouldReceive('setAttribute')->with('category_id', $dto->places[0]->category_id)->once()->andReturnSelf();
        $place->shouldReceive('setAttribute')->with('name', $dto->places[0]->name)->once()->andReturnSelf();
        $place->shouldReceive('setAttribute')->with('description', $dto->places[0]->description)->once()->andReturnSelf();
        $place->shouldReceive('setAttribute')->with('longitude', $dto->places[0]->longitude)->once()->andReturnSelf();
        $place->shouldReceive('setAttribute')->with('latitude', $dto->places[0]->latitude)->once()->andReturnSelf();
        $place->shouldReceive('save')->once();

        $result = app(TravelService::class)->storeTravel($dto);
        $this->assertInstanceOf(Travel::class, $result);
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }
}
