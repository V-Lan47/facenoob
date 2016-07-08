<?php

namespace Facenoob\Models;

use Facenoob\Models\Status;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{

    protected $table = 'users';

    protected $fillable = [
        'username',
        'email',
        'password',
        'first_name',
        'last_name',
        'location',
    ];

    /*
    User
    */
    public function getName(){
        if ($this->first_name && $this->last_name){
            return "{$this->first_name} $this->last_name";
        }
        if ($this->first_name){
            return $this->first_name;
        }
        return null;
    }

    public function getNameOrUsername(){
        return $this->getName() ?: $this->username;
    }

    public function getFirstNameOrUsername(){
        return $this->first_name ?: $this->username;
    }

    public function getAvatarUrl(){
        /*return "https://www.gravatar.com/avatar/{{ md5($this->email) }}?d=mm&s=60";*/
        /*return "/projects/facenoob/images/mm.png";*/
        return $this->image ?: "/projects/facenoob/images/mm - Copy.png";
    }

    /*Friends*/
    public function friendOfMine(){
        return $this->belongsToMany('Facenoob\Models\User', 'friends', 'user_id', 'friend_id');
    }

    public function friendOf(){
        return $this->belongsToMany('Facenoob\Models\User', 'friends', 'friend_id', 'user_id');
    }

     public function friends(){
        return $this->friendOfMine()
        ->wherePivot('accepted', true)->get()->merge($this->friendOf()
        ->wherePivot('accepted', true)->get());
        //Merging both possible records into one mutual friendship
    }

    public function friendsRequests(){
        return $this->friendOfMine()->wherePivot('accepted', false)->get();
    }
  
    public function friendRequestsPending(){
        return $this->friendOf()->wherePivot('accepted', false)->get();
    }

    public function hasFriendRequestPending(User $user){
        return (bool) $this->friendRequestsPending()->where('id', $user->id)->count();
    }

    public function hasFriendRequestReceived(User $user){
        return (bool) $this->friendsRequests()->where('id', $user->id)->count();
    }

    public function addFriend(User $user){
        $this->friendOf()->attach($user->id);
    }

    public function acceptFriendRequest(User $user){
        $this->friendsRequests()
            ->where('id', $user->id)->first()->pivot
            ->update(['accepted' => true, 'created_at' => date_timestamp_set(date_create(), 1171502725),
            ]);
    }

    public function isFriendsWith(User $user){
        return (bool) $this->friends()->where('id', $user->id)->count();
    }
    /*Statuses*/
    public function statuses(){
        return $this->hasMany('Facenoob\Models\Status', 'user_id');
    }
    /*
    Likes
    Can't like own statuses, people's with who we're not friends with.
    */
    public function hasLikedStatus(Status $status){
        return (bool) $status->addLike
            ->where('like_id', $status->id)
            ->where('like_type', get_class($status))
            ->where('user_id', $this->id)
            ->count();
    }

    public function addLike(){
        return $this->hasMany('Facenoob\Models\Like', 'user_id');
    }
    
    /**
     * Attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];
}
