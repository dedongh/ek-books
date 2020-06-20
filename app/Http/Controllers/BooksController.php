<?php

namespace App\Http\Controllers;

use App\Http\Resources\BooksCollection;
use App\Http\Resources\BooksResource;
use App\Model\Book;
use Illuminate\Http\Request;
use Spatie\QueryBuilder\QueryBuilder;

class BooksController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        /*$books = Book::all();
        return new BooksCollection($books);*/

        $books = QueryBuilder::for(Book::class)->allowedSorts([
            'title', 'year'
        ])->jsonPaginate();
        return new BooksCollection($books);
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
        $book = Book::create([
            'title' => $request->title,
            'description' => $request->description,
            'year' => $request->year
        ]);
        return new BooksResource($book);
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

        $query = QueryBuilder::for(Book::where('id', $book))
            ->allowedIncludes('authors')
            ->firstOrFail();
        return new BooksResource($query);
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
        $book->update($request->input());

        return new BooksResource($book);
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
        $book->delete();
        return response(null, 204);
    }
}
