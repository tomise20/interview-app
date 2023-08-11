<?php

namespace Tests\Feature;

// use Illuminate\Foundation\Testing\RefreshDatabase;

use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;

class CalculatorTest extends TestCase
{
    /**
     * @test
     */
    public function missing_required_university(): void
    {
        $this->withoutExceptionHandling();

        $response = $this->post('/api/calculation');

        $response->assertJsonValidationErrorFor('valasztott-szak.egyetem');
    }

    /**
     * @test
     */
    public function missing_required_university_faculty(): void
    {
        //$this->withoutExceptionHandling();

        $response = $this->post('/api/calculation');

        $response->assertJsonValidationErrorFor('valasztott-szak.kar');
    }

    /**
     * @test
     */
    public function too_few_required_exam_subject(): void
    {
        //$this->withoutExceptionHandling();

        $response = $this->post('/api/calculation');

        $response->assertJsonValidationErrorFor('erettsegi-eredmenyek');
    }

    /**
     * @test
     */
    public function missing_required_subject(): void
    {
        $response = $this->post('/api/calculation', [
            'valasztott-szak' => [
                'egyetem' => 'ELTE',
                'kar' => 'IK',
                'szak' => 'Programtervező informatikus',
            ],
            'erettsegi-eredmenyek' => [
                [
                    'nev' => 'magyar nyelv és irodalom',
                    'tipus' => 'közép',
                    'eredmeny' => '15%',
                ],
                [
                    'nev' => 'történelem',
                    'tipus' => 'közép',
                    'eredmeny' => '15%',
                ],
                [
                    'nev' => 'történelem',
                    'tipus' => 'közép',
                    'eredmeny' => '15%',
                ],
                [
                    'nev' => 'angol nyelv',
                    'tipus' => 'közép',
                    'eredmeny' => '94%',
                ],
                [
                    'nev' => 'informatika',
                    'tipus' => 'közép',
                    'eredmeny' => '95%',
                ],
            ],
        ]);

        $response->assertExactJson(['errors' => 'hiba, nem lehetséges a pontszámítás a kötelező érettségi tárgyak hiánya miatt']);
    }

    /**
     * @test
     */
    public function too_low_exam_result(): void
    {
        $this->withoutExceptionHandling();

        $response = $this->post('/api/calculation', [
            'valasztott-szak' => [
                'egyetem' => 'ELTE',
                'kar' => 'IK',
                'szak' => 'Programtervező informatikus',
            ],
            'erettsegi-eredmenyek' => [
                [
                    'nev' => 'magyar nyelv és irodalom',
                    'tipus' => 'közép',
                    'eredmeny' => '15%',
                ],
                [
                    'nev' => 'történelem',
                    'tipus' => 'közép',
                    'eredmeny' => '80%',
                ],
                [
                    'nev' => 'matematika',
                    'tipus' => 'emelt',
                    'eredmeny' => '90%',
                ],
                [
                    'nev' => 'angol nyelv',
                    'tipus' => 'közép',
                    'eredmeny' => '94%',
                ],
                [
                    'nev' => 'informatika',
                    'tipus' => 'közép',
                    'eredmeny' => '95%',
                ],
            ],
        ]);

        $response->assertExactJson(['errors' => 'hiba, nem lehetséges a pontszámítás mert a következő tárgyak nem érték el 20%-ot, magyar nyelv és irodalom']);
    }

    /**
     * @test
     */
    public function exam_is_not_raised(): void {
        $response = $this->post('/api/calculation', [
            'valasztott-szak' => [
                'egyetem' => 'PPKE',
                'kar' => 'BTK',
                'szak' => 'Anglisztika',
            ],
            'erettsegi-eredmenyek' => [
                [
                    'nev' => 'magyar nyelv és irodalom',
                    'tipus' => 'közép',
                    'eredmeny' => '70%',
                ],
                [
                    'nev' => 'történelem',
                    'tipus' => 'közép',
                    'eredmeny' => '80%',
                ],
                [
                    'nev' => 'matematika',
                    'tipus' => 'emelt',
                    'eredmeny' => '90%',
                ],
                [
                    'nev' => 'angol nyelv',
                    'tipus' => 'közép',
                    'eredmeny' => '94%',
                ],
                [
                    'nev' => 'francia',
                    'tipus' => 'közép',
                    'eredmeny' => '95%',
                ],
            ]
        ]);

        $response->assertExactJson(['errors' => 'hiba, nem lehetséges a pontszámítás mert a kötelező vizsgatrágy szintje nem megfelelő!']);
    }

    /**
     * @test
     */
    public function success_calculation(): void {

        //$this->withoutExceptionHandling();

        $response = $this->post('/api/calculation', [
            'valasztott-szak' => [
                'egyetem' => 'ELTE',
                'kar' => 'IK',
                'szak' => 'Programtervező informatikus',
            ],
            'erettsegi-eredmenyek' => [
                [
                    'nev' => 'magyar nyelv és irodalom',
                    'tipus' => 'közép',
                    'eredmeny' => '70%',
                ],
                [
                    'nev' => 'történelem',
                    'tipus' => 'közép',
                    'eredmeny' => '80%',
                ],
                [
                    'nev' => 'matematika',
                    'tipus' => 'emelt',
                    'eredmeny' => '90%',
                ],
                [
                    'nev' => 'angol nyelv',
                    'tipus' => 'közép',
                    'eredmeny' => '94%',
                ],
                [
                    'nev' => 'informatika',
                    'tipus' => 'közép',
                    'eredmeny' => '95%',
                ],
            ],
            'tobbletpontok' => [
                [
                    'kategoria' => 'Nyelvvizsga',
                    'tipus' => 'B2',
                    'nyelv' => 'angol',
                ],
                [
                    'kategoria' => 'Nyelvvizsga',
                    'tipus' => 'C1',
                    'nyelv' => 'angol',
                ],
                [
                    'kategoria' => 'Nyelvvizsga',
                    'tipus' => 'C1',
                    'nyelv' => 'német',
                ],
            ],
        ]);

        $response->assertExactJson(['points' => 470, 'isSuccess' => true]);
    }
}
