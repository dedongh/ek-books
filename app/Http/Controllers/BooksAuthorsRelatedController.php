<?php

namespace App\Http\Controllers;

use App\Http\Resources\AuthorsCollection;
use App\Http\Resources\JSONAPICollection;
use App\Model\Book;
use App\Services\JSONAPIService;
use Illuminate\Http\Request;

class BooksAuthorsRelatedController extends Controller
{
    //

    private $service;

    public function __construct(JSONAPIService $service)
    {
        $this->service = $service;
    }

    public function index(Book $book)
    {
        return $this->service->fetchRelated($book, 'authors');
    }
}
