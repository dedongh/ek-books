<?php

namespace App\Http\Controllers;

use App\Model\Comments;
use App\Services\JSONAPIService;
use Illuminate\Http\Request;

class CommentsBooksRelationshipsController extends Controller
{
    private $service;

    public function __construct(JSONAPIService $service)
    {

        $this->service = $service;
    }

    /**
     * Display a listing of the resource.
     *
     * @param Comments $comments
     * @return void
     */
    public function index(Comments $comments)
    {
        //
        return $this->service->fetchRelationship($comments, 'books');
    }


    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param Comments $comment
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Comments $comment)
    {
        //
        return $this->service->updateToOneRelationship($comment, 'books', $request->input('data.id'));

    }

}
