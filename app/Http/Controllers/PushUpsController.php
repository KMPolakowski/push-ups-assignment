<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\AddPushUp;
use App\Models\PushUp;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationData;

class PushUpsController extends Controller
{
    public function add(AddPushUp $request, int $userId)
    {
        $user = User::find($userId);

        if (!$user instanceof User) {
            return response()->json(['UserId' => 'User not found'], 404);
        }

        $amount = $request->get('amount');

        $pushUp = new PushUp();
        $pushUp->amount = $amount;
        $pushUp->points = floor(($amount % 10 * 0.5) + $amount);

        $user->PushUps()->save($pushUp);
    }

    public function getUsersPushUps(int $userId)
    {
        $user = User::find($userId);

        if (!$user instanceof User) {
            return response()->json(['UserId' => 'User not found'], 404);
        }

        $pushUps = PushUp::where('user_id', $user->id)->get();

        return $pushUps;
    }
}
