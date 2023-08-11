<?php

declare(strict_types=1);

namespace App\Services;

use App\DTOs\ExamResultDto;
use App\DTOs\SubjectDto;
use App\Exceptions\CalculationException;
use App\Interfaces\CalculationService;
use Illuminate\Support\Collection;

class CalculationServiceImpl implements CalculationService {
    const REQUIRED_SUBJECTS = ['magyar nyelv és irodalom', 'történelem', 'matematika'];
    const MINIMUM_PERCENT_TO_SUCCESS_EXAM = 20;

    public function calculatePoints(ExamResultDto $examResultDto): int
    {
        if(!$this->checkRequiredSubjects($examResultDto->examResults)) {
            Throw new CalculationException('hiba, nem lehetséges a pontszámítás a kötelező érettségi tárgyak hiánya miatt');
        }

        $failedSubject = $this->checkRequiredExamResults($examResultDto->examResults);
        
        if(count($failedSubject) > 0) {
            Throw new CalculationException("hiba, nem lehetséges a pontszámítás mert a következő tárgyak nem érték el 20%-ot, " . implode(',', $failedSubject));
        }

        if(!$this->checkRequiredSubjectIsRaised($examResultDto->examResults, $examResultDto->university)) {
            Throw new CalculationException('hiba, nem lehetséges a pontszámítás mert a kötelező vizsgatrágy szintje nem megfelelő!');
        }


        $basePoints = $this->calculateBasePoints($examResultDto->examResults, $examResultDto->university);
        $extraPoints = $this->calculateExtraPoints($examResultDto);


        return $basePoints + $extraPoints;

    }

    /**
     * @var $subject Collection<SubjectDto>
     */
    private function checkRequiredSubjects(Collection &$subjects): bool
    {
        return count(array_intersect_key(array_unique($subjects->pluck('name')->toArray()), self::REQUIRED_SUBJECTS)) === count(self::REQUIRED_SUBJECTS);
    }

    /**
     * @var $subject Collection<SubjectDto>
     */
    private function checkRequiredSubjectIsRaised(Collection &$subjects, string $university): bool
    {
        $universityConfig = config("university.$university");

        if(!$universityConfig['required']['is_raised']) {
            return true;
        }

        $requiredSubject = $subjects->first(fn($subject) => $subject->name === $universityConfig['required']['name']);

        return $requiredSubject->type === 'emelt' ? true : false;
    }

    /**
     * @var $subject Collection<SubjectDto>
     */
    private function checkRequiredExamResults(Collection $subjects): array
    {
        $failedExams = [];

        /**
         * @var $subject SubjectDto 
         */
        foreach($subjects as $subject) {
            // split the string along the percentage sign
            $result = cutPercentageSign($subject->result);
            
            if($result < self::MINIMUM_PERCENT_TO_SUCCESS_EXAM) {
                $failedExams[] = $subject->name;
            }
        }

        return $failedExams;
    }

    /**
     * @var $subjects Collection<SubjectDto>
     */
    private function calculateBasePoints(Collection $subjects, string $university): int
    {
        $points = 0;
        $universityConfig = config("university.$university");
        
        $requiredSubject = $subjects->first(fn($subject) => $subject->name === $universityConfig['required']['name']);
        $points += cutPercentageSign($requiredSubject->result);

        $highestOptinalSubject = $subjects->filter(fn(SubjectDto $subject) => in_array($subject->name, $universityConfig['optional']))->max('result');
        $points += cutPercentageSign($highestOptinalSubject);

        return $points * 2;
    }

    private function calculateExtraPoints(ExamResultDto &$dto): int
    {
        $raisedSubjects = $dto->examResults->filter(fn(SubjectDto $subject) => $subject->type === 'emelt')->count();
        $langExamsPoints = 0;
        $totalPoints = $raisedSubjects * 50;

        if(isset($dto->extraPoints)) {
            $dto->extraPoints->groupBy('lang')->each(function(Collection $group) use(&$langExamsPoints) {
                $type = $group->max('type');
                if($type === 'B2') {
                    $langExamsPoints += 28;
                } else if($type === 'C1'){
                    $langExamsPoints += 40;
                }
            });
    
            $totalPoints += $langExamsPoints;
        }

        return $totalPoints < 100 ? $totalPoints : 100;
    }
}