<?php

namespace App\Controllers;

use App\Controllers\BaseController;

use App\Models\RolevendorModel;

class Rolevendor extends BaseController
{

	protected $rolevendorModel;
	protected $validation;

	public function __construct()
	{
		$this->rolevendorModel = new RolevendorModel();
		$this->validation =  \Config\Services::validation();
	}

	public function index()
	{

		$data = [
			'controller'    	=> ucwords('rolevendor'),
			'title'     		=> ucwords('role_vendor')
		];

		return view('rolevendor', $data);
	}

	public function getAll()
	{
		$response = $data['data'] = array();

		$result = $this->rolevendorModel->select()->findAll();
		$no = 1;
		foreach ($result as $key => $value) {
			$ops = '<div class="btn-group text-white">';
			$ops .= '<a class="btn btn-dark" onClick="save(' . $value->role_id . ')"><i class="fas fa-pencil-alt"></i></a>';
			$ops .= '<a class="btn btn-secondary" onClick="remove(' . $value->role_id . ')"><i class="fas fa-trash-alt"></i></a>';
			$ops .= '</div>';
			$data['data'][$key] = array(
				$no,
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

		$id = $this->request->getPost('role_id');

		if ($this->validation->check($id, 'required|numeric')) {

			$data = $this->rolevendorModel->where('role_id', $id)->first();

			return $this->response->setJSON($data);
		} else {
			throw new \CodeIgniter\Exceptions\PageNotFoundException();
		}
	}

	public function add()
	{
		$response = array();

		$fields['role_id'] = $this->request->getPost('role_id');
		$fields['role_name'] = $this->request->getPost('role_name');


		$this->validation->setRules([
			'role_name' => ['label' => 'Role name', 'rules' => 'required|min_length[0]'],

		]);

		if ($this->validation->run($fields) == FALSE) {

			$response['success'] = false;
			$response['messages'] = $this->validation->getErrors(); //Show Error in Input Form

		} else {

			if ($this->rolevendorModel->insert($fields)) {

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

		$fields['role_id'] = $this->request->getPost('role_id');
		$fields['role_name'] = $this->request->getPost('role_name');


		$this->validation->setRules([
			'role_name' => ['label' => 'Role name', 'rules' => 'required|min_length[0]'],

		]);

		if ($this->validation->run($fields) == FALSE) {

			$response['success'] = false;
			$response['messages'] = $this->validation->getErrors(); //Show Error in Input Form

		} else {

			if ($this->rolevendorModel->update($fields['role_id'], $fields)) {

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

		$id = $this->request->getPost('role_id');

		if (!$this->validation->check($id, 'required|numeric')) {

			throw new \CodeIgniter\Exceptions\PageNotFoundException();
		} else {

			if ($this->rolevendorModel->where('role_id', $id)->delete()) {

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
