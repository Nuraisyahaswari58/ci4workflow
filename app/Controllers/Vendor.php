<?php

namespace App\Controllers;

use App\Controllers\BaseController;

use App\Models\VendorModel;
use App\Models\RolevendorModel;

class Vendor extends BaseController
{

	protected $vendorModel;
	protected $rolevendorModel;
	protected $validation;

	public function __construct()
	{
		$this->vendorModel = new VendorModel();
		$this->rolevendorModel = new RolevendorModel();
		$this->validation =  \Config\Services::validation();
	}

	public function index()
	{

		$data = [
			'controller'    	=> ucwords('vendor'),
			'title'     		=> ucwords('vendor'),
			'role'				=> $this->rolevendorModel->findAll()
		];

		return view('vendor', $data);
	}

	public function getAll()
	{
		$response = $data['data'] = array();
		$result = $this->vendorModel->select('id_vendor,vendor_name,address,contact_person,email,phone_number,username,password,role.role_name')
			->join('role', 'role.role_id = vendor.id_role')
			->findAll();

		$no = 1;
		foreach ($result as $key => $value) {
			$ops = '<div class="btn-group text-white">';
			$ops .= '<a class="btn btn-dark" onClick="save(' . $value->id_vendor . ')"><i class="fas fa-pencil-alt"></i></a>';
			$ops .= '<a class="btn btn-secondary" onClick="remove(' . $value->id_vendor . ')"><i class="fas fa-trash-alt"></i></a>';
			$ops .= '</div>';
			$data['data'][$key] = array(
				$no,
				$value->vendor_name,
				$value->address,
				$value->contact_person,
				$value->email,
				$value->phone_number,
				$value->username,
				$value->role_name,
				$ops
			);
			$no++;
		}

		return $this->response->setJSON($data);
	}

	public function getOne()
	{
		$response = array();
		$id = $this->request->getPost('id_vendor');
		if ($this->validation->check($id, 'required|numeric')) {
			$data = $this->vendorModel->where('id_vendor', $id)->first();
			return $this->response->setJSON($data);
		} else {
			throw new \CodeIgniter\Exceptions\PageNotFoundException();
		}
	}

	public function add()
	{
		$response = array();

		$fields['id_vendor'] = $this->request->getPost('id_vendor');
		$fields['vendor_name'] = $this->request->getPost('vendor_name');
		$fields['address'] = $this->request->getPost('address');
		$fields['contact_person'] = $this->request->getPost('contact_person');
		$fields['email'] = $this->request->getPost('email');
		$fields['phone_number'] = $this->request->getPost('phone_number');
		$fields['username'] = $this->request->getPost('username');
		$fields['password'] = password_hash((string)$this->request->getPost('password'), PASSWORD_DEFAULT);
		$fields['id_role'] = $this->request->getPost('id_role');


		$this->validation->setRules([
			'vendor_name' => ['label' => 'Vendor name', 'rules' => 'required|min_length[0]|max_length[100]'],
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

			if ($this->vendorModel->insert($fields)) {
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

		$fields['id_vendor'] = $this->request->getPost('id_vendor');
		$fields['vendor_name'] = $this->request->getPost('vendor_name');
		$fields['address'] = $this->request->getPost('address');
		$fields['contact_person'] = $this->request->getPost('contact_person');
		$fields['email'] = $this->request->getPost('email');
		$fields['phone_number'] = $this->request->getPost('phone_number');
		$fields['username'] = $this->request->getPost('username');
		$fields['password'] = password_hash((string)$this->request->getPost('password'), PASSWORD_DEFAULT);
		$fields['id_role'] = $this->request->getPost('id_role');


		$this->validation->setRules([
			'vendor_name' => ['label' => 'Vendor name', 'rules' => 'required|min_length[0]|max_length[100]'],
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

			if ($this->vendorModel->update($fields['id_vendor'], $fields)) {

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

		$id = $this->request->getPost('id_vendor');

		if (!$this->validation->check($id, 'required|numeric')) {

			throw new \CodeIgniter\Exceptions\PageNotFoundException();
		} else {

			if ($this->vendorModel->where('id_vendor', $id)->delete()) {

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
