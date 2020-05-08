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
        $book->authors()->sync($authors->only('id'));
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
                    "relationships" => [
                        'authors' => [
                        'data' => [
                            [
                                'id' => $authors->get(0)->id,
                                'type' => 'author' ],
                            [
                                'id' => $authors->get(1)->id,
                                'type' => 'author'
                            ]
                        ]
                        ]
                    ]
                ]
            ]);
    }
}
