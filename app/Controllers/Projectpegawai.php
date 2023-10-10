<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\ProjectModel;

class Projectpegawai extends BaseController
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
            'controller'        => ucwords('projectpegawai'),
            'title'             => ucwords('project pegawai')
        ];
        return view('projectpegawai', $data);
    }


    public function getAll()
    {
        $response = $data['data'] = array();

        $db = db_connect();
        if (session()->get('level') == 'Pegawai') {
            $result = $db->table('vw_project_pegawai')->select('*')->where('id_pegawai', session()->get('id'))->get()->getResultObject();
        } else if (session()->get('level') == 'Admin') {
            $result = $db->table('vw_project_pegawai')->select('*')->get()->getResultObject();
        } else {
            $result = NULL;
        }
        $linkprojectRoleOne = $db->table('vendor')->select('*')
        ->join('project_vendor', 'vendor.id_vendor = project_vendor.vendor_id')
        ->where('vendor.id_role', 3)
        ->get()->getRowObject();
        $linkprojectRoleTwo = $db->table('vendor')->select('*')
        ->join('project_vendor', 'vendor.id_vendor = project_vendor.vendor_id')
        ->where('vendor.id_role', 4)
        ->get()->getRowObject();

        $no = 1;

        foreach ($result as $key => $value) {
            $ops = '<div class="btn-group text-white">';
            $ops .= '<a class="btn btn-dark" onClick="save(' . $value->id_project . ',' . $value->id_pegawai . ')"><i class="fas fa-pencil-alt"></i></a>';
            $ops .= '</div>';

            $tes = '';
             
            $data['data'][$key] = array(
                $no,
                $value->project_name,
                $value->pegawai_name,
                $value->role_name,
                $value->start_date,
                $value->end_date,
                '<h6>Editing foto album dokumentasi </h6><a target="_blank" href="' . $linkprojectRoleOne->link .'">Klik Disini</a><br><br><h6>Editing foto album candid </h6><a target="_blank" href="' . $linkprojectRoleTwo->link .'">Klik Disini</a>',
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
        $id_pega = $this->request->getPost('id_pegawai');

        if ($this->validation->check($id_prj, 'required|numeric')) {

            $data = $this->db->table('project_pegawai')->where('project_id', $id_prj)->where('pegawai_id', $id_pega)->get()->getFirstRow();

            return $this->response->setJSON($data);
        } else {
            throw new \CodeIgniter\Exceptions\PageNotFoundException();
        }
    }
    public function edit()
    {
        $response = array();

        $projectID = $this->request->getPost('id_project');
        $pegawaiID = $this->request->getPost('id_pega');

        $data = [
            'status'   => $this->request->getPost('status')
        ];

        $update = $this->db->table('project_pegawai')->update($data, [
            'project_id' => $projectID,
            'pegawai_id' => $pegawaiID
        ]);

        if ($update) {
            $this->db->query("CALL UpdateProjectStatus()")->getResultObject();
            $response['success'] = true;
            $response['messages'] = lang("App.update-success");
        } else {
            $response['success'] = false;
            $response['messages'] = lang("App.update-error");
        }

        return $this->response->setJSON($response);
    }
}