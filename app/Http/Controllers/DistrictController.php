<?php

namespace App\Http\Controllers;

use App\Http\Requests\SeachIndexRequest;
use App\Models\District;
use Illuminate\Support\Facades\Cache;
use Location\Coordinate;
use Location\Polygon;

class DistrictController extends Controller
{
    public function oldLocationTehran()
    {
        Cache::forget('oldLocationTehran');
        $value = Cache::get('oldLocationTehran');
        return [
            'value' => $value,
            'message' => "old value removed"
        ];
    }

    public function check(SeachIndexRequest $request)
    {
        $longNow = $request->input('longitude_now');
        $latNow = $request->input('latitude_now');
        $message = 'موقعیت شما بدون تغییر است';
        $location = "outside";

        District::all()->each(function (District $district) use ($latNow, $longNow, &$location) {
            $geofence = new Polygon();
            foreach ($district->shape as $shape) {
                $geofence->addPoint(new Coordinate($shape['latitude'], $shape['longitude']));
            }
            if ($geofence->contains(new Coordinate($latNow, $longNow))) {
                $location = "inside";
            }
        });

        $lastLocation = Cache::get('oldLocationTehran');

        if (!$lastLocation) {
            $message = "اولین موقعیت شماست";

        } elseif ($lastLocation == "outside" && $location == "inside") {

            $message = "شما وارد تهران شدید.";

        } elseif ($lastLocation == "inside" && $location == "outside") {

            $message = "شما از تهران خارج شدید.";

        }
        Cache::put('oldLocationTehran',$location);
        return [
            'message' => $message
        ];
    }
}
