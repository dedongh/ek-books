<?php

namespace App\Http\Controllers;

use App\Model\Author;
use App\Services\JSONAPIService;
use Illuminate\Http\Request;

class AuthorsBooksRelationshipsController extends Controller
{
    //
    private $service;

    public function __construct(JSONAPIService $service)
    {
        $this->service = $service;
    }

    public function index(Author $author)
    {
        return $this->service->fetchRelationship($author, 'books');
    }

    public function update(Request $request, Author $author)
    {
        return $this->service->updateManyToManyRelationships($author, 'books',
            $request->input('data.*.id'));
    }
}
