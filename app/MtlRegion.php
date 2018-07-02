<?php

namespace App;

use Illuminate\Database\Eloquent\Model;


class MtlRegion extends Model
{
    protected $table = 'mtl_region';

   	protected $fillable = [
   					'region`',
   					'active',
   					'created_by',
   					'updated_by'
   					];
}
