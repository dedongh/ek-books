<?php

namespace Tests\Feature;


use App\Model\Book;
use App\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Passport\Passport;
use Tests\TestCase;

class BooksTest extends TestCase
{
    use DatabaseMigrations;

    /**
     * @test
     */
    public function it_returns_a_book_as_a_resource_object()
    {
        $book = factory(Book::class)->create();
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
                        //'created_at' => $book->created_at->toJSON(),
                        //'updated_at' => $book->updated_at->toJSON(),
                    ]
                ]
            ]);
    }

    /**
     * @test
     * @watch
     */
    public function
    it_returns_all_books_as_a_collection_of_resource_objects()
    {
        $user = factory(User::class)->create();
        Passport::actingAs($user);
        $books = factory(Book::class, 3)->create();

        $this->getJson('/api/v1/books', [
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
        ])->assertStatus(200)
            ->assertJson([
                "data" => [
                    [
                        "id" => '1',
                        "type" => "books",
                        "attributes" => [
                            'title' => $books[0]->title,
                            'description' => $books[0]->description,
                            'year' => $books[0]->year,
                        ]
                    ],
                    [
                        "id" => '2',
                        "type" => "books",
                        "attributes" => [
                            'title' => $books[1]->title,
                            'description' => $books[1]->description,
                            'year' => $books[1]->year,
                        ]
                    ],
                    [
                        "id" => '3',
                        "type" => "books",
                        "attributes" => [
                            'title' => $books[2]->title,
                            'description' => $books[2]->description,
                            'year' => $books[2]->year,
                        ]
                    ],

                ]
            ]);
    }

    /**
     * @test
     */
    public function it_can_create_a_book_from_a_resource_object()
    {

        $user = factory(User::class)->create();
        Passport::actingAs($user);
        $this->postJson('/api/v1/books', [
            'title' => 'Building an API with Laravel',
            'description' => 'A book about API development',
            'year' => '2020'
        ])->assertStatus(201)
            ->assertJson([
                "data" => [
                    'id' => '1',
                    'type' => 'books',
                    'attributes' => [
                        'title' => 'Building an API with Laravel',
                        'description' => 'A book about API development',
                        'year' => '2020'
                    ]
                ]
            ]);

        // ensure that data was saved to db
        $this->assertDatabaseHas('books', [
            'id' => 1,
            'title' => 'Building an API with Laravel',
            'description' => 'A book about API development',
            'year' => '2020'
        ]);
    }

    /**
     * @test
     */
    public function it_can_update_an_book_from_a_resource_object()
    {
        $user = factory(User::class)->create();
        Passport::actingAs($user);
        $book = factory(Book::class)->create();

        $this->patchJson('/api/v1/books', [
            'title' => 'Building an API with Laravel',
            'description' => 'A book about API development',
            'year' => '2020'
        ])->assertStatus(200)
            ->assertJson([
                "data" => [
                    'id' => '1',
                    'type' => 'books',
                    'attributes' => [
                        'title' => 'Building an API with Laravel',
                        'description' => 'A book about API development',
                        'year' => '2020'
                    ]
                ]
            ]);

        $this->assertDatabaseHas('books', [
            'id' => 1,
            'title' => 'Building an API with Laravel',
            'description' => 'A book about API development',
            'year' => '2019',
        ]);
    }

    /**
     * @test
     */
    public function it_can_delete_an_book_through_a_delete_request()
    {
    }

    /**
     * @test
     */
    public function
    it_can_sort_books_by_title_through_a_sort_query_parameter()
    {
        $user = factory(User::class)->create();
        Passport::actingAs($user);
        $books = collect([
            'Building an API with Laravel',
            'Classes are our blueprints',
            'Adhering to the JSON:API Specification',
        ])->map(function ($title) {
            return factory(Book::class)->create([
                'title' => $title
            ]);
        });

        $this->getJson('/api/v1/books?sort=title', [
            'accept' => 'application/json',
            'content-type' => 'application/json',
        ])
            ->assertStatus(200)->assertJson([
                "data" => [
                    [
                        "id" => '3',
                        "type" => "books",
                        "attributes" => [
                            'title' => 'Adhering to the JSON:API Specification',
                            'description' => $books[2]->description,
                            'year' => $books[2]->year,
                        ]
                    ],
                    [
                        "id" => '1',
                        "type" => "books",
                        "attributes" => [
                            'title' => 'Building an API with Laravel',
                            'description' => $books[0]->description,
                            'year' => $books[0]->year,
                        ]
                    ],
                    [
                        "id" => '2',
                        "type" => "books",
                        "attributes" => [
                            'title' => 'Classes are our blueprints',
                            'description' => $books[1]->description,
                            'year' => $books[1]->year,
                        ]
                    ],
                ]
            ]);
    }

    /**
     * @test
     */
    public function
    it_can_sort_books_by_title_in_descending_order_through_a_sort_query_parameter
    ()
    {
        $user = factory(User::class)->create();
        Passport::actingAs($user);
        $books = collect([
            'Building an API with Laravel',
            'Classes are our blueprints',
            'Adhering to the JSON:API Specification',
        ])->map(function ($title) {
            return factory(Book::class)->create([
                'title' => $title
            ]);
        });

        $this->getJson('/api/v1/books?sort=-title', [
            'accept' => 'application/json',
            'content-type' => 'application/json',
        ])
            ->assertStatus(200)->assertJson([
                "data" => [
                    [
                        "id" => '2',
                        "type" => "books",
                        "attributes" => [
                            'title' => 'Classes are our blueprints',
                            'description' => $books[1]->description,
                            'year' => $books[1]->year,
                        ]
                    ],
                    [
                        "id" => '1',
                        "type" => "books",
                        "attributes" => [
                            'title' => 'Building an API with Laravel',
                            'description' => $books[0]->description,
                            'year' => $books[0]->year,
                        ]
                    ],
                    [
                        "id" => '3',
                        "type" => "books",
                        "attributes" => [
                            'title' => 'Adhering to the JSON:API Specification',
                            'description' => $books[2]->description,
                            'year' => $books[2]->year,
                        ]
                    ],

                ]
            ]);
    }

    /**
     * @test
     */
    public function
    it_can_sort_books_by_multiple_attributes_through_a_sort_query_parameter
    ()
    {
        $user = factory(User::class)->create();
        Passport::actingAs($user);
        $books = collect([
            'Building an API with Laravel',
            'Classes are our blueprints',
            'Adhering to the JSON:API Specification',
        ])->map(function ($title) {
            if ($title === 'Building an API with Laravel') {
                return factory(Book::class)->create([
                    'title' => $title,
                    'year' => '2020',
                ]);
            }
            return factory(Book::class)->create([
                'title' => $title,
                'year' => '2019'
            ]);
        });

        $this->getJson('/api/v1/books?sort=year,title', [
            'accept' => 'application/json',
            'content-type' => 'application/json',
        ])
            ->assertStatus(200)->assertJson([
                "data" => [
                    [
                        "id" => '3',
                        "type" => "books",
                        "attributes" => [
                            'title' => 'Adhering to the JSON:API Specification',
                            'description' => $books[2]->description,
                            'year' => $books[2]->year,
                        ]
                    ],
                    [
                        "id" => '2',
                        "type" => "books",
                        "attributes" => [
                            'title' => 'Classes are our blueprints',
                            'description' => $books[1]->description,
                            'year' => $books[1]->year,
                        ]
                    ],
                    [
                        "id" => '1',
                        "type" => "books",
                        "attributes" => [
                            'title' => 'Building an API with Laravel',
                            'description' => $books[0]->description,
                            'year' => $books[0]->year,
                        ]
                    ],

                ]
            ]);
    }

    /**
     * @test
     */
    public function
    it_can_sort_books_by_multiple_attributes_in_descending_order_through_a_sort_query_parameter
    ()
    {
        $user = factory(User::class)->create();
        Passport::actingAs($user);
        $books = collect([
            'Building an API with Laravel',
            'Classes are our blueprints',
            'Adhering to the JSON:API Specification',
        ])->map(function ($title) {
            if ($title === 'Building an API with Laravel') {
                return factory(Book::class)->create([
                    'title' => $title,
                    'year' => '2020',
                ]);
            }
            return factory(Book::class)->create([
                'title' => $title,
                'year' => '2019'
            ]);
        });

        $this->getJson('/api/v1/books?sort=-year,title', [
            'accept' => 'application/json',
            'content-type' => 'application/json',
        ])
            ->assertStatus(200)->assertJson([
                "data" => [
                    [
                        "id" => '1',
                        "type" => "books",
                        "attributes" => [
                            'title' => 'Building an API with Laravel',
                            'description' => $books[0]->description,
                            'year' => $books[0]->year,
                        ]
                    ],
                    [
                        "id" => '3',
                        "type" => "books",
                        "attributes" => [
                            'title' => 'Adhering to the JSON:API Specification',
                            'description' => $books[2]->description,
                            'year' => $books[2]->year,
                        ]
                    ],
                    [
                        "id" => '2',
                        "type" => "books",
                        "attributes" => [
                            'title' => 'Classes are our blueprints',
                            'description' => $books[1]->description,
                            'year' => $books[1]->year,
                        ]
                    ],


                ]
            ]);
    }

    /**
     * @test
     */
    public function
    it_can_paginate_books_through_a_page_query_parameter()
    {
        $user = factory(User::class)->create();
        Passport::actingAs($user);
        $books = factory(Book::class, 10)->create();

        $this->getJson('/api/v1/books?page[size]=5&page[number]=1', [
            'accept' => 'application/json',
            'content-type' => 'application/json',
        ])
            ->assertStatus(200)->assertJson([
                "data" => [
                    [
                        "id" => '1',
                        "type" => "books",
                        "attributes" => [
                            'title' => $books[0]->title,
                            'description' => $books[0]->description,
                            'year' => $books[0]->year,
                        ]
                    ],
                    [
                        "id" => '2',
                        "type" => "books",
                        "attributes" => [
                            'title' => $books[1]->title,
                            'description' => $books[1]->description,
                            'year' => $books[1]->year,
                        ]
                    ],
                    [
                        "id" => '3',
                        "type" => "books",
                        "attributes" => [
                            'title' => $books[2]->title,
                            'description' => $books[2]->description,
                            'year' => $books[2]->year,
                        ]
                    ],
                    [
                        "id" => '4',
                        "type" => "books",
                        "attributes" => [
                            'title' => $books[3]->title,
                            'description' => $books[3]->description,
                            'year' => $books[3]->year,
                        ]
                    ],
                    [
                        "id" => '5',
                        "type" => "books",
                        "attributes" => [
                            'title' => $books[4]->title,
                            'description' => $books[4]->description,
                            'year' => $books[4]->year,
                        ]
                    ],
                ],
                'links' => [
                    'first' => route('books.index', ['page[size]' => 5, 'page[number]' => 1]),
                    'last' => route('books.index', ['page[size]' => 5, 'page[number]' => 2]),
                    'prev' => null,
                    'next' => route('books.index', ['page[size]' => 5, 'page[number]' => 2]),
                ]
            ]);
    }

    /**
     * @test
     */
    public function
    it_can_paginate_books_through_a_page_query_parameter_and_show_different_pages
    ()
    {
        $user = factory(User::class)->create();
        Passport::actingAs($user);
        $books = factory(Book::class, 10)->create();

        $this->getJson('/api/v1/books?page[size]=5&page[number]=2', [
            'accept' => 'application/json',
            'content-type' => 'application/json',
        ])
            ->assertStatus(200)->assertJson([
                "data" => [
                    [
                        "id" => '6',
                        "type" => "books",
                        "attributes" => [
                            'title' => $books[5]->title,
                            'description' => $books[5]->description,
                            'year' => $books[5]->year,
                        ]
                    ],
                    [
                        "id" => '7',
                        "type" => "books",
                        "attributes" => [
                            'title' => $books[6]->title,
                            'description' => $books[6]->description,
                            'year' => $books[6]->year,
                        ]
                    ],
                    [
                        "id" => '8',
                        "type" => "books",
                        "attributes" => [
                            'title' => $books[7]->title,
                            'description' => $books[7]->description,
                            'year' => $books[7]->year,
                        ]
                    ],
                    [
                        "id" => '9',
                        "type" => "books",
                        "attributes" => [
                            'title' => $books[8]->title,
                            'description' => $books[8]->description,
                            'year' => $books[8]->year,
                        ]
                    ],
                    [
                        "id" => '10',
                        "type" => "books",
                        "attributes" => [
                            'title' => $books[9]->title,
                            'description' => $books[9]->description,
                            'year' => $books[9]->year,
                        ]
                    ],
                ],
                'links' => [
                    'first' => route('books.index', ['page[size]' => 5, 'page[number]' => 1]),
                    'last' => route('books.index', ['page[size]' => 5, 'page[number]' => 2]),
                    'prev' => route('books.index', ['page[size]' => 5, 'page[number]' => 1]),
                    'next' => null,
                ]
            ]);
    }

    /**
     * @test
     *
     */
    public function
    it_validates_that_the_title_is_given_when_creating_a_book
    ()
    {
        $user = factory(User::class)->create();
        Passport::actingAs($user);
        $this->postJson('/api/v1/books', [
            'title' => '',
            'description' => 'A book about API development',
            'year' => '2020'
        ])->assertStatus(422)
        ->assertJson([
            'message' => 'The given data was invalid.',
            'errors'=>[
                'title'=>[
                    'The title field is required.'
                ]
            ]
        ]);

        $this->assertDatabaseMissing('books',[
            'id' => 1,
            'title' => 'Building an API with Laravel',
            'description' => 'A book about API development',
            'publication_year' => '2020',
        ]);
    }
    /**
     * @test
     *
     */
    public function
    it_validates_that_a_title_attribute_is_a_string_when_creating_a_book
    ()
    {
        $user = factory(User::class)->create();
        Passport::actingAs($user);

        $this->postJson('/api/v1/books', [
            'title' => 244,
            'description' => 'A book about API development',
            'year' => '2020'
        ])->assertStatus(422)
            ->assertJson([
                'message' => 'The given data was invalid.',
                'errors'=>[
                    'title'=>[
                        'The title must be a string.'
                    ]
                ]
            ]);
    }

}
