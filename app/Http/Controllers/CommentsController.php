<?php

namespace App\Http\Controllers;

use App\Model\Comments;
use App\Services\JSONAPIService;
use Illuminate\Http\Request;

class CommentsController extends Controller
{

    private $service;

    public function __construct(JSONAPIService $service)
    {
        $this->service = $service;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \App\Http\Resources\JSONAPICollection
     */
    public function index()
    {
        //
        return $this->service->fetchResources(Comments::class,'comments');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \App\Http\Resources\JSONAPIResource
     */
    public function store(Request $request)
    {
        //
        return $this->service->createResource(Comments::class, $request->all());
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \App\Http\Resources\JSONAPIResource
     */
    public function show($id)
    {
        //
        return $this->service->fetchResource(Comments::class, $id,'comments');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param Comments $comments
     * @return \App\Http\Resources\JSONAPIResource
     */
    public function update(Request $request, Comments $comments)
    {
        //
        return $this->service->updateResource($comments, $request->input('data.attributes'));

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Comments $comments
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function destroy(Comments $comments)
    {
        //
        return $comments;
        //return $this->service->deleteResource($comments);
    }
}
