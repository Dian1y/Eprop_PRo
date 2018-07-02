<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TrxDetails extends Model
{
     /**
     * Fillable fields
     * 
     * @var array
     */

    protected $table = 'trx_cmo_details';

    /*protected $fillable = [
          'header_id',
		  'kode_item',
		  'kode_descr',
		  'uom_trx',
		  'stock_awal_cycle',
		  'sp',
		  'total_stock',
		  'est_sales',
		  'est_stock_akhir',
		  'est_sales_nextmonth',
		  'buffer',
		  'average',
		  'doi',
		  'cmo',
		  'cmo_status',
		  'delivery_date',
		  'delivery_num',
		  'delivery_qty',
		  'price_crt',
		  'disc_percent',
		  'disc_value',
		  'netto',
		  'ppn',
		  'extended_price',
		  'created_by',
		  'updated_by'
    ];*/

}