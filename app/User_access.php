<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class User_access extends Model
{
    protected $table = 'users_access';
	
    protected $fillable = [
    			'user_id',
    			'cust_ship_id',
    			'created_by',
    			'updated_by'
    			];
}
