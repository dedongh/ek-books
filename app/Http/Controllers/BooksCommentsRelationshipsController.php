<?php

namespace App\Http\Controllers;

use App\Model\Book;
use App\Services\JSONAPIService;
use Illuminate\Http\Request;

class BooksCommentsRelationshipsController extends Controller
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
        return $this->service->fetchRelationship($book, 'comments');
    }


    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param Book $book
     * @return void
     */
    public function update(Request $request, Book $book)
    {
        //
        return $this->service->updateToManyRelationships($book, 'comments', $request->input('data.*.id'));

    }



}
