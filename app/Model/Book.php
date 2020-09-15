<?php

namespace App\Model;


use App\Model\AbstractAPIModel;
use Illuminate\Database\Eloquent\Model;

class Book extends AbstractAPIModel
{
    //
    protected $fillable = [
        'title','description','year'
    ];

    protected $hidden = [
        'created_at','updated_at'
    ];

    public function authors()
    {
        return $this->belongsToMany(Author::class);
    }

    public function comments()
    {
        return $this->hasMany(Comments::class);
    }

    public function type()
    {
        // TODO: Implement type() method.
        return 'books';
    }
}
