<?php

declare(strict_types=1);

namespace App\Interfaces;

use App\DTOs\ExamResultDto;

interface CalculationService {
    public function calculatePoints(ExamResultDto $examResultDto): int;
}