<?php

namespace Facenoob\Models;

use Illuminate\Database\Eloquent\Model;

class Status extends Model{

	protected $table = 'statuses';
	protected $fillable = ['status'];

	public function user(){
		return $this->belongsTo('Facenoob\Models\User', 'user_id');
	}

	public function scopeNotReply($query){
		return $query->whereNull('parent_id');
	}

	public function comments(){
		return $this->hasMany('Facenoob\Models\Status', 'parent_id');
	}
}


?>