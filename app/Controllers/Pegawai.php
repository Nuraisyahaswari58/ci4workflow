<?php

namespace App\Controllers;

use App\Controllers\BaseController;

use App\Models\PegawaiModel;

use App\Models\RolevendorModel;

class Pegawai extends BaseController
{

	protected $pegawaiModel;
	protected $validation;
	protected $rolevendorModel;

	public function __construct()
	{
		$this->pegawaiModel = new PegawaiModel();
		$this->rolevendorModel = new RolevendorModel();
		$this->validation =  \Config\Services::validation();
	}

	public function index()
	{

		$data = [
			'controller'    	=> ucwords('pegawai'),
			'title'     		=> ucwords('pegawai'),
			'role'				=> $this->rolevendorModel->findAll()
		];

		return view('pegawai', $data);
	}

	public function getAll()
	{
		$response = $data['data'] = array();

		$result = $this->pegawaiModel->select('id_pegawai,pegawai_name,address, contact_person,email,phone_number,username,password,role.role_name as rolename',)
			->join('role', 'role.role_id = pegawai.id_role')
			->findAll();
		$no = 1;
		foreach ($result as $key => $value) {
			$ops = '<div class="btn-group text-white">';
			$ops .= '<a class="btn btn-dark" onClick="save(' . $value->id_pegawai . ')"><i class="fas fa-pencil-alt"></i></a>';
			$ops .= '<a class="btn btn-secondary" onClick="remove(' . $value->id_pegawai . ')"><i class="fas fa-trash-alt"></i></a>';
			$ops .= '</div>';
			$data['data'][$key] = array(
				$no,
				$value->pegawai_name,
				$value->address,
				$value->contact_person,
				$value->email,
				$value->phone_number,
				$value->username,
				$ops
			);
			$no++;
		}

		return $this->response->setJSON($data);
	}

	public function getOne()
	{
		$response = array();

		$id = $this->request->getPost('id_pegawai');

		if ($this->validation->check($id, 'required|numeric')) {
			$data = $this->pegawaiModel->where('id_pegawai', $id)->first();
			return $this->response->setJSON($data);
		} else {
			throw new \CodeIgniter\Exceptions\PageNotFoundException();
		}
	}

	public function add()
	{
		$response = array();

		$fields['id_pegawai'] = $this->request->getPost('id_pegawai');
		$fields['pegawai_name'] = $this->request->getPost('pegawai_name');
		$fields['address'] = $this->request->getPost('address');
		$fields['contact_person'] = $this->request->getPost('contact_person');
		$fields['email'] = $this->request->getPost('email');
		$fields['phone_number'] = $this->request->getPost('phone_number');
		$fields['username'] = $this->request->getPost('username');
		$fields['password'] = password_hash((string)$this->request->getPost('password'), PASSWORD_DEFAULT);
		$fields['id_role'] = $this->request->getPost('id_role');



		$this->validation->setRules([
			'pegawai_name' => ['label' => 'Pegawai name', 'rules' => 'required|min_length[0]|max_length[30]'],
			'address' => ['label' => 'Address', 'rules' => 'permit_empty|min_length[0]|max_length[200]'],
			'contact_person' => ['label' => 'Contact person', 'rules' => 'permit_empty|min_length[0]|max_length[100]'],
			'email' => ['label' => 'Email', 'rules' => 'permit_empty|valid_email|min_length[0]|max_length[100]'],
			'phone_number' => ['label' => 'Phone number', 'rules' => 'permit_empty|min_length[0]|max_length[20]'],
			'username' => ['label' => 'Username', 'rules' => 'required|min_length[0]|max_length[100]'],
			'password' => ['label' => 'Password', 'rules' => 'required|min_length[0]|max_length[200]'],
			'id_role' => ['label' => 'Id role', 'rules' => 'required|numeric|min_length[0]'],
		]);

		if ($this->validation->run($fields) == FALSE) {

			$response['success'] = false;
			$response['messages'] = $this->validation->getErrors(); //Show Error in Input Form

		} else {

			if ($this->pegawaiModel->insert($fields)) {

				$response['success'] = true;
				$response['messages'] = lang("App.insert-success");
			} else {

				$response['success'] = false;
				$response['messages'] = lang("App.insert-error");
			}
		}

		return $this->response->setJSON($response);
	}

	public function edit()
	{
		$response = array();

		$fields['id_pegawai'] = $this->request->getPost('id_pegawai');
		$fields['pegawai_name'] = $this->request->getPost('pegawai_name');
		$fields['address'] = $this->request->getPost('address');
		$fields['contact_person'] = $this->request->getPost('contact_person');
		$fields['email'] = $this->request->getPost('email');
		$fields['phone_number'] = $this->request->getPost('phone_number');
		$fields['username'] = $this->request->getPost('username');
		$fields['password'] = password_hash((string)$this->request->getPost('password'), PASSWORD_DEFAULT);
		$fields['id_role'] = $this->request->getPost('id_role');


		$this->validation->setRules([
			'pegawai_name' => ['label' => 'Pegawai name', 'rules' => 'required|min_length[0]|max_length[30]'],
			'address' => ['label' => 'Address', 'rules' => 'permit_empty|min_length[0]|max_length[200]'],
			'contact_person' => ['label' => 'Contact person', 'rules' => 'permit_empty|min_length[0]|max_length[100]'],
			'email' => ['label' => 'Email', 'rules' => 'permit_empty|valid_email|min_length[0]|max_length[200]'],
			'phone_number' => ['label' => 'Phone number', 'rules' => 'permit_empty|min_length[0]|max_length[20]'],
			'username' => ['label' => 'Username', 'rules' => 'required|min_length[0]|max_length[100]'],
			'password' => ['label' => 'Password', 'rules' => 'required|min_length[0]|max_length[200]'],


		]);

		if ($this->validation->run($fields) == FALSE) {

			$response['success'] = false;
			$response['messages'] = $this->validation->getErrors(); //Show Error in Input Form

		} else {

			if ($this->pegawaiModel->update($fields['id_pegawai'], $fields)) {

				$response['success'] = true;
				$response['messages'] = lang("App.update-success");
			} else {

				$response['success'] = false;
				$response['messages'] = lang("App.update-error");
			}
		}

		return $this->response->setJSON($response);
	}

	public function remove()
	{
		$response = array();

		$id = $this->request->getPost('id_pegawai');

		if (!$this->validation->check($id, 'required|numeric')) {

			throw new \CodeIgniter\Exceptions\PageNotFoundException();
		} else {

			if ($this->pegawaiModel->where('id_pegawai', $id)->delete()) {

				$response['success'] = true;
				$response['messages'] = lang("App.delete-success");
			} else {

				$response['success'] = false;
				$response['messages'] = lang("App.delete-error");
			}
		}

		return $this->response->setJSON($response);
	}
}
