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
        $this->getJson('/api/v1/authors/1',[
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
                'type' => 'authors',
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
     * @watch
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

}
