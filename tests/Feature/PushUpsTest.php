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

        // dd($user);

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

    public function testLeaderboard() {
        
    }
}
