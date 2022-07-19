<?php


function distance($latitude, $longitude, $radius = 400)
{
    /*
     * using eloquent approach, make sure to replace the "Restaurant" with your actual model name
     * replace 6371000 with 6371 for kilometer and 3956 for miles
     */
     return DB::selectRaw("id, latitude, longitude,
                         ( 6371000 * acos( cos( radians(?) ) *
                           cos( radians( latitude ) )
                           * cos( radians( longitude ) - radians(?)
                           ) + sin( radians(?) ) *
                           sin( radians( latitude ) ) )
                         ) AS distance", [$latitude, $longitude, $latitude])
        ->having("distance", "<", $radius)
        ->get();

}