<?php

namespace App\Http\Controllers;

use App\Http\Requests\BooksAuthorsRelationshipsRequest;
use App\Http\Resources\AuthorsIdentifierResource;
use App\Http\Resources\JSONAPIIdentifierResource;
use App\Model\Book;
use App\Services\JSONAPIService;
use Illuminate\Http\Request;

class BooksAuthorsRelationshipsController extends Controller
{
    //

    private $service;

    public function __construct(JSONAPIService $service)
    {
        $this->service = $service;
    }

    public function index(Book $book)
    {
        return $this->service->fetchRelationship($book, 'authors');
    }

    public function update(BooksAuthorsRelationshipsRequest $request, Book $book)
    {

        return $this->service
            ->updateManyToManyRelationships($book, 'authors', $request->input('data.*.id'));
    }
}
