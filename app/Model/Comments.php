<?php

namespace App\Model;

use App\User;
use Illuminate\Database\Eloquent\Model;

class Comments extends AbstractAPIModel
{
    //

    protected $fillable = [
        'message'
    ];

    protected $hidden = [
        'created_at','updated_at'
    ];

    public function type()
    {
        // TODO: Implement type() method.
        return 'comments';
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function users(){
        return $this->user();
    }
    public function book()
    {
        return $this->belongsTo(Book::class);
    }
    public function books()
    {
        return $this->book();
    }
}
