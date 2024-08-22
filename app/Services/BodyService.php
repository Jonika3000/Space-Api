<?php

namespace App\Services;

use App\Http\Resources\BodyResource;
use App\Models\Body;
use Illuminate\Support\Facades\Redis;

class BodyService
{
    public function index($page)
    {
        $bodies = Redis::get('bodies_page_'. $page);

        if(!empty($bodies)) {
            $bodies = json_decode($bodies, false);
            return $bodies;
        } else {
            $bodies = Body::with('galaxy')->paginate(15, ['*'], 'page', $page);
            Redis::set('bodies_page_'. $page, $bodies->toJson(),'EX', 600);
            return BodyResource::collection($bodies);
        }
    }
}
