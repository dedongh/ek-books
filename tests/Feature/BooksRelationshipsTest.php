<?php

namespace Tests\Feature;

use App\Model\Author;
use App\Model\Book;
use App\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Passport\Passport;
use Tests\TestCase;

class BooksRelationshipsTest extends TestCase
{
    use DatabaseMigrations;


    /**
     * @test
     */
    public function it_returns_a_relationship_to_authors_adhering_to_json_api_spec()
    {
        $book = factory(Book::class)->create();
        $authors = factory(Author::class, 3)->create();
        $book->authors()->sync($authors->pluck('id'));
        $user = factory(User::class)->create();
        Passport::actingAs($user);

        $this->getJson('/api/v1/books/1', [
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
        ])->assertStatus(200)
            ->assertJson([
                "data" => [
                    "id" => '1',
                    "type" => "books",
                    "attributes" => [
                        'title' => $book->title,
                        'description' => $book->description,
                        'year' => $book->year,
                    ],
                    "relationships" => [
                        'authors' => [
                            'data' => [
                                [
                                    'id' => $authors[0]->id,
                                    'type' => 'author'
                                ],
                                [
                                    //'id' => $authors->get(1)->id,
                                    'id' => $authors[1]->id,
                                    'type' => 'author'
                                ]
                            ]
                        ]
                    ]
                ]
            ]);
    }

    /**
     * @test
     *
     */
    public function
    a_relationship_link_to_authors_returns_all_related_authors_as_resource_id_ob
    ()
    {
        $book = factory(Book::class)->create();
        $authors = factory(Author::class, 3)->create();

        $book->authors()->sync($authors->pluck('id'));

        $user = factory(User::class)->create();
        Passport::actingAs($user);

        $this->getJson('/api/v1/books/1/relationships/authors', [
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
        ])->assertStatus(200)
            ->assertJson([
                "data" => [
                    [
                        'id' => '1',
                        'type' => 'author'
                    ],
                    [
                        'id' => '2',
                        'type' => 'author'
                    ],
                    [
                        'id' => '3',
                        'type' => 'author'
                    ]
                ]
            ]);
    }

    /**
     * @test
     *
     */
    public function
    it_can_modify_relationships_to_authors_and_add_new_relationships
    ()
    {
        $book = factory(Book::class)->create();
        $authors = factory(Author::class, 10)->create();
        $user = factory(User::class)->create();
        Passport::actingAs($user);

        $this->patchJson('/api/v1/books/1/relationships/authors', [
            "data" => [
                [
                    'id' => '5',
                    'type' => 'author'
                ],
                [
                    'id' => '6',
                    'type' => 'author'
                ],
            ]
        ])->assertStatus(204);

        $this->assertDatabaseHas('author_book', [
            'author_id' => 5,
            'book_id' => 1
        ])->assertDatabaseHas('author_book', [
            'author_id' => 6,
            'book_id' => 1
        ]);;
    }

    /**
     * @test
     * @watch
     */
    public function
    it_can_modify_relationships_to_authors_and_remove_relationships
    ()
    {
        $book = factory(Book::class)->create();
        $authors = factory(Author::class, 5)->create();
        $book->authors()->sync($authors->pluck('id'));

        $user = factory(User::class)->create();
        Passport::actingAs($user);

        $this->patchJson('/api/v1/books/1/relationships/authors', [
            "data" => [
                [
                    'id' => '1',
                    'type' => 'author'
                ],
                [
                    'id' => '2',
                    'type' => 'author'
                ],
                [
                    'id' => '5',
                    'type' => 'author'
                ],
            ]
        ])->assertStatus(204);

        $this->assertDatabaseHas('author_book', [
            'author_id' => 1,
            'book_id' => 1,
        ])->assertDatabaseHas('author_book', [
            'author_id' => 2,
            'book_id' => 1,
        ])->assertDatabaseHas('author_book', [
            'author_id' => 5,
            'book_id' => 1,
        ])->assertDatabaseMissing('author_book', [
            'author_id' => 3,
            'book_id' => 1,
        ])->assertDatabaseMissing('author_book', [
            'author_id' => 4,
            'book_id' => 1,
        ]);
    }

    /**
     * @test
     * @watch
     */
    public function
    it_can_remove_all_relationships_to_authors_with_an_empty_collection
    ()
    {
        $book = factory(Book::class)->create();
        $authors = factory(Author::class, 3)->create();
        $book->authors()->sync($authors->pluck('id'));
        $user = factory(User::class)->create();
        Passport::actingAs($user);

        $this->patchJson('/api/v1/books/1/relationships/authors', [
            "data" => []
        ])->assertStatus(204);

        $this->assertDatabaseMissing('author_book', [
            'author_id' => 1,
            'book_id' => 1,
        ])->assertDatabaseMissing('author_book', [
            'author_id' => 2,
            'book_id' => 1,
        ])->assertDatabaseMissing('author_book', [
            'author_id' => 3,
            'book_id' => 1,
        ]);
    }

    /**
     * @test
     * @watch
     */
    public function
    it_returns_a_404_not_found_when_trying_to_add_relationship_to_a_non_existing
    ()
    {
        $book = factory(Book::class)->create();
        $authors = factory(Author::class, 5)->create();
        $user = factory(User::class)->create();
        Passport::actingAs($user);

        $this->patchJson('/api/v1/books/1/relationships/authors', [
            'data' => [
                [
                    'id' => '5',
                    'type' => 'author',
                ],
                [
                    'id' => '6',
                    'type' => 'author',
                ]
            ]
        ], [
            'accept' => 'application/json',
            'content-type' => 'application/json',
        ])->assertStatus(404)
            ->assertJson([
                'message' => 'Resource not found',
            ]);
    }

    /**
     * @test
     *
     */
    public function
    it_validates_that_the_id_member_is_given_when_updating_a_relationship
    ()
    {
        $book = factory(Book::class)->create();
        $authors = factory(Author::class, 5)->create();
        $user = factory(User::class)->create();
        Passport::actingAs($user);

        $this->patchJson('/api/v1/books/1/relationships/authors', [
            'data' => [
                [
                    'type' => 'author',
                ],
            ]
        ])->assertStatus(422)
            ->assertJson([
                'message' => 'The given data was invalid.',
                'errors' => [
                    'data.0.id' => [
                        'The data.0.id field is required.'
                    ]
                ]
            ]);
    }

    /**
     * @test
     *
     */
    public function
    it_validates_that_the_id_member_is_a_string_when_updating_a_relationship
    ()
    {
        $book = factory(Book::class)->create();
        $authors = factory(Author::class, 5)->create();
        $user = factory(User::class)->create();
        Passport::actingAs($user);

        $this->patchJson('/api/v1/books/1/relationships/authors', [
            'data' => [
                [
                    'id' => 5,
                    'type' => 'author',
                ],
            ]
        ])->assertStatus(422)
            ->assertJson([
                'message' => 'The given data was invalid.',
                'errors' => [
                    'data.0.id' => [
                        'The data.0.id must be a string.'
                    ]
                ]
            ]);
    }

    /**
     * @test
     *
     */
    public function
    it_validates_that_the_type_member_has_a_value_when_updating_a_relationship
    ()
    {
        $book = factory(Book::class)->create();
        $authors = factory(Author::class, 5)->create();

        $user = factory(User::class)->create();
        Passport::actingAs($user);

        $this->patchJson('/api/v1/books/1/relationships/authors', [
            'data' => [
                [
                    'id' => '5',
                    'type' => 'books',
                ],
            ]
        ])->assertStatus(422)
            ->assertJson([
                'message' => 'The given data was invalid.',
                'errors' => [
                    'data.0.type' => [
                        'The selected data.0.type is invalid.'
                    ]
                ]
            ]);

    }

}
