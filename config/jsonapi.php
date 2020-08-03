<?php

return [
    'resources' => [
        'authors' => [
            'allowedSorts' => [
                'name'
            ]
        ],
        'books' => [
            'relationships' => [
                [
                    'type' => 'authors',
                    'method' => 'authors'
                ]
            ],
            'allowedSorts' => [
                'title', 'year'
            ],
            'allowedIncludes' => [
                'authors'
            ]
        ],
    ]
];
