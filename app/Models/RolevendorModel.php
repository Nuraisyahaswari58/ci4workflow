<?php
namespace App\Models;
use CodeIgniter\Model;

class RolevendorModel extends Model {
    
	protected $table = 'role';
	protected $primaryKey = 'role_id';
	protected $returnType = 'object';
	protected $useSoftDeletes = false;
	protected $allowedFields = ['role_name'];
	protected $useTimestamps = false;
	protected $createdField  = 'created_at';
	protected $updatedField  = 'updated_at';
	protected $deletedField  = 'deleted_at';
	protected $validationRules    = [];
	protected $validationMessages = [];
	protected $skipValidation     = true;    
	
}