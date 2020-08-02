<?php

namespace App;

use App\User;
use Illuminate\Database\Eloquent\Model;

/**
 * Class MediaPost
 * @package App
 */
class MediaPost extends Model
{    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
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
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'file_path'
    ];     
    /**
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
    */
    public function user()
    {
        return $this->belongsTo(User::class,'user_id','uuid')->withDefault();
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
    /**
     * search function for normal user
     *
     * @return array
     */
    public static function search($id, $query)
    {   
       return MediaPost::where(function($q) use($query) {
                                $q->where('title', 'LIKE', "%$query%")
                                ->orWhere('tag', 'LIKE', "%$query%")
                                ->orWhere('description', 'LIKE', "%$query%");

                            })->where('user_id','=',$id)->get();
    }
    /**
     * search function for admin user
     *
     * @return array
     */
    public static function searchByAdmin($query)
    {   
       return MediaPost::where(function($q) use($query) {
                                $q->where('title', 'LIKE', "%$query%")
                                ->orWhere('tag', 'LIKE', "%$query%")
                                ->orWhere('description', 'LIKE', "%$query%");
                            })->get();
    }
}
