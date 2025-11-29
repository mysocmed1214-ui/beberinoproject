<?php

namespace App\Models;

use CodeIgniter\Model;

class ProductModel extends Model
{
    protected $table = 'products';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'name', 'type', 'description', 'price', 'stock', 'image', 'created_at'
    ];
    protected $useTimestamps = false;
}
