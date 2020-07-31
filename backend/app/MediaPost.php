<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * Class MediaPost
 * @package App
 */
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
    
    /**
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function userMediaPost()
    {
        return $this->belongsTo('App\User');
    }
    
    /**
     * Return a user uuid 
     *
     * @return integer
     */
    public function getUserIdAttribute()
    {
        return User::find($this->attributes['user_id'])->uuid;
    }

}
