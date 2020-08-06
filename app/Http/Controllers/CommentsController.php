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
     * @return \Illuminate\Http\Response
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
     * @return \Illuminate\Http\Response
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
     * @return \Illuminate\Http\Response
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
     * @return void
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
     * @return void
     */
    public function destroy(Comments $comments)
    {
        //
        return $this->service->deleteResource($comments);
    }
}
