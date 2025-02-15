<?php

namespace App\Services;

use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;

class LorcanaApiService
{
    private $client;
    private $baseUrl = 'https://lorcanajson.org/files/current/en';

    public function __construct()
    {
        $this->client = new Client();
    }

    public function fetchAllCards()
    {
        try {
            $response = $this->client->get("{$this->baseUrl}/allCards.json");
            return json_decode($response->getBody(), true);
        } catch (\Exception $e) {
            Log::error('Error fetching data from Lorcana JSON: ' . $e->getMessage());
            throw new \Exception("Failed to fetch Lorcana data: " . $e->getMessage());
        }
    }
}
