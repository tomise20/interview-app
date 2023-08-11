<?php

namespace App\Http\Controllers;

use App\DTOs\ExamResultDto;
use App\Http\Requests\CalculationRequest;
use App\Interfaces\CalculationService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CalculationController extends Controller
{

    function __construct(private readonly CalculationService $calculationService)
    {
    }

    public function index(CalculationRequest $request): JsonResponse
    {

        try {
            $points = $this->calculationService->calculatePoints(ExamResultDto::fromRequest($request->all()));
        } catch (\Exception $ex) {
            return response()->json([
                'errors' => $ex->getMessage(),
                'isSuccess' => false
            ], Response::HTTP_BAD_REQUEST);
        }
        
        return response()->json([
            'points' => $points,
            'isSuccess' => true
        ]);
    }
}
