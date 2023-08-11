<?php

declare(strict_types=1);

namespace App\DTOs;

class SubjectDto {

    public string $name;
    public string $type;
    public string $result;

    public static function fromRequest(array $data): self
    {
        $dto = new SubjectDto();
        $dto->name = $data['nev'];
        $dto->type = $data['tipus'];
        $dto->result = $data['eredmeny'];

        return $dto;
    }
}