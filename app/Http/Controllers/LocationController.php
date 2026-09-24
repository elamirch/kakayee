<?php

namespace App\Http\Controllers;

use App\Models\Country;

class LocationController extends Controller
{
    public function index()
    {
        $countries = Country::with(['provinces.cities'])->get();

        return response()->json([
            'countries' => $countries->map(fn ($country) => [
                'id' => $country->id,
                'name' => $country->name,
                'iso2' => $country->iso2,
                'provinces' => $country->provinces->map(fn ($province) => [
                    'id' => $province->id,
                    'name' => $province->name,
                    'cities' => $province->cities->map(fn ($city) => [
                        'id' => $city->id,
                        'name' => $city->name,
                    ])->values(),
                ])->values(),
            ])->values(),
        ]);
    }
}
