<?php

namespace App\Http\Controllers;

use App\Models\WeeklyData;
use Illuminate\Http\Request;

class WeeklyDataController extends Controller
{
    public function index()
    {
        $data = WeeklyData::orderBy('individual_id')
        ->orderBy('week')
        ->get()
        ->groupBy('individual_id')
        ->map(function ($group) {
            $cumulativeSum = 0;
            return $group->map(function ($item) use (&$cumulativeSum) {
                $cumulativeSum += $item->value;
                return $cumulativeSum;
            });
        });

    // dd($data->toArray());

    return view('weekly_data.index', ['data' => $data->values()->toArray()]);
   }
}
