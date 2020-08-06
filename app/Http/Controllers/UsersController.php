<?php

namespace App\Http\Controllers;

use App\Services\JSONAPIService;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UsersController extends Controller
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
        return $this->service->fetchResources(User::class, 'users');
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
        return $this->service->createResource(
            User::class, [
                'name' => $request->input('name'),
                'email' => $request->input('email'),
                'password' => Hash::make(($request->input('password'))),

            ]
        );
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
        return $this->service->fetchResource(User::class, $id, 'users');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  User  $user
     * @return \App\Http\Resources\JSONAPIResource
     */
    public function update(Request $request, User $user)
    {
        //
        $attributes = $request->input('data.attributes');
        if(isset($attributes['password'])){
            $attributes['password'] = Hash::make($attributes['password']);
        }

        return $this->service->updateResource($user, $attributes);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        //
        return $this->service->deleteResource($user);
    }
}
