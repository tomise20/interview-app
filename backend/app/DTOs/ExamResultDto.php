<?php

declare(strict_types=1);

namespace App\DTOs;

use Illuminate\Support\Collection;

class ExamResultDto {

    public string $university;
    public string $faculty;

    /**
     * @param Collection<SubjectDto>
     */
    public Collection $examResults;


    /**
     * @param Collection<ExtraPointDto>
     */
    public ?Collection $extraPoints;

    public static function fromRequest(array $data): self
    {
        $dto = new ExamResultDto();
        $dto->university = $data['valasztott-szak']['egyetem'];
        $dto->faculty = $data['valasztott-szak']['kar'];
        $dto->examResults = (new self)->convertArrayToCollection($data['erettsegi-eredmenyek'], 'subject');
        $dto->extraPoints = isset($data['tobbletpontok']) ? (new self)->convertArrayToCollection($data['tobbletpontok'], 'extraPoints') : null;

        return $dto;
    }

    /**
     * @return Collection<SubjectDto>
     */
    private function convertArrayToCollection(array $data, string $type): Collection
    {
        $collection = collect();

        foreach($data as $item) {
            if($type === 'subject') {
                $collection->push(SubjectDto::fromRequest($item));
            } else {
                $collection->push(ExtraPointDto::fromRequest($item));
            }
        }

        return $collection;
    }
}