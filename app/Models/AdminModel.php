<?php

namespace App\Models;

use CodeIgniter\Model;

class AdminModel extends Model
{
    protected $returnType = 'object';
    protected $skipValidation = true;
    protected $table            = 'admin';
    protected $primaryKey       = 'id_admin';
    protected $allowedFields    = ['username','email','password'];

}
