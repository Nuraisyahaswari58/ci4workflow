<?php
namespace App\Models;
use CodeIgniter\Model;

class VendorModel extends Model {
    
	protected $table = 'vendor';
	protected $primaryKey = 'id_vendor';
	protected $returnType = 'object';
	protected $useSoftDeletes = false;
	protected $allowedFields = ['vendor_name', 'address', 'contact_person', 'email', 'phone_number', 'username', 'password', 'id_role'];
	protected $useTimestamps = false;
	protected $createdField  = 'created_at';
	protected $updatedField  = 'updated_at';
	protected $deletedField  = 'deleted_at';
	protected $validationRules    = [];
	protected $validationMessages = [];
	protected $skipValidation     = true;    
	
}