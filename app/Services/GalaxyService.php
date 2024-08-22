<?php

namespace App\Services;

use App\Http\Resources\GalaxyResource;
use App\Models\Galaxy;
use Illuminate\Support\Facades\Redis;

class GalaxyService
{
    public function index($page)
    {
        $galaxies = Redis::get('galaxies_page_'. $page);

        if(!empty($galaxies)) {
            $galaxies = json_decode($galaxies, false);
            return $galaxies;
        } else {
            $galaxies = Galaxy::with('bodies')->paginate(15, ['*'], 'page', $page);
            Redis::set('galaxies_page_'. $page, $galaxies->toJson(),'EX', 600);
            return GalaxyResource::collection($galaxies);
        }
    }
}
