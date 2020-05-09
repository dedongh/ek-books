<?php

namespace App\Http\Controllers;

use App\Http\Resources\AuthorsIdentifierResource;
use App\Model\Book;
use Illuminate\Http\Request;

class BooksAuthorsRelationshipsController extends Controller
{
    //
    public function index(Book $book)
    {
        return AuthorsIdentifierResource::collection($book->authors);
    }
}
