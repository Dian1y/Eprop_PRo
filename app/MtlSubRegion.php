<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MtlSubRegion extends Model
{
    //
    protected $table = 'mtl_subregion';

   	protected $fillable = [
   					'id_region',
   					'subregion',
   					'active',
   					'created_by',
   					'updated_by'
   					]; 
}
