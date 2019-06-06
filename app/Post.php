<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
	protected $table = 'post';

    protected $fillable = [
	    'user_id', 'name', 'text'
	];

	public function author() {
        return $this->hasOne('App\User', 'id' ,'iser_id');
    }
}
