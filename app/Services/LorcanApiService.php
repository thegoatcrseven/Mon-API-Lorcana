<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use RuntimeException;

class LorcanApiService
{
    private string $storagePath;
    private const CACHE_TTL = 3600; // 1 heure

    public function __construct()
    {
        $this->storagePath = storage_path('app/lorcana/');
    }

    /**
     * Lit et décode un fichier JSON
     */
    private function readJsonFile(string $filename): array
    {
        $filePath = $this->storagePath . $filename;
        
        if (!file_exists($filePath)) {
            throw new RuntimeException("Le fichier {$filename} n'existe pas. Veuillez le télécharger depuis lorcanajson.org");
        }

        $content = file_get_contents($filePath);
        return json_decode($content, true) ?? [];
    }

    /**
     * Récupère toutes les cartes
     */
    public function getAllCards(): array
    {
        return Cache::remember('lorcana.all_cards', self::CACHE_TTL, function () {
            $data = $this->readJsonFile('allCards.json');
            return $data['cards'] ?? [];
        });
    }

    /**
     * Récupère une carte par son ID
     */
    public function getCard(string $id): ?array
    {
        return collect($this->getAllCards())->firstWhere('id', (int)$id);
    }

    /**
     * Récupère les cartes d'un set spécifique
     */
    public function getSetCards(string $setId): array
    {
        return Cache::remember("lorcana.set_cards.{$setId}", self::CACHE_TTL, function () use ($setId) {
            $data = $this->readJsonFile("setdata.{$setId}.json");
            return $data['cards'] ?? [];
        });
    }

    /**
     * Récupère tous les sets
     */
    public function getAllSets(): array
    {
        return Cache::remember('lorcana.all_sets', self::CACHE_TTL, function () {
            $data = $this->readJsonFile('allCards.json');
            return $data['sets'] ?? [];
        });
    }

    /**
     * Récupère un set par son ID
     */
    public function getSet(string $id): ?array
    {
        return $this->getAllSets()[$id] ?? null;
    }
}
