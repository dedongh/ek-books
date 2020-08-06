<?php

namespace App\Http\Controllers;

use App\Model\Comments;
use App\Services\JSONAPIService;
use Illuminate\Http\Request;

class CommentsUsersRelationshipsController extends Controller
{
    private $service;

    public function __construct(JSONAPIService $service)
    {

        $this->service = $service;
    }


    public function index(Comments $comments)
    {
        //
        return $this->service->fetchRelationship($comments, 'users');
    }



    public function update(Request $request, Comments $comments)
    {
        //
        return $this->service->updateToOneRelationship($comments, 'users', $request->input('data.id'));
    }

}
