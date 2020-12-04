<?php

namespace Tests\Feature;

use App\Models\PushUp;
use App\Models\User;
use Carbon\Carbon;
use Database\Factories\PushUpFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PushUpsTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testAdd()
    {
        $user = User::factory(1)->createOne();

        $response = $this->post(
            '/api/users/' . $user->id . '/push_ups',
            [
                'amount' => 10
            ]
        );

        $response->assertStatus(200);
        $this->assertDatabaseHas('push_ups', ['user_id' => $user->id, 'amount' => 10]);
    }

    /**
     */
    public function testGetUsersPushUp()
    {
        $user = User::factory()->createOne();
        $pushUps = PushUp::factory(10)->create(['user_id' => $user->id]);

        $response = $this->get(
            '/api/users/' . $user->id . '/push_ups',
        );

        $response->assertStatus(200);

        $data = json_decode($response->getContent());

        foreach ($pushUps as $key => $pushUp) {

            $pushUpFromResp = $data[$key];

            $this->assertEquals($pushUp->id, $pushUpFromResp->id);
            $this->assertEquals($pushUp->user_id, $pushUpFromResp->user_id);
            $this->assertEquals($pushUp->amount, $pushUpFromResp->amount);
            $this->assertEquals($pushUp->points, $pushUpFromResp->points);
            $this->assertEquals($pushUp->created_at, Carbon::createFromTimeString($pushUpFromResp->created_at));
            $this->assertEquals($pushUp->updated_at, Carbon::createFromTimeString($pushUpFromResp->updated_at));
        }
    }


    public function testLeaderboard()
    {
        $data = $this->fakeLeaderboardProvider();

        foreach ($data as $userData) {  
            $user = User::factory()->create();

            foreach ($userData['points'] as $points) {
                PushUp::factory()->createOne(['points' => $points, 'user_id' => $user->id]);
            }
        }

        $response = $this->get(
            '/api/leaderboard',
        );

        $response->assertStatus(200);

        $respData = json_decode($response->getContent());

            foreach ($data as $key => $userData) {
                $rankingFromResp = $respData[$key];

                $this->assertEquals(
                    [
                        "ranking" => $userData['ranking'],
                        "sum" => $userData['sum']
                    ],
                    [
                        "ranking" => $rankingFromResp->ranking,
                        "sum" => $rankingFromResp->sum_points
                    ]
                );
        }
    }

    // fake provider, no time for setting up factories to work with data provider
    public function fakeLeaderboardProvider(): array
    {
        return [
            [
                "ranking" => 1,
                "points" => [1000, 1000, 100],
                "sum" => 2100,
                "email" => "1@.com"
            ],
            [
                "ranking" => 2,
                "points" => [1000, 1000, 10],
                "sum" => 2010,
                "email" => "2@.com"
            ], [
                "ranking" => 3,
                "points" => [1000, 1000, 1],
                "sum" => 2001,
                "email" => "3@.com"
            ], [
                "ranking" => 4,
                "points" => [12, 23, 45, 1000],
                "sum" => 1080,
                "email" => "4@.com"
            ]
        ];
    }
}
