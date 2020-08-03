<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateAuthorRequest;
use App\Http\Requests\UpdateAuthorRequest;
use App\Http\Resources\AuthorsCollection;
use App\Http\Resources\AuthorsResource;
use App\Http\Resources\JSONAPICollection;
use App\Http\Resources\JSONAPIResource;
use App\Model\Author;
use Illuminate\Http\Request;
use Spatie\QueryBuilder\QueryBuilder;

class AuthorsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        //$authors = Author::all();
        $authors = QueryBuilder::for(Author::class)->allowedSorts([
            'name'
        ])->jsonPaginate();
        return new JSONAPICollection($authors);
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
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreateAuthorRequest $request)
    {

        //
        $author = Author::create([
            'name'=>$request->name,
        ]);
        return new JSONAPIResource($author);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Model\Author  $author
     * @return \Illuminate\Http\Response
     */
    public function show(Author $author)
    {
        //
        return new JSONAPIResource($author);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Model\Author  $author
     * @return \Illuminate\Http\Response
     */
    public function edit(Author $author)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Model\Author  $author
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateAuthorRequest $request, Author $author)
    {
        //
        $author->update($request->input('data.attributes'));

        return new JSONAPIResource($author);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Model\Author  $author
     * @return \Illuminate\Http\Response
     */
    public function destroy(Author $author)
    {
        //
        $author->delete();
        return response(null, 204);
    }
}
