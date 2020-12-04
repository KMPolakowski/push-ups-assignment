<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\AddPushUp;
use App\Models\PushUp;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
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

    public function getLeaderBoard()
    {
        $query =
            <<<SQL
SELECT
	`u0`.`id` AS `user_id`,
    SUM(`p0`.`points`) AS `sum_points`,
    `u0`.`name`,
    `u0`.`email`
FROM
    users AS `u0`
        INNER JOIN
    push_ups AS `p0` ON `u0`.`id` = `p0`.`user_id`
WHERE
	1=1
GROUP BY `u0`.`id`
ORDER BY `sum_points` DESC
LIMIT 10;
SQL;

        $leaders = DB::select($query);

        foreach ($leaders as $idx => $leader) {
            $leader->ranking = $idx + 1;
        }

        return $leaders;
    }
}
