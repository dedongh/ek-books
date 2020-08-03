<?php

namespace App\Http\Controllers;

use App\Http\Resources\BooksCollection;
use App\Http\Resources\BooksResource;
use App\Http\Resources\JSONAPICollection;
use App\Http\Resources\JSONAPIResource;
use App\Model\Book;
use App\Services\JSONAPIService;
use Illuminate\Http\Request;
use Spatie\QueryBuilder\QueryBuilder;

class BooksController extends Controller
{
    private $service;

    public function __construct(JSONAPIService $service)
    {
        $this->service = $service;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        return $this->service->fetchResources(Book::class, 'books');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $this->validate($request, [
            'title' => 'required|string|max:255',
            'description' => 'required|string|max:255',
            'year' => 'required|string|min:4',
        ]);
        //
        return $this->service->createResource(Book::class,
        $request->only('title', 'description', 'year'));
    }

    /**
     * Display the specified resource.
     *
     * @param \App\Model\Book $book
     * @return \Illuminate\Http\Response
     */
    public function show($book)
    {
        //
        return $this->service->fetchResource(Book::class, $book, 'books');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\Model\Book $book
     * @return \Illuminate\Http\Response
     */
    public function edit(Book $book)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Model\Book $book
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Book $book)
    {
        //
        return $this->service->updateResource($book, $request->input());
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Model\Book $book
     * @return \Illuminate\Http\Response
     */
    public function destroy(Book $book)
    {
        //
        return $this->service->deleteResource($book);
    }
}
