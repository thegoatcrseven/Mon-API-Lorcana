<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Card;
use App\Models\Wishlist;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class WishlistController extends Controller
{
    public function index(): JsonResponse
    {
        try {
            $wishlistItems = Auth::user()->wishlistItems()->with('card.set')->get();
            return response()->json($wishlistItems);
        } catch (\Exception $e) {
            Log::error('Error fetching wishlist: ' . $e->getMessage());
            return response()->json(['message' => 'Error fetching wishlist'], 500);
        }
    }

    public function store(Request $request): JsonResponse
    {
        try {
            $validated = $request->validate([
                'card_id' => 'required|exists:cards,id',
                'quantity' => 'required|integer|min:1',
                'priority' => 'required|integer|min:1|max:5',
            ]);

            $wishlistItem = Wishlist::updateOrCreate(
                [
                    'user_id' => Auth::id(),
                    'card_id' => $validated['card_id'],
                ],
                [
                    'quantity' => $validated['quantity'],
                    'priority' => $validated['priority'],
                ]
            );

            return response()->json([
                'message' => 'Card added to wishlist',
                'data' => $wishlistItem->load('card.set'),
            ]);
        } catch (\Exception $e) {
            Log::error('Error adding card to wishlist: ' . $e->getMessage());
            return response()->json(['message' => 'Error adding card to wishlist'], 500);
        }
    }

    public function update(Request $request, int $cardId): JsonResponse
    {
        try {
            $validated = $request->validate([
                'quantity' => 'required|integer|min:0',
                'priority' => 'required|integer|min:1|max:5',
            ]);

            $wishlistItem = Auth::user()->wishlistItems()->where('card_id', $cardId)->firstOrFail();
            
            if ($validated['quantity'] === 0) {
                $wishlistItem->delete();
                return response()->json(['message' => 'Card removed from wishlist']);
            }

            $wishlistItem->update([
                'quantity' => $validated['quantity'],
                'priority' => $validated['priority'],
            ]);

            return response()->json([
                'message' => 'Wishlist item updated',
                'data' => $wishlistItem->load('card.set'),
            ]);
        } catch (\Exception $e) {
            Log::error('Error updating wishlist item: ' . $e->getMessage());
            return response()->json(['message' => 'Error updating wishlist item'], 500);
        }
    }

    public function destroy(int $cardId): JsonResponse
    {
        try {
            $wishlistItem = Auth::user()->wishlistItems()->where('card_id', $cardId)->firstOrFail();
            $wishlistItem->delete();

            return response()->json(['message' => 'Card removed from wishlist']);
        } catch (\Exception $e) {
            Log::error('Error removing card from wishlist: ' . $e->getMessage());
            return response()->json(['message' => 'Error removing card from wishlist'], 500);
        }
    }
}
