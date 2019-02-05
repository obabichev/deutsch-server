<?php

namespace App\Http\Controllers;

use App\WordProgress;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WordProgressController extends Controller
{
    /**
     * @OA\Get(
     *      path="/api/wordprogress",
     *      operationId="getWordProgresses",
     *      tags={"Word progress"},
     *      summary="Get word progresses list",
     *      description="Get word progresses list",
     *      @OA\Response(
     *          response=200,
     *          description="successful operation",
     *          @OA\JsonContent()
     *       ),
     *   security={{"api_key":{}}}
     *     )
     */
    /**
     * @OA\Get(
     *      path="/api/wordprogress?filter=repeat",
     *      operationId="getRepeatWordProgresses",
     *      tags={"Word progress"},
     *      summary="Get word progresses to repeat",
     *      description="Get word progresses to repeat",
     *      @OA\Response(
     *          response=200,
     *          description="successful operation",
     *          @OA\JsonContent()
     *       ),
     *   security={{"api_key":{}}}
     *     )
     */
    public function index(Request $request)
    {
        $user = Auth::user();

        $filter = $request->query->get('filter');

        $query = WordProgress::where('user_id', $user->id);

        if ($filter === 'repeat') {
            $query->where('repeat', '<', new \DateTime());
        }

        $progresses = $query->limit(100)
            ->get();

        $progresses->load(['word', 'translation']);
        return $progresses;
    }

    /**
     * @OA\Post(
     *      path="/api/wordprogress",
     *      operationId="wordprogressPost",
     *      tags={"Word progress"},
     *      summary="Create word progress",
     *      description="Create word progress",
     * @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 @OA\Property(
     *                     property="learned",
     *                     type="bool"
     *                 ),
     *                 @OA\Property(
     *                     property="word_id",
     *                     type="integer"
     *                 ),
     *                 @OA\Property(
     *                     property="translation_id",
     *                     type="integer"
     *                 ),
     *                 example={
     *                      "learned": true,
     *                      "word_id": 362742,
     *                      "translation_id": 1
     *                  }
     *             )
     *         )
     *     ),
     *      @OA\Response(
     *          response=201,
     *          description="successful operation",
     *          @OA\JsonContent()
     *       ),
     *     @OA\Response(response=404, description="Resource not found"),
     *   security={{"api_key":{}}}
     *     )
     */
    public function store(Request $request)
    {
        $user = Auth::user();
        $data = $request->all();

        $wordId = $data['word_id'];
        $translation_id = $data['translation_id'];

        $wordProgress = WordProgress::where('user_id', $user->id)
            ->where('word_id', $wordId)
            ->where('translation_id', $translation_id)
            ->first();

        if (empty($wordProgress)) {
            $wordProgress = new WordProgress();
            $wordProgress->user()->associate($user);
            $wordProgress->word()->associate($wordId);
            $wordProgress->translation()->associate($translation_id);
        }

        if (isset($data['learned'])) {
            $wordProgress->learned = $data['learned'];
            if ($wordProgress->learned) {
                $date = new \DateTime();
                $date->modify('+5 minutes');
                $wordProgress->repeat = $date;
            }
        }


        $wordProgress->save();
        $wordProgress->refresh();

        return response()->json($wordProgress, 201);
    }

    /**
     * @OA\PUT(
     *      path="/api/wordprogress/{wordprogressId}?action=repeat",
     *      operationId="wordProgressRepeatPut",
     *      tags={"Word progress"},
     *      summary="Repeat word progress",
     *      description="Repeat word progress",
     *      @OA\Parameter(
     *         name="wordprogressId",
     *         in="path",
     *         description="ID of word progress",
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
     *                     property="success",
     *                     type="bool"
     *                 ),
     *                 example={"success": true}
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
    public function update(Request $request, WordProgress $wordProgress)
    {
        $action = $request->query->get('action');
        if ($action === 'repeat') {
            $this->repeatAction($wordProgress, $request->all());
        }
        return response()->json($wordProgress, 200);
    }

    protected function repeatAction(WordProgress $wordProgress, array $data)
    {
        $success = $data['success'];
        if ($success) {
            $wordProgress->successes = $wordProgress->successes + 1;
            $wordProgress->setRepeat($this->calculateRepeatDate($wordProgress));
        } else {
            $wordProgress->mistakes = $wordProgress->mistakes + 1;
        }

        $wordProgress->save();
    }

    protected function calculateRepeatDate(WordProgress $wordProgress)
    {
        if (empty($wordProgress->repeat)) {
            return null;
        }

        $repeat = new \DateTime();
        $successes = $wordProgress->successes;


        if ($successes === 0) {
            $repeat->modify('+5 minutes');
            return $repeat;
        }

        if ($successes <= 3) {
            $repeat->modify("+$successes days");
            return $repeat;
        }

        if ($successes <= 7) {
            $weeks = $successes - 3;
            $repeat->modify("+$weeks weeks");
            return $repeat;
        }

        $months = $successes - 7;
        $repeat->modify("+$months months");
        return $repeat;
    }
}