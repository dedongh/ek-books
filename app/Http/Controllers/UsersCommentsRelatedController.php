<?php

namespace App\Http\Controllers;

use App\Services\JSONAPIService;
use App\User;
use Illuminate\Http\Request;

class UsersCommentsRelatedController extends Controller
{

    private $service;
    public function __construct(JSONAPIService $service)
    {
        $this->service = $service;
    }

    /**
     * Display a listing of the resource.
     *
     * @param User $user
     * @return \App\Http\Resources\JSONAPICollection
     */
    public function index(User $user)
    {
        //
        return $this->service->fetchRelated($user, 'comments');
    }


}
