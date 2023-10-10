<?php
// ADEL CODEIGNITER 4 CRUD GENERATOR

namespace App\Controllers;

use App\Controllers\BaseController;

use App\Models\AdminModel;

class Admin extends BaseController
{

	protected $adminModel;
	protected $validation;

	public function __construct()
	{
		$this->adminModel = new AdminModel();
		$this->validation =  \Config\Services::validation();
	}

	public function index()
	{

		$data = [
			'controller'    	=> 'admin',
			'title'     		=> 'Admin'
		];

		return view('admin', $data);
	}

	public function getAll()
	{
		$response = $data['data'] = array();

		$result = $this->adminModel->select()->findAll();
		$no = 1;
		foreach ($result as $key => $value) {

			$ops = '<div class="btn-group">';
			$ops .= '<button type="button" class=" btn btn-sm dropdown-toggle btn-info" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">';
			$ops .= '<i class="fad fa-pen-square"></i>  </button>';
			$ops .= '<div class="dropdown-menu">';
			$ops .= '<a class="dropdown-item text-info" onClick="save(' . $value->id_admin . ')"><i class="fad fa-pen-square"></i>   ' .  lang("App.edit")  . '</a>';
			$ops .= '<a class="dropdown-item text-orange" ><i class="fad fa-copy"></i>   ' .  lang("App.copy")  . '</a>';
			$ops .= '<div class="dropdown-divider"></div>';
			$ops .= '<a class="dropdown-item text-danger" onClick="remove(' . $value->id_admin . ')"><i class="fad fa-trash"></i>   ' .  lang("App.delete")  . '</a>';
			$ops .= '</div></div>';

			$data['data'][$key] = array(
				$no++,
				$value->username,
				$value->email,
				$ops
			);
		}

		return $this->response->setJSON($data);
	}

	public function getOne()
	{
		$response = array();

		$id = $this->request->getPost('id_admin');

		if ($this->validation->check($id, 'required|numeric')) {

			$data = $this->adminModel->where('id_admin', $id)->first();

			return $this->response->setJSON($data);
		} else {
			throw new \CodeIgniter\Exceptions\PageNotFoundException();
		}
	}

	public function add()
	{
		$response = array();

		$fields['id_admin'] = $this->request->getPost('id_admin');
		$fields['username'] = $this->request->getPost('username');
		$fields['email'] = $this->request->getPost('email');
		$fields['password'] = password_hash((string)$this->request->getPost('password'), PASSWORD_DEFAULT);


		$this->validation->setRules([
			'username' => ['label' => 'Username', 'rules' => 'required|min_length[3]'],
			'email' => ['label' => 'Email', 'rules' => 'required|valid_email|min_length[0]|max_length[200]'],
			'password' => ['label' => 'Password', 'rules' => 'required|min_length[0]|max_length[200]'],

		]);

		if ($this->validation->run($fields) == FALSE) {

			$response['success'] = false;
			$response['messages'] = $this->validation->getErrors(); //Show Error in Input Form

		} else {

			if ($this->adminModel->insert($fields)) {

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

		$fields['id_admin'] = $this->request->getPost('id_admin');
		$fields['username'] = $this->request->getPost('username');
		$fields['email'] = $this->request->getPost('email');
		$fields['password'] = password_hash((string)$this->request->getPost('password'), PASSWORD_DEFAULT);


		$this->validation->setRules([
			'username' => ['label' => 'Username', 'rules' => 'required|min_length[3]'],
			'email' => ['label' => 'Email', 'rules' => 'required|valid_email|min_length[0]|max_length[200]'],
			'password' => ['label' => 'Password', 'rules' => 'required|min_length[0]|max_length[200]'],

		]);

		if ($this->validation->run($fields) == FALSE) {

			$response['success'] = false;
			$response['messages'] = $this->validation->getErrors(); //Show Error in Input Form

		} else {

			if ($this->adminModel->update($fields['id_admin'], $fields)) {

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

		$id = $this->request->getPost('id_admin');

		if (!$this->validation->check($id, 'required|numeric')) {

			throw new \CodeIgniter\Exceptions\PageNotFoundException();
		} else {

			if ($this->adminModel->where('id_admin', $id)->delete()) {

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
