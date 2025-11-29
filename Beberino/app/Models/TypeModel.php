<?php

namespace App\Models;

use CodeIgniter\Model;

class TypeModel extends Model
{
    protected $table = 'types';
    protected $primaryKey = 'id';
    protected $allowedFields = ['name', 'description', 'created_at'];
    protected $useTimestamps = true;
}
