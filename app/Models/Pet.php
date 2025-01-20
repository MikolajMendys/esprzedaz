<?php

namespace App\Models;

class Pet
{
    public int $id;
    public string $name;
    public array $category;
    public array $photoUrls;
    public array $tags;
    public string $status;

    public static function fromArray(array $data): self
    {
        $pet = new self();
        $pet->id = $data['id'] ?? 0;
        $pet->name = $data['name'] ?? '';
        $pet->category = $data['category'] ?? [];
        $pet->photoUrls = $data['photoUrls'] ?? [];
        $pet->tags = $data['tags'] ?? [];
        $pet->status = $data['status'] ?? 'available';
        return $pet;
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'category' => $this->category,
            'photoUrls' => $this->photoUrls,
            'tags' => $this->tags,
            'status' => $this->status,
        ];
    }
}
