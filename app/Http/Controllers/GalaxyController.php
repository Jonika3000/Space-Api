<?php

namespace App\Http\Controllers;

use App\Http\Middleware\CheckIsAdminMiddleware;
use App\Http\Requests\Galaxy\StoreGalaxyRequest;
use App\Http\Requests\Galaxy\UpdateGalaxyRequest;
use App\Http\Resources\GalaxyResource;
use App\Models\Galaxy;
use App\Services\GalaxyService;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

/**
 * @OA\Tag(
 *     name="Galaxies",
 *     description="Operations related to galaxies"
 * )
 */
class GalaxyController extends Controller implements HasMiddleware
{
    public function __construct(private GalaxyService $galaxyService) {}

    public static function middleware(): array
    {
        return [
            new Middleware(middleware: 'auth:sanctum', except: ['index', 'show']),
            new Middleware(middleware: CheckIsAdminMiddleware::class, except: ['index', 'show'])
        ];
    }

    /**
     * @OA\Get(
     *     path="/api/galaxies",
     *     tags={"Galaxies"},
     *     summary="List all galaxies",
     *     description="Retrieve a list of galaxies with pagination",
     *     @OA\Response(
     *         response=200,
     *         description="List of galaxies",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(
     *                 property="data",
     *                 type="array",
     *                 @OA\Items(ref="#/components/schemas/Galaxy")
     *             ),
     *             @OA\Property(property="links", type="object"),
     *             @OA\Property(property="meta", type="object")
     *         )
     *     )
     * )
     */
    public function index()
    {
        $page = request()->get('page', 1);

        return $this->galaxyService->index($page);
    }

    /**
     * @OA\Post(
     *     path="/api/galaxies",
     *     tags={"Galaxies"},
     *     summary="Create a new galaxy",
     *     description="Store a newly created galaxy resource",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="title", type="string"),
     *             @OA\Property(property="description", type="string")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Galaxy created successfully",
     *         @OA\JsonContent(ref="#/components/schemas/Galaxy")
     *     )
     * )
     */
    public function store(StoreGalaxyRequest $request)
    {
        $galaxy = Galaxy::create($request->validated());

        return new GalaxyResource($galaxy);
    }

    /**
     * @OA\Get(
     *     path="/api/galaxies/{id}",
     *     tags={"Galaxies"},
     *     summary="Get a specific galaxy",
     *     description="Retrieve a specific galaxy by ID",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID of the galaxy to retrieve",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Galaxy details",
     *         @OA\JsonContent(ref="#/components/schemas/Galaxy")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Galaxy not found"
     *     )
     * )
     */
    public function show(Galaxy $galaxy)
    {
        return new GalaxyResource($galaxy->load('bodies'));
    }

    /**
     * @OA\Put(
     *     path="/api/galaxies/{id}",
     *     tags={"Galaxies"},
     *     summary="Update a specific galaxy",
     *     description="Update a specific galaxy resource",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID of the galaxy to update",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="title", type="string"),
     *             @OA\Property(property="description", type="string")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Galaxy updated successfully",
     *         @OA\JsonContent(ref="#/components/schemas/Galaxy")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Galaxy not found"
     *     )
     * )
     */
    public function update(UpdateGalaxyRequest $request, Galaxy $galaxy)
    {
        $galaxy->update($request->validated());

        return new GalaxyResource($galaxy);
    }

    /**
     * @OA\Delete(
     *     path="/api/galaxies/{id}",
     *     tags={"Galaxies"},
     *     summary="Delete a specific galaxy",
     *     description="Delete a specific galaxy resource",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID of the galaxy to delete",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=204,
     *         description="Galaxy deleted successfully"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Galaxy not found"
     *     )
     * )
     */
    public function destroy(Galaxy $galaxy)
    {
        $galaxy->delete();

        return response()->json(null, 204);
    }
}
