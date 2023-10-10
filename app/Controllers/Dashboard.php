<?php

namespace App\Controllers;

use App\Controllers\BaseController;

use App\Models\AdminModel;
use App\Models\VendorModel;
use App\Models\PegawaiModel;
use App\Models\ProjectModel;
use App\Models\ProjectpegawaiModel;
use App\Models\ProjectvendorModel;


class Dashboard extends BaseController
{
    protected $projectModel;
    protected $adminModel;
    protected $vendorModel;
    protected $pegawaiModel;
    protected $db;

    public function __construct()
    {
        $this->projectModel = new ProjectModel();
        $this->adminModel = new AdminModel();
        $this->vendorModel = new VendorModel();
        $this->pegawaiModel = new PegawaiModel();
        $this->db = db_connect();
    }

    public function index()
    {
        $data = [
            'controller'        => 'Dashboard',
            'title'             => 'Dashboard',
            'total_admin'       => $this->adminModel->select('COUNT(id_admin) as total_admin')->first(),
            'total_project' => $this->projectModel->select('COUNT(id_project) as total_project')->first(),
            'total_vendor' => $this->vendorModel->select('COUNT(id_vendor) as total_vendor')->first(),
            'total_pegawai' => $this->pegawaiModel->select('COUNT(id_pegawai) as total_pegawai')->first(),
            'total_project_vendor' => $this->db->table('project_vendor')->select('COUNT(vendor_id) as total_project_vendor')->get()->getFirstRow(),
            'total_project_pegawai' => $this->db->table('project_pegawai')->select('COUNT(pegawai_id) as total_project_pegawai')->get()->getFirstRow(),
        ];

        return view('dashboard', $data);
    }

    public static function timeLeft($deadline)
    {
        $today = date_create();
        $diff = strtotime($deadline) - $today->getTimestamp();
        if ($diff <= 0) {
            return "Deadline has passed!";
        }
        $days = floor($diff / (60 * 60 * 24));
        return $days . ' days left';
    }


    public function getAllProjectVendor()
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
            $deadline = Dashboard::timeLeft($value->end_date);

            if ($deadline == '2 days left') {
                $deadline = '<span style="color: red;">' . $deadline . '</span>';
            } else {
                $deadline;
            }

            $ops = '<div class="btn-group text-white">';
            $ops .= '<a class="btn btn-dark" onClick="save(' . $value->id_project . ',' . $value->id_vendor . ')"><i class="fas fa-pencil-alt"></i></a>';
            $ops .= '</div>';
            $data['data'][$key] = array(
                $no,
                $value->project_name,
                $value->vendor_name,
                $value->role_name,
                $deadline,
                '<a href="' . $value->link_project . '" target="_blank">' . $value->link_project . '</a>',
                '<a href="' . $value->link_pengumpulan . '" target="_blank">' . $value->link_pengumpulan . '</a>',
                $value->status == 0 ? '<button class="btn btn-primary btn-sm"><i class="fas fa-sync"></i></button>' : '<button class="btn btn-success btn-sm"><i class="fas fa-check"></i></button>',
            );
            $no++;
        }

        return $this->response->setJSON($data);
    }

    public function getAllProjectPegawai()
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
        $no = 1;
        foreach ($result as $key => $value) {
            $deadline = Dashboard::timeLeft($value->end_date);

            if ($deadline == '2 days left') {
                $deadline = '<span style="color: red;">' . $deadline . '</span>';
            } else {
                $deadline;
            }


            $ops = '<div class="btn-group text-white">';
            $ops .= '<a class="btn btn-dark" onClick="save(' . $value->id_project . ',' . $value->id_pegawai . ')"><i class="fas fa-pencil-alt"></i></a>';
            $ops .= '</div>';
            $data['data'][$key] = array(
                $no,
                $value->project_name,
                $value->pegawai_name,
                $value->role_name,
                $deadline,
                $value->link,
                $value->status == 0 ? '<button class="btn btn-primary btn-sm"><i class="fas fa-sync"></i></button>' : '<button class="btn btn-success btn-sm"><i class="fas fa-check"></i></button>',
            );
            $no++;
        }

        return $this->response->setJSON($data);
    }
}