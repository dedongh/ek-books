<?php

namespace App\Model;

use App\Model\AbstractAPIModel;
use Illuminate\Database\Eloquent\Model;

class Author extends AbstractAPIModel
{
    //

    protected $fillable = ['name'];

    public function books()
    {
        return $this->belongsToMany(Book::class);
    }

    protected $hidden = [
        'created_at','updated_at'
    ];


    public function type()
    {
        // TODO: Implement type() method.
        return 'author';
    }
}
