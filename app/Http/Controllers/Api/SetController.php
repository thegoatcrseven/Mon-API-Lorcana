<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\LorcanApiService;
use Illuminate\Http\JsonResponse;

class SetController extends Controller
{
    private $lorcanService;

    public function __construct(LorcanApiService $lorcanService)
    {
        $this->lorcanService = $lorcanService;
    }

    public function index(): JsonResponse
    {
        $sets = $this->lorcanService->getAllSets();
        return response()->json($sets);
    }

    public function show(string $setId): JsonResponse
    {
        $set = $this->lorcanService->getSet($setId);
        return response()->json($set);
    }

    public function cards(string $setId): JsonResponse
    {
        $cards = $this->lorcanService->getSetCards($setId);
        return response()->json($cards);
    }
}
