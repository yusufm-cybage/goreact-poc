<?php

namespace App;

use Illuminate\Database\Eloquent\Model;


class MediaPost extends Model
{
    protected $fillable = [
        'user_id',
        'file_name',
        'file_path',
        'file_type',
        'title',
        'tag',
        'description'
    ];

    public function userMediaPost()
    {
        return $this->belongsTo('App\User');
    }

    public function getUserIdAttribute()
    {
        return User::find($this->attributes['user_id'])->uuid;
    }

}
