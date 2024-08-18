<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreBodyRequest;
use App\Http\Requests\UpdateBodyRequest;
use App\Http\Resources\BodyResource;
use App\Models\Body;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

/**
 * @OA\Tag(
 *     name="Bodies",
 *     description="Bodies"
 * )
 */
class BodyController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware(middleware: 'auth:sanctum', except: ['index', 'show']),
        ];
    }

    /**
     * @OA\Get(
     *     path="/api/bodies",
     *     tags={"Bodies"},
     *     summary="List all bodies",
     *     description="Retrieve a list of bodies with pagination",
     *     @OA\Response(
     *         response=200,
     *         description="List of bodies",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(
     *                 property="data",
     *                 type="array",
     *                 @OA\Items(ref="#/components/schemas/Body")
     *             ),
     *             @OA\Property(property="links", type="object"),
     *             @OA\Property(property="meta", type="object")
     *         )
     *     )
     * )
     */
    public function index()
    {
        $body = Body::with('galaxy')->paginate(10);
        return BodyResource::collection($body);
    }

    /**
     * @OA\Post(
     *     path="/api/bodies",
     *     tags={"Bodies"},
     *     summary="Create a new body",
     *     description="Store a newly created body resource",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="title", type="string"),
     *             @OA\Property(property="type", type="string"),
     *             @OA\Property(property="description", type="string"),
     *             @OA\Property(property="image", type="string", format="binary"),
     *             @OA\Property(property="galaxy_id", type="integer")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Body created successfully",
     *         @OA\JsonContent(ref="#/components/schemas/Body")
     *     )
     * )
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
     * @OA\Get(
     *     path="/api/bodies/{id}",
     *     tags={"Bodies"},
     *     summary="Get a specific body",
     *     description="Retrieve a specific body by ID",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID of the body to retrieve",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Body details",
     *         @OA\JsonContent(ref="#/components/schemas/Body")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Body not found"
     *     )
     * )
     */
    public function show(Body $body)
    {
        $body->load( 'galaxy', 'posts');

        return new BodyResource($body);
    }

    /**
     * @OA\Put(
     *     path="/api/bodies/{id}",
     *     tags={"Bodies"},
     *     summary="Update a specific body",
     *     description="Update a specific body resource",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID of the body to update",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="title", type="string"),
     *             @OA\Property(property="type", type="string"),
     *             @OA\Property(property="description", type="string"),
     *             @OA\Property(property="image", type="string", format="binary"),
     *             @OA\Property(property="galaxy_id", type="integer")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Body updated successfully",
     *         @OA\JsonContent(ref="#/components/schemas/Body")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Body not found"
     *     )
     * )
     */
    public function update(UpdateBodyRequest $request, Body $body)
    {
        $body->update($request->validated());
        return new BodyResource($body);
    }

    /**
     * @OA\Delete(
     *     path="/api/bodies/{id}",
     *     tags={"Bodies"},
     *     summary="Delete a specific body",
     *     description="Delete a specific body resource",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID of the body to delete",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=204,
     *         description="Body deleted successfully"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Body not found"
     *     )
     * )
     */
    public function destroy(Body $body)
    {
        $body->delete();
        return response()->json(null,204);
    }
}
