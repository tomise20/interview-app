<?php

return [
    'ELTE' => [
        'required' => [
            'name' => 'matematika',
            'is_raised' => false
        ],
        'optional' => [
            'biológia',
            'fizika',
            'informatika',
            'kémia'
        ]
    ],
    'PPKE' => [
        'required' => [
            'name' => 'angol nyelv',
            'is_raised' => true
        ],
        'optional' => [
            'francia',
            'német',
            'olasz',
            'orosz',
            'spanyol'
        ]
    ]
];