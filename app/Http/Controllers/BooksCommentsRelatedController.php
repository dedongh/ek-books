<?php

namespace App\Http\Controllers;

use App\Model\Book;
use App\Services\JSONAPIService;
use Illuminate\Http\Request;

class BooksCommentsRelatedController extends Controller
{

    private $service;

    public function __construct(JSONAPIService $service)
    {
        $this->service = $service;
    }

    /**
     * Display a listing of the resource.
     *
     * @param Book $book
     * @return void
     */
    public function index(Book $book)
    {
        //
        return $this->service->fetchRelated($book, 'comments');
    }


}
