<?php

namespace App\Services;

use App\Models\Pet;
use Illuminate\Support\Facades\Http;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class PetService
{
    private string $baseUrl = 'https://petstore.swagger.io/v2';

    public function getPet(int $id): ?Pet
    {
        try {
            $response = Http::get("{$this->baseUrl}/pet/{$id}");
            if ($response->successful()) {
                return Pet::fromArray($response->json());
            }

            return null;
        } catch (\Exception $e) {
            throw new \Exception('Failed to fetch pet: ' . $e->getMessage());
        }
    }

    public function uploadImageToExternalServer($petId, $image, $additionalMetadata): array
    {
        try {
            $response = Http::attach('file', file_get_contents($image), 'image.jpg')
                ->post("{$this->baseUrl}/pet/{$petId}/uploadImage", ['additionalMetadata' => $additionalMetadata]);
    
            if ($response->successful()) {
                return $response->json();
            } else {
                throw new \Exception('Failed to upload image: ' . $response->body());
            }
        } catch (\Exception $e) {
            throw new \Exception('Error uploading image: ' . $e->getMessage());
        }
    }    

    public function createPet(array $data): Pet
    {
        try {
            $data['photoUrls'] = [];
            $response = Http::post("{$this->baseUrl}/pet", $data);
            if ($response->successful()) {
                return Pet::fromArray($response->json());
            }

            throw new \Exception($response->body());
        } catch (\Exception $e) {
            throw new \Exception('Failed to create pet: ' . $e->getMessage());
        }
    }

    public function updatePet(array $data): Pet
    {
        try {
            $response = Http::put("{$this->baseUrl}/pet", $data);

            if ($response->successful()) {
                return Pet::fromArray($response->json());
            }

            throw new \Exception($response->body());
        } catch (\Exception $e) {
            throw new \Exception('Failed to update pet: ' . $e->getMessage());
        }
    }

    public function deletePet(int $id): bool
    {
        try {
            $response = Http::delete("{$this->baseUrl}/pet/{$id}");

            if ($response->successful()) {
                return true;
            }

            throw new \Exception($response->body());
        } catch (\Exception $e) {
            throw new \Exception('Failed to delete pet: ' . $e->getMessage());
        }
    }

    public function getPetsByStatus(string $status, int $page = 1, int $perPage = 10): LengthAwarePaginator
    {
        try {
            $response = Http::get("{$this->baseUrl}/pet/findByStatus", ['status' => $status]);

            if ($response->successful()) {
                $petsData = $response->json();
                $pets = collect($petsData)->map(function ($petData) {
                    return Pet::fromArray($petData);
                });
                $totalItems = $pets->count();

                return new LengthAwarePaginator(
                    $pets->forPage($page, $perPage),
                    $totalItems,
                    $perPage,
                    $page
                );
            }

            throw new \Exception($response->body());
        } catch (\Exception $e) {
            throw new \Exception('Failed to fetch pets: ' . $e->getMessage());
        }
    }
}
