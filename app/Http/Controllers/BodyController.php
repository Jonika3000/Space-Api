<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreBodyRequest;
use App\Http\Requests\UpdateBodyRequest;
use App\Http\Resources\BodyResource;
use App\Models\Body;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
class BodyController extends Controller implements HasMiddleware
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
        $body = Body::with('galaxy')->paginate(10);
        return BodyResource::collection($body);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreBodyRequest $request)
    {
        $imagePath = $request->file('image')->store('bodies', 'public');
        $validatedData = $request->validated();
        $validatedData['image_path'] = $imagePath;

        $body = Body::create($validatedData);
        return new BodyResource($body);
    }

    /**
     * Display the specified resource.
     */
    public function show(Body $body)
    {
        $body->load('galaxy', 'posts');
        return new BodyResource($body);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateBodyRequest $request, Body $body)
    {
        $body->update($request->validated());
        return new BodyResource($body);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Body $body)
    {
        $body->delete();
        return response()->json(null,204);
    }
}
