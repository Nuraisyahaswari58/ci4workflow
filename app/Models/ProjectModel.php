<?php
namespace App\Models;
use CodeIgniter\Model;

class ProjectModel extends Model {
    
	protected $table = 'project';
	protected $primaryKey = 'id_project';
	protected $returnType = 'object';
	protected $useSoftDeletes = false;
	protected $allowedFields = ['client_name', 'project_name', 'start_date', 'end_date', 'link', 'status'];
	protected $useTimestamps = true;
	protected $createdField  = 'created_at';
	protected $updatedField  = 'updated_at';
	protected $deletedField  = 'deleted_at';
	protected $validationRules    = [];
	protected $validationMessages = [];
	protected $skipValidation     = true;    
	
}