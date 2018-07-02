<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TrxRekap extends Model
{
    /**
     * Fillable fields
     * 
     * @var array
     */

     protected $table = 'trx_cmo_headers';

    /*protected $fillable = [
    	  'cust_ship_id',
		  'cust_bill_id',
		  'customer_number',
		  'cmo_number',
		  'cmo_period',
		  'subregion',
		  'cmo_status',
		  'rsdh_id',
		  'asdh_id',
		  'delivery_date_1',
		  'delivery_date_2',
		  'delivery_date_3',
		  'delivery_date_4', 
		  'created_by',
		  'updated_by'
    ];*/
}
