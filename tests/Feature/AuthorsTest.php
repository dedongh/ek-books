<?php

namespace Tests\Feature;

use App\Model\Author;
use App\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Passport\Passport;
use Tests\TestCase;

class AuthorsTest extends TestCase
{
    use DatabaseMigrations;

    /**
     * @test
     *
     * */
    public function it_returns_an_author_as_a_resource_object()
    {
        $author = factory(Author::class)->create();
        $user = factory(User::class)->create();

        //login user
        Passport::actingAs($user);
        // run code
        $this->getJson('/api/v1/authors/1', [
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
        ])
            // make assertions
            ->assertStatus(200)
            ->assertJson([
                "data" => [
                    'id' => '1',
                    'type' => 'author',
                    'attributes' => [
                        'name' => $author->name,
                    ]
                ]
            ]);
    }

    /**
     * @test
     *
     */
    public function
    it_returns_all_authors_as_a_collection_of_resource_objects()
    {
        $user = factory(User::class)->create();
        Passport::actingAs($user);
        $author = factory(Author::class, 3)->create();

        $this->getJson('/api/v1/authors')
            ->assertStatus(200);
    }

    /**
     * @test
     *
     */
    public function it_can_create_an_author_from_a_resource_object()
    {
        $user = factory(User::class)->create();
        Passport::actingAs($user);
        $this->postJson('/api/v1/authors', [
            'name' => 'John Doe',
        ])->assertStatus(201)
            ->assertJson([
                "data" => [
                    'id' => '1',
                    'type' => 'author',
                    'attributes' => [
                        'name' => 'John Doe',
                    ]
                ]
            ]);

        // ensure that data was saved to db
        $this->assertDatabaseHas('authors', [
            'id' => 1,
            'name' => 'John Doe'
        ]);
    }

    /**
     * @test
     *
     */
    public function it_can_update_an_author_from_a_resource_object()
    {
        $user = factory(User::class)->create();
        Passport::actingAs($user);
        $author = factory(Author::class)->create();

        $this->patchJson('/api/v1/authors/1', [
            'data' => [
                'id' => '1',
                'type' => 'author',
                'attributes' => [
                    'name' => 'Jane Doe',
                ]
            ]
        ])->assertStatus(200)
            ->assertJson([
                "data" => [
                    'id' => '1',
                    'type' => 'author',
                    'attributes' => [
                        'name' => 'Jane Doe',
                    ]
                ]
            ]);
        $this->assertDatabaseHas('authors', [
            'id' => 1,
            'name' => 'Jane Doe',
        ]);
    }

    /**
     * @test
     *
     * */
    public function it_can_delete_an_author_through_a_delete_request()
    {
        $author = factory(Author::class)->create();
        $user = factory(User::class)->create();

        Passport::actingAs($user);

        $this->delete('/api/v1/authors/1', [], [
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
        ])
            ->assertStatus(204);

        $this->assertDatabaseMissing('authors', [
            'id' => 1,
            'name' => $author->name,
        ]);
    }

    /**
     * @test
     *
     */
    public function
    it_can_sort_authors_by_name_through_a_sort_query_parameter()
    {
        $user = factory(User::class)->create();
        Passport::actingAs($user);
        $authors = collect([
            'Bertram',
            'Claus',
            'Anna',
        ])->map(function ($name) {
            return factory(Author::class)->create([
                'name' => $name
            ]);
        });


        $this->get('/api/v1/authors?sort=name', [
            'accept' => 'application/json',
            'content-type' => 'application/json',
        ])->assertStatus(200)->assertJson([
            "data" => [
                [
                    "id" => '3',
                    "type" => "author",
                    "attributes" => [
                        'name' => 'Anna',
                    ]
                ],
                [
                    "id" => '1',
                    "type" => "author",
                    "attributes" => [
                        'name' => 'Bertram',
                    ]
                ],
                [
                    "id" => '2',
                    "type" => "author",
                    "attributes" => [
                        'name' => 'Claus',
                    ]
                ],

            ]
        ]);
    }

    /**
     * @test
     *
     */
    public function
    it_can_sort_authors_by_name_in_descending_order_through_a_sort_query_parameter()
    {
        $user = factory(User::class)->create();
        Passport::actingAs($user);
        $authors = collect([
            'Bertram',
            'Claus',
            'Anna',
        ])->map(function ($name) {
            return factory(Author::class)->create([
                'name' => $name
            ]);
        });


        $this->get('/api/v1/authors?sort=-name', [
            'accept' => 'application/json',
            'content-type' => 'application/json',
        ])->assertStatus(200)->assertJson([
            "data" => [
                [
                    "id" => '2',
                    "type" => "author",
                    "attributes" => [
                        'name' => 'Claus',
                    ]
                ],
                [
                    "id" => '1',
                    "type" => "author",
                    "attributes" => [
                        'name' => 'Bertram',
                    ]
                ],
                [
                    "id" => '3',
                    "type" => "author",
                    "attributes" => [
                        'name' => 'Anna',
                    ]
                ],

            ]
        ]);
    }

    /**
     * @test
     *
     */
    public function
    it_can_paginate_authors_through_a_page_query_parameter()
    {
        $user = factory(User::class)->create();
        Passport::actingAs($user);
        $authors = factory(Author::class, 10)->create();

        $this->getJson('/api/v1/authors?page[size]=5&page[number]=1', [
            'accept' => 'application/json',
            'content-type' => 'application/json',
        ])
            ->assertStatus(200)->assertJson([
                "data" => [
                    [
                        "id" => '1',
                        "type" => "author",
                        "attributes" => [
                            'name' => $authors[0]->name,
                        ]
                    ],
                    [
                        "id" => '2',
                        "type" => "author",
                        "attributes" => [
                            'name' => $authors[1]->name,
                        ]
                    ],
                    [
                        "id" => '3',
                        "type" => "author",
                        "attributes" => [
                            'name' => $authors[2]->name,
                        ]
                    ],
                    [
                        "id" => '4',
                        "type" => "author",
                        "attributes" => [
                            'name' => $authors[3]->name,
                        ]
                    ],
                    [
                        "id" => '5',
                        "type" => "author",
                        "attributes" => [
                            'name' => $authors[4]->name,
                        ]
                    ],
                ],
                'links' => [
                    'first' => route('authors.index', ['page[size]' => 5, 'page[number]' => 1]),
                    'last' => route('authors.index', ['page[size]' => 5, 'page[number]' => 2]),
                    'prev' => null,
                    'next' => route('authors.index', ['page[size]' => 5, 'page[number]' => 2]),
                ]
            ]);
    }


}
