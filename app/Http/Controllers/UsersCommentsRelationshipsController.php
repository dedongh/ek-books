<?php

namespace App\Http\Controllers;

use App\Services\JSONAPIService;
use App\User;
use Illuminate\Http\Request;

class UsersCommentsRelationshipsController extends Controller
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
     * @return void
     */
    public function index(User $user)
    {
        //
        return $this->service->fetchRelationship($user, 'comments');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param User $user
     * @return void
     */
    public function update(Request $request, User $user)
    {
        //
        return $this->service->updateToManyRelationships($user, 'comments',
            $request->input('data.*.id'));
    }

}
