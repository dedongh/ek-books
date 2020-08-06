<?php

namespace Tests\Feature;
use App\Model\Author;
use App\Model\Book;
use App\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Passport\Passport;
use Tests\TestCase;

class AuthorsRelationshipTest extends TestCase
{
    use DatabaseMigrations;
    /**
     * @test
     */

    public function
    it_returns_a_relationship_to_books_adhering_to_json_api_spec()
    {
        $author = factory(Author::class)->create();
        $books = factory(Book::class, 3)->create();
        $author->books()->sync($books->pluck('id'));
        $user = factory(User::class)->create();
        Passport::actingAs($user);

        $this->getJson('/api/v1/authors/1?include=books', [
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
        ])->assertStatus(200)
            ->assertJson([
                'data' => [
                    'id' => '1',
                    'type' => 'authors',
                    'relationships' => [
                        'books' => [
                            'links' => [
                                'self' => route(
                                    'authors.relationships.books',
                                    $author->id
                                ),
                                'related' => route(
                                    'authors.books', $author->id
                                ),
                            ],

                            'data' => [
                                [
                                    'id' => (string)$books->get(0)->id,
                                    'type' => 'books'
                                ],
                                [
                                    'id' => (string)$books->get(1)->id,
                                    'type' => 'books'
                                ]
                            ]
                        ]
                    ]
                ],
            ]);
    }
}
