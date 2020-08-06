<?php

namespace App\Http\Controllers;

use App\Model\Comments;
use App\Services\JSONAPIService;
use Illuminate\Http\Request;

class CommentsUsersRelatedController extends Controller
{
    private $service;

    public function __construct(JSONAPIService $service)
    {

        $this->service = $service;
    }

    public function index(Comments $comments)
    {
        //
        return $this->service->fetchRelated($comments, 'users');
    }

}
