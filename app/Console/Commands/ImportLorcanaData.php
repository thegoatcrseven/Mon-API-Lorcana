<?php

namespace App\Console\Commands;

use App\Models\Card;
use App\Models\Set;
use App\Services\LorcanaApiService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class ImportLorcanaData extends Command
{
    protected $signature = 'lorcana:import';
    protected $description = 'Import Lorcana cards and sets data from lorcanajson.org';

    private $lorcanaApiService;

    public function __construct(LorcanaApiService $lorcanaApiService)
    {
        parent::__construct();
        $this->lorcanaApiService = $lorcanaApiService;
    }

    public function handle()
    {
        $this->info('Starting Lorcana data import...');
        
        try {
            $data = $this->lorcanaApiService->fetchAllCards();
            
            // Process sets first
            foreach ($data['sets'] as $setCode => $setData) {
                $this->info("Processing set: {$setData['name']}");
                $this->info("Set data: " . json_encode($setData));
                
                $set = Set::updateOrCreate(
                    ['code' => (string)$setCode], // Convert numeric set code to string
                    [
                        'name' => $setData['name'],
                        'type' => $setData['type'] ?? 'expansion',
                        'release_date' => $setData['releaseDate'] ? date('Y-m-d', strtotime($setData['releaseDate'])) : null,
                        'prerelease_date' => $setData['prereleaseDate'] ? date('Y-m-d', strtotime($setData['prereleaseDate'])) : null,
                        'has_all_cards' => $setData['hasAllCards']
                    ]
                );
            }
            
            // Process all cards
            foreach ($data['cards'] as $cardData) {
                $this->info("Processing card: {$cardData['fullName']}");
                
                $set = Set::where('code', (string)$cardData['setCode'])->first(); // Convert numeric set code to string
                
                if (!$set) {
                    $this->warn("Set not found for card: {$cardData['fullName']}");
                    continue;
                }
                
                $card = Card::updateOrCreate(
                    [
                        'set_id' => $set->id,
                        'card_id' => $cardData['id']
                    ],
                    [
                        'name' => $cardData['name'],
                        'full_name' => $cardData['fullName'],
                        'type' => $cardData['type'],
                        'color' => $cardData['color'] ?? '',
                        'rarity' => $cardData['rarity'],
                        'cost' => $cardData['cost'],
                        'strength' => $cardData['strength'] ?? null,
                        'willpower' => $cardData['willpower'] ?? null,
                        'lore' => $cardData['lore'] ?? null,
                        'inkwell' => $cardData['inkwell'] ?? false,
                        'abilities' => json_encode($cardData['abilities'] ?? []),
                        'flavor_text' => $cardData['flavorText'] ?? null,
                        'full_text' => $cardData['fullText'] ?? '',
                        'story' => $cardData['story'],
                        'image_url' => $cardData['images']['full'] ?? null,
                        'thumbnail_url' => $cardData['images']['thumbnail'] ?? null,
                        'subtypes' => json_encode($cardData['subtypes'] ?? []),
                        'artists' => json_encode($cardData['artists'] ?? []),
                        'version' => $cardData['version'] ?? null
                    ]
                );
            }
            
            $this->info('Import completed successfully!');
            
        } catch (\Exception $e) {
            $this->error('Import failed: ' . $e->getMessage());
            return 1;
        }
        
        return 0;
    }
}
