<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\ProjectModel;

class Projectvendor extends BaseController
{
    protected $projectModel;
    protected $validation;
    protected $db;

    public function __construct()
    {
        $this->projectModel = new ProjectModel();
        $this->db      = db_connect();
        $this->validation =  \Config\Services::validation();
    }

    public function index()
    {
        $data = [
            'controller'        => ucwords('projectvendor'),
            'title'             => ucwords('project vendor')
        ];
        return view('projectvendor', $data);
    }


    public function getAll()
    {
        $response = $data['data'] = array();

        $db = db_connect();
        if (session()->get('level') == 'Vendor') {
            $result = $db->table('vw_project_vendor')->select('*')->where('id_vendor', session()->get('id'))->get()->getResultObject();
        } else if (session()->get('level') == 'Admin') {
            $result = $db->table('vw_project_vendor')->select('*')->get()->getResultObject();
        } else {
            $result = NULL;
        }


        $no = 1;
        foreach ($result as $key => $value) {
            $ops = '<div class="btn-group text-white">';
            $ops .= '<a class="btn btn-dark" onClick="save(' . $value->id_project . ',' . $value->id_vendor . ')"><i class="fas fa-pencil-alt"></i></a>';
            $ops .= '</div>';
            $data['data'][$key] = array(
                $no,
                $value->project_name,
                $value->vendor_name,
                $value->role_name,
                $value->start_date,
                $value->end_date,
                '<a href="' . $value->link_project . '" target="_blank">' . $value->link_project . '</a>',
                '<a href="' . $value->link_pengumpulan . '" target="_blank">' . $value->link_pengumpulan . '</a>',
                $value->status == 0 ? '<button class="btn btn-primary btn-sm"><i class="fas fa-sync"></i></button>' : '<button class="btn btn-success btn-sm"><i class="fas fa-check"></i></button>',
                $ops
            );
            $no++;
        }

        return $this->response->setJSON($data);
    }

    public function getOne()
    {
        $response = array();

        $id_prj = $this->request->getPost('id_project');
        $id_vend = $this->request->getPost('id_vendor');

        if ($this->validation->check($id_prj, 'required|numeric')) {

            $data = $this->db->table('project_vendor')->where('project_id', $id_prj)->where('vendor_id', $id_vend)->get()->getFirstRow();

            return $this->response->setJSON($data);
        } else {
            throw new \CodeIgniter\Exceptions\PageNotFoundException();
        }
    }

    public function edit()
    {
        $response = array();

        $projectID = $this->request->getPost('id_project');
        $vendorID = $this->request->getPost('id_vendor');

        $data = [
            'link' => $this->request->getPost('link'),
            'status'   => $this->request->getPost('status')
        ];

        $update = $this->db->table('project_vendor')->update($data, [
            'project_id' => $projectID,
            'vendor_id' => $vendorID
        ]);

        if ($update) {
            $response['success'] = true;
            $response['messages'] = lang("App.update-success");
            $this->db->query("CALL UpdateProjectStatus()")->getResultObject();
        } else {
            $response['success'] = false;
            $response['messages'] = lang("App.update-error");
        }

        return $this->response->setJSON($response);
    }
}
