<?php

declare(strict_types=1);

namespace App\DTOs;

class ExtraPointDto {

    public string $category;
    public string $type;
    public string $lang;

    public static function fromRequest(array $data): self
    {
        $dto = new ExtraPointDto();
        $dto->category = $data['kategoria'];
        $dto->type = $data['tipus'];
        $dto->lang = $data['nyelv'];

        return $dto;
    }
}