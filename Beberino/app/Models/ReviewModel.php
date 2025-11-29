<?php namespace App\Models;

use CodeIgniter\Model;

class ReviewModel extends Model
{
    protected $table = 'reviews';
    protected $primaryKey = 'id';
    protected $allowedFields = ['product_id', 'customer_name', 'rating', 'feedback', 'image', 'created_at'];
    protected $useTimestamps = false;
}
