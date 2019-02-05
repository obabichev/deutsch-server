<?php

namespace App\Http\Controllers;

use App\Glossary;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class GlossaryController extends Controller
{

    /**
     * @OA\Get(
     *      path="/api/glossary",
     *      operationId="getGlossaries",
     *      tags={"Glossary"},
     *      summary="Get glossaries list",
     *      description="Returns glossaries list",
     *      @OA\Response(
     *          response=200,
     *          description="successful operation",
     *          @OA\JsonContent()
     *       ),
     *   security={{"api_key":{}}}
     *     )
     */
    public function index()
    {
        $user = Auth::user();

        return Glossary::where('owner_id', $user->id)
            ->limit(100)
            ->with(['cards.word', 'cards.translation'])
            ->get();
    }

    /**
     * @OA\Get(
     *      path="/api/glossary/{glossaryId}",
     *      operationId="getGlossary",
     *      tags={"Glossary"},
     *      summary="Get glossary",
     *      description="Returns glossary",
     *      @OA\Parameter(
     *         name="glossaryId",
     *         in="path",
     *         description="ID of glossary",
     *         required=true,
     *         @OA\Schema(
     *             type="integer",
     *             format="int64"
     *         )
     *     ),
     *      @OA\Response(
     *          response=200,
     *          description="successful operation",
     *          @OA\JsonContent()
     *       ),
     *   @OA\Response(response=404, description="Resource not found"),
     *   security={{"api_key":{}}}
     *     )
     *
     */
    public function show(Glossary $glossary)
    {
        $glossary->load(['cards.word', 'cards.translation']);
        return $glossary;
    }

    /**
     * @OA\Post(
     *      path="/api/glossary",
     *      operationId="glossaryPost",
     *      tags={"Glossary"},
     *      summary="Create glossary",
     *      description="Create glossary",
     * @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 @OA\Property(
     *                     property="title",
     *                     type="string"
     *                 ),
     *                 example={"title": "unknown"}
     *             )
     *         )
     *     ),
     *      @OA\Response(
     *          response=200,
     *          description="successful operation",
     *          @OA\JsonContent()
     *       ),
     *   security={{"api_key":{}}}
     *     )
     */
    public function store(Request $request)
    {
        $user = Auth::user();
        $data = $request->all();

        $glossary = new Glossary([
            'title' => $data['title']
        ]);
        $glossary->owner()->associate($user);
        $glossary->save();
        $glossary->load(['cards.word', 'cards.translation']);

        return response()->json($glossary, 201);
    }

    /**
     * @OA\PUT(
     *      path="/api/glossary/{glossaryId}",
     *      operationId="glossaryPost",
     *      tags={"Glossary"},
     *      summary="Create glossary",
     *      description="Create glossary",
     *      @OA\Parameter(
     *         name="glossaryId",
     *         in="path",
     *         description="ID of glossary",
     *         required=true,
     *         @OA\Schema(
     *             type="integer",
     *             format="int64"
     *         )
     *     ),
     * @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 @OA\Property(
     *                     property="title",
     *                     type="string"
     *                 ),
     *                 example={"title": "unknown"}
     *             )
     *         )
     *     ),
     *      @OA\Response(
     *          response=200,
     *          description="successful operation",
     *          @OA\JsonContent()
     *       ),
     *     @OA\Response(response=404, description="Resource not found"),
     *   security={{"api_key":{}}}
     *     )
     */
    public function update(Request $request, Glossary $glossary)
    {
        $glossary->update($request->all());
        $glossary->load(['cards.word', 'cards.translation']);

        return response()->json($glossary, 200);
    }

    /**
     * @OA\Delete(
     *      path="/api/glossary/{glossaryId}",
     *      operationId="delGlossary",
     *      tags={"Glossary"},
     *      summary="Delete glossary",
     *      description="Delete glossary",
     *      @OA\Parameter(
     *         name="glossaryId",
     *         in="path",
     *         description="ID of glossary",
     *         required=true,
     *         @OA\Schema(
     *             type="integer",
     *             format="int64"
     *         )
     *     ),
     *      @OA\Response(
     *          response=204,
     *          description="successful operation",
     *          @OA\JsonContent()
     *       ),
     *   @OA\Response(response=404, description="Resource not found"),
     *   security={{"api_key":{}}}
     *     )
     */
    public function delete($id)
    {
        $glossary = Glossary::find($id);

        if (empty($glossary)) {
            throw new ModelNotFoundException();
        }

        $glossary->delete();

        return response()->json(null, 204);
    }
}