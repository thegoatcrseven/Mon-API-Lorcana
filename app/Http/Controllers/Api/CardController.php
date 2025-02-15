<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\LorcanApiService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CardController extends Controller
{
    private $lorcanService;

    public function __construct(LorcanApiService $lorcanService)
    {
        $this->lorcanService = $lorcanService;
    }

    public function index(): JsonResponse
    {
        $cards = $this->lorcanService->getAllCards();
        return response()->json($cards);
    }

    public function show(string $cardId): JsonResponse
    {
        $card = $this->lorcanService->getCard($cardId);
        return response()->json($card);
    }

    public function search(Request $request)
    {
        // Cette méthode devra être implémentée en fonction des capacités de l'API Lorcan
        // Pour l'instant, nous récupérons toutes les cartes et filtrons côté serveur
        $query = $request->get('q');
        $cards = $this->lorcanService->getAllCards();
        
        $filteredCards = collect($cards)->filter(function ($card) use ($query) {
            return str_contains(strtolower($card['name']), strtolower($query));
        })->values();

        return response()->json($filteredCards);
    }
}
