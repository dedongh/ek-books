<?php

namespace App\Http\Controllers;

use App\Model\Comments;
use App\Services\JSONAPIService;
use Illuminate\Http\Request;

class CommentsBooksRelatedController extends Controller
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
        return $this->service->fetchRelated($comments, 'books');
    }


}
