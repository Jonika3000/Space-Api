<?php

namespace App\Http\Controllers;

use App\Http\Requests\Galaxy\StoreGalaxyRequest;
use App\Http\Requests\Galaxy\UpdateGalaxyRequest;
use App\Http\Resources\GalaxyResource;
use App\Models\Galaxy;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\Middleware;

class GalaxyController extends Controller
{
    public static function middleware(): array
    {
        return [
            new Middleware(middleware: 'auth:sanctum', except: ['index', 'show']),
        ];
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $galaxies = Galaxy::with('bodies')->paginate(10);

        return GalaxyResource::collection($galaxies);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreGalaxyRequest $request)
    {
        $galaxy = Galaxy::create($request->validated());

        return new GalaxyResource($galaxy);
    }

    /**
     * Display the specified resource.
     */
    public function show(Galaxy $galaxy)
    {
        return new GalaxyResource($galaxy->load('bodies'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateGalaxyRequest $request, Galaxy $galaxy)
    {
        $galaxy->update($request->validated());

        return new GalaxyResource($galaxy);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Galaxy $galaxy)
    {
        $galaxy->delete();

        return response()->json(null, 204);
    }
}
