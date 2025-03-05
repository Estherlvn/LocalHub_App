<?php

namespace App\Service;

use Symfony\Contracts\HttpClient\HttpClientInterface;

class GeolocationService
{
    private HttpClientInterface $httpClient;

    public function __construct(HttpClientInterface $httpClient)
    {
        $this->httpClient = $httpClient;
    }

    /**
     * Récupère les coordonnées GPS d'une adresse postale via l'API Nominatim.
     */
    public function getCoordinates(string $address): ?array
    {
        if (empty($address)) {
            return null;
        }

        $url = 'https://nominatim.openstreetmap.org/search?q=' . urlencode($address) . '&format=json';

        try {
            $response = $this->httpClient->request('GET', $url);
            $data = $response->toArray();

            if (!empty($data)) {
                return [
                    'latitude' => $data[0]['lat'],
                    'longitude' => $data[0]['lon']
                ];
            }
        } catch (\Exception $e) {
            return null;
        }

        return null;
    }
}
