<?php namespace App\Models;

use CodeIgniter\Model;

class PurchaseModel extends Model
{
    protected $table = 'purchases';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'receipt_no',
        'product_id',
        'customer_name',
        'address',
        'quantity',
        'total',
        'payment_method',
        'created_at'
    ];
}
