<?php

namespace App\Models;

use CodeIgniter\Model;

class PegawaiModel extends Model
{

	protected $table = 'pegawai';
	protected $primaryKey = 'id_pegawai';
	protected $returnType = 'object';
	protected $useSoftDeletes = false;
	protected $allowedFields = ['pegawai_name', 'address', 'contact_person', 'email', 'phone_number', 'username', 'password', 'id_role'];
	protected $useTimestamps = false;
	protected $createdField  = 'created_at';
	protected $updatedField  = 'updated_at';
	protected $deletedField  = 'deleted_at';
	protected $validationRules    = [];
	protected $validationMessages = [];
	protected $skipValidation     = true;
}
