<?php

return [
    'resources' => [
        'authors' => [
            'allowedSorts' => [
                'name'
            ],
            'relationships' => [
                [
                    'type' => 'books',
                    'method' => 'books'
                ]
            ],
            'allowedIncludes' => [
                'books'
            ],
            'validationRules' => [
                'create' => [
                    'data.attributes.name' => 'required|string',
                    'name' => 'required|string',
                ],
                'update' => [
                    'data.attributes.name' => 'sometimes|required|string',
                    'name' => 'sometimes|required|string',
                ]
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
            ],
            'validationRules' => [
                'create' => [
                    'data.attributes.name' => 'required|string',
                    'name' => 'required|string',
                    'data.attributes.title' => 'required|string',
                    'title' => 'required|string',
                    'data.attributes.description' => 'required|string',
                    'description' => 'required|string',
                    'data.attributes.year' => 'required|string',
                    'year' => 'required|string',
                ],
                'update' => [
                    'data.attributes.name' => 'sometimes|required|string',
                    'name' => 'sometimes|required|string',
                    'data.attributes.title' => 'sometimes|required|string',
                    'title' => 'sometimes|required|string',
                    'data.attributes.description' => 'sometimes|required|string',
                    'description' => 'sometimes|required|string',
                    'data.attributes.year' => 'sometimes|required|string',
                    'year' => 'sometimes|required|string',
                ]
            ]
        ],

        'users' => [
            'allowedSorts' => [],
            'allowedIncludes' => [
                'comments'
            ],
            'validationRules' => [
                'create' => [],
                'update' => []
            ],
            'relationships' => [
                [
                    'type' => 'comments',
                    'method' => 'comments',
                ],
            ],

        ],

        'comments' => [
            'allowedSorts' => [],
            'allowedIncludes' => [
                'books',
                'users',
            ],
            'validationRules' => [
                'create' => [],
                'update' => []
            ],
            'relationships' => [
                [
                    'type' => 'books',
                    'method' => 'books',
                ],
                [
                    'type' => 'users',
                    'method' => 'users',
                ],
            ],
        ]


    ]
];
