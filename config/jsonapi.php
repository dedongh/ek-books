<?php

use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\Filters\Filter;

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
            'allowedFilters' => [],
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
                ],
                [
                    'type' => 'comments',
                    'method' => 'comments',
                ]
            ],
            'allowedSorts' => [
                'title', 'year'
            ],
            'allowedIncludes' => [
                'authors','comments'
            ],
            'allowedFilters' => [],
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
            'allowedSorts' => [
                'name','email'
            ],
            'allowedIncludes' => [
                'comments'
            ],
            'allowedFilters' => [
                AllowedFilter::exact('role'),
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
            'allowedSorts' => [
                'created_at'
            ],
            'allowedIncludes' => [
                'books', 'users',
            ],
            'allowedFilters' => [],
            'validationRules' => [
                'create' => [
                    'data.attributes.message' => 'required|string',
                ],
                'update' => [
                    'data.attributes.message' => 'sometimes|required|string',
                ]
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
