<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class EmployeeController extends Controller
{
    public function index()
    {
        $employees = collect([
            ['name' => 'John', 'city' => 'Dallas'],
            ['name' => 'Jane', 'city' => 'Austin'],
            ['name' => 'Jake', 'city' => 'Dallas'],
            ['name' => 'Jill', 'city' => 'Dallas'],
        ]);

        $offices = collect([
            ['office' => 'Dallas HQ', 'city' => 'Dallas'],
            ['office' => 'Dallas South', 'city' => 'Dallas'],
            ['office' => 'Austin Branch', 'city' => 'Austin'],
        ]);

        $output = $employees
            ->groupBy('city')
            ->mapWithKeys(function ($employeesInCity, $city) use ($offices) {
                $officesInCity = $offices->where('city', $city);

                return [$city => $officesInCity->mapWithKeys(function ($office) use ($employeesInCity) {
                    $employeesInOffice = $employeesInCity->pluck('city')->first() == $office['city']
                        ? $employeesInCity->where('city', $office['city'])->pluck('name')->all()
                        : [];

                    return [$office['office'] => $employeesInOffice];
                })];
            });

        return response()->json($output);
    }
}
