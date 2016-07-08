<?php

namespace Facenoob\Models;

use Illuminate\Database\Eloquent\Model;

class Like extends Model{

	protected $table = 'likes';
/*
	public function likes(){
		return $this->morphTo();
	}
*/
	public function user(){
		return $this->belongsTo('Facenoob\Models\User', 'user_id');
	}
}
?>