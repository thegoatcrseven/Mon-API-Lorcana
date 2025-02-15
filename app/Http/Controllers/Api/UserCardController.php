<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Card;
use App\Models\UserCard;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class UserCardController extends Controller
{
    public function index(): JsonResponse
    {
        try {
            $userCards = Auth::user()->userCards()->with('card.set')->get();
            return response()->json($userCards);
        } catch (\Exception $e) {
            Log::error('Error fetching user cards: ' . $e->getMessage());
            return response()->json(['message' => 'Error fetching user cards'], 500);
        }
    }

    public function store(Request $request): JsonResponse
    {
        try {
            $validated = $request->validate([
                'card_id' => 'required|exists:cards,id',
                'quantity' => 'required|integer|min:1',
            ]);

            $userCard = UserCard::updateOrCreate(
                [
                    'user_id' => Auth::id(),
                    'card_id' => $validated['card_id'],
                ],
                [
                    'quantity' => $validated['quantity'],
                ]
            );

            return response()->json([
                'message' => 'Card added to collection',
                'data' => $userCard->load('card.set'),
            ]);
        } catch (\Exception $e) {
            Log::error('Error adding card to collection: ' . $e->getMessage());
            return response()->json(['message' => 'Error adding card to collection'], 500);
        }
    }

    public function update(Request $request, int $cardId): JsonResponse
    {
        try {
            $validated = $request->validate([
                'quantity' => 'required|integer|min:0',
            ]);

            $userCard = Auth::user()->userCards()->where('card_id', $cardId)->firstOrFail();
            
            if ($validated['quantity'] === 0) {
                $userCard->delete();
                return response()->json(['message' => 'Card removed from collection']);
            }

            $userCard->update(['quantity' => $validated['quantity']]);

            return response()->json([
                'message' => 'Card quantity updated',
                'data' => $userCard->load('card.set'),
            ]);
        } catch (\Exception $e) {
            Log::error('Error updating card quantity: ' . $e->getMessage());
            return response()->json(['message' => 'Error updating card quantity'], 500);
        }
    }

    public function destroy(int $cardId): JsonResponse
    {
        try {
            $userCard = Auth::user()->userCards()->where('card_id', $cardId)->firstOrFail();
            $userCard->delete();

            return response()->json(['message' => 'Card removed from collection']);
        } catch (\Exception $e) {
            Log::error('Error removing card from collection: ' . $e->getMessage());
            return response()->json(['message' => 'Error removing card from collection'], 500);
        }
    }
}
