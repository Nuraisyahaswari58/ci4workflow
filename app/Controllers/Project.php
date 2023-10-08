<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\ProjectModel;
use App\Models\VendorModel;
use App\Models\RolevendorModel;
use App\Models\PegawaiModel;

use CodeIgniter\I18n\Time;

class Project extends BaseController
{
    protected $projectModel;
    protected $projectvendorModel;
    protected $pegawaiModel;
    protected $vendorModel;
    protected $rvendorModel;
    protected $validation;

    public function __construct()
    {
        $this->projectModel = new ProjectModel();
        $this->vendorModel = new VendorModel();
        $this->rvendorModel = new RolevendorModel();
        $this->pegawaiModel = new PegawaiModel();
        $this->validation =  \Config\Services::validation();
    }

    public function index()
    {

        $data = [
            'controller'        => ucwords('project'),
            'title'             => ucwords('project'),
            'v_a'                 => $this->vendorModel->where('id_role', 3)->findAll(),
            'v_b'                 => $this->vendorModel->where('id_role', 4)->findAll(),
            'v_c'                 => $this->vendorModel->where('id_role', 5)->findAll(),
            'v_d'                 => $this->vendorModel->where('id_role', 6)->findAll(),
            'p_b'                 => $this->pegawaiModel->where('id_role', 8)->findAll(),
            'p_c'                 => $this->pegawaiModel->where('id_role', 9)->findAll(),
            'r_v'                 => $this->rvendorModel->findAll(),
        ];

        return view('project', $data);
    }

    public function getAll()
    {
        $response = $data['data'] = array();

        $result = $this->projectModel->select()->findAll();
        $no = 1;
        foreach ($result as $key => $value) {
            $ops = '<div class="btn-group text-white">';
            $ops .= '<a class="btn btn-dark" onClick="save(' . $value->id_project . ')"><i class="fas fa-pencil-alt"></i></a>';
            $ops .= '<a class="btn btn-secondary" onClick="remove(' . $value->id_project . ')"><i class="fas fa-trash-alt"></i></a>';
            $ops .= '</div>';
            $data['data'][$key] = array(
                $no,
                $value->client_name,
                $value->project_name,
                $value->start_date,
                $value->end_date,
                // link a href
                '<a href="' . $value->link . '" target="_blank">' . $value->link . '</a>',
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

        $id = $this->request->getPost('id_project');

        if ($this->validation->check($id, 'required|numeric')) {

            $data = $this->projectModel->where('id_project', $id)->first();

            return $this->response->setJSON($data);
        } else {
            throw new \CodeIgniter\Exceptions\PageNotFoundException();
        }
    }

    public function add()
    {
        $response = array();

        $fields['id_project'] = $this->request->getPost('id_project');
        $fields['client_name'] = $this->request->getPost('client_name');
        $fields['project_name'] = $this->request->getPost('project_name');
        $fields['start_date'] = $this->request->getPost('start_date');
        $fields['end_date'] = $this->request->getPost('end_date');
        $fields['link'] = $this->request->getPost('link');

        $this->validation->setRules([
            'client_name' => ['label' => 'Client name', 'rules' => 'required|min_length[0]|max_length[100]'],
            'project_name' => ['label' => 'Project name', 'rules' => 'required|min_length[0]|max_length[100]'],
            'start_date' => ['label' => 'Start date', 'rules' => 'permit_empty|min_length[0]'],
            'end_date' => ['label' => 'End date', 'rules' => 'permit_empty|min_length[0]'],
            'link' => ['label' => 'Link', 'rules' => 'required|min_length[0]|max_length[200]']

        ]);

        if ($this->validation->run($fields) == FALSE) {

            $response['success'] = false;
            $response['messages'] = $this->validation->getErrors(); //Show Error in Input Form

        } else {
            $myTime = new Time($this->request->getPost('end_date'));
            $currTime = new Time($this->request->getPost('start_date'));

            if ($this->projectModel->insert($fields)) {
                $db = db_connect();
                $vendor = [
                    [
                        'vendor_id' => $this->request->getPost('v1'),
                        'project_id' => $this->projectModel->getInsertID(),
                        'start_date' => $this->request->getPost('start_date'),
                        'end_date' => $myTime->modify('-4 Days'),
                        'status' => 0
                    ],
                    [
                        'vendor_id' => $this->request->getPost('v2'),
                        'project_id' => $this->projectModel->getInsertID(),
                        'start_date' => $this->request->getPost('start_date'),
                        'end_date' => $myTime->modify('-4 Days'),
                        'status' => 0
                    ],
                    [
                        'vendor_id' => $this->request->getPost('v3'),
                        'project_id' => $this->projectModel->getInsertID(),
                        'start_date' => $this->request->getPost('start_date'),
                        'end_date' => $myTime->modify('-4 Days'),
                        'status' => 0
                    ],
                    [
                        'vendor_id' => $this->request->getPost('v4'),
                        'project_id' => $this->projectModel->getInsertID(),
                        'start_date' => $this->request->getPost('start_date'),
                        'end_date' => $myTime->modify('-4 Days'),
                        'status' => 0
                    ]
                ];

                $v1 = $this->vendorModel->find($this->request->getPost('v1'));
                $v2 = $this->vendorModel->find($this->request->getPost('v2'));
                $v3 = $this->vendorModel->find($this->request->getPost('v3'));
                $v4 = $this->vendorModel->find($this->request->getPost('v4'));

                $ass = 'New Assigment ';
                $ass .= date('Y-m-d H:i:s');
                $ass .= ' ';

                Project::sendMailVendor($v1->email, $ass, $v1->vendor_name, $this->request->getPost('client_name'), $currTime, $myTime->modify('-4 Days'));
                Project::sendMailVendor($v2->email, $ass, $v2->vendor_name, $this->request->getPost('client_name'), $currTime, $myTime->modify('-4 Days'));
                Project::sendMailVendor($v3->email, $ass, $v3->vendor_name, $this->request->getPost('client_name'), $currTime, $myTime->modify('-4 Days'));
                Project::sendMailVendor($v4->email, $ass, $v4->vendor_name, $this->request->getPost('client_name'), $currTime, $myTime->modify('-4 Days'));

                $db->table('project_vendor')->insertBatch($vendor);

                $pegawai = [
                    [
                        'pegawai_id' => $this->request->getPost('p2'),
                        'project_id' => $this->projectModel->getInsertID(),
                        'start_date' => $myTime->modify('-3 Days'),
                        'end_date' => $this->request->getPost('end_date'),
                        'status' => 0
                    ],
                    [
                        'pegawai_id' => $this->request->getPost('p3'),
                        'project_id' => $this->projectModel->getInsertID(),
                        'start_date' => $myTime->modify('-3 Days'),
                        'end_date' => $this->request->getPost('end_date'),
                        'status' => 0
                    ]
                ];

                $p1 = $this->pegawaiModel->find($this->request->getPost('p2'));
                $p2 = $this->pegawaiModel->find($this->request->getPost('p3'));

                Project::sendMailPegawai($p1->email, $ass, $p1->pegawai_name, $this->request->getPost('client_name'), $myTime->modify('-3 Days'), $myTime);
                Project::sendMailPegawai($p2->email, $ass, $p2->pegawai_name, $this->request->getPost('client_name'), $myTime->modify('-3 Days'), $myTime);

                $db->table('project_pegawai')->insertBatch($pegawai);

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

        $fields['id_project'] = $this->request->getPost('id_project');
        $fields['client_name'] = $this->request->getPost('client_name');
        $fields['project_name'] = $this->request->getPost('project_name');
        $fields['start_date'] = $this->request->getPost('start_date');
        $fields['end_date'] = $this->request->getPost('end_date');
        $fields['link'] = $this->request->getPost('link');
        $fields['status'] = $this->request->getPost('status');

        $this->validation->setRules([
            'client_name' => ['label' => 'Client name', 'rules' => 'required|min_length[0]|max_length[100]'],
            'project_name' => ['label' => 'Project name', 'rules' => 'required|min_length[0]|max_length[100]'],
            'start_date' => ['label' => 'Start date', 'rules' => 'permit_empty|min_length[0]'],
            'end_date' => ['label' => 'End date', 'rules' => 'permit_empty|min_length[0]'],
            'link' => ['label' => 'Link', 'rules' => 'required|min_length[0]|max_length[200]']
        ]);

        if ($this->validation->run($fields) == FALSE) {

            $response['success'] = false;
            $response['messages'] = $this->validation->getErrors(); //Show Error in Input Form

        } else {
            $myTime = new Time($this->request->getPost('end_date'));

            if ($this->projectModel->update($fields['id_project'], $fields)) {
                $db = db_connect();
                $vendor = [
                    'start_date' => $this->request->getPost('start_date'),
                    'end_date' => $myTime->modify('-4 Days')
                ];

                $db->table('project_vendor')->where('project_id', $this->request->getPost('id_project'))->update($vendor);

                $pegawai = [
                    'start_date' => $myTime->modify('-3 Days'),
                    'end_date' => $this->request->getPost('end_date')
                ];

                $db->table('project_pegawai')->where('project_id', $this->request->getPost('id_project'))->update($pegawai);

                $response['success'] = true;
                $response['messages'] = lang("App.insert-success");
            } else {

                $response['success'] = false;
                $response['messages'] = lang("App.insert-error");
            }
        }

        return $this->response->setJSON($response);
    }


    public function remove()
    {
        $response = array();

        $id = $this->request->getPost('id_project');

        if (!$this->validation->check($id, 'required|numeric')) {

            throw new \CodeIgniter\Exceptions\PageNotFoundException();
        } else {

            if ($this->projectModel->where('id_project', $id)->delete()) {

                $response['success'] = true;
                $response['messages'] = lang("App.delete-success");
            } else {

                $response['success'] = false;
                $response['messages'] = lang("App.delete-error");
            }
        }

        return $this->response->setJSON($response);
    }

    public static function sendMailVendor($v, $subject,  $vendorName, $clientName, $start, $deadline)
    {
        $email = \Config\Services::email();
        $message = '
		<!DOCTYPE html>
            <html lang="en" xmlns="http://www.w3.org/1999/xhtml" xmlns:v="urn:schemas-microsoft-com:vml"            xmlns:o="urn:schemas-microsoft-com:office:office">

            <head>
                <meta charset="utf-8">
                <meta name="viewport" content="width=device-width">
                <meta http-equiv="X-UA-Compatible" content="IE=edge">
                <meta name="x-apple-disable-message-reformatting">
                <title>Verifikasi Akun Anda</title>

                <link href="https://fonts.googleapis.com/css?family=Poppins:200,300,400,500,600,700" rel="stylesheet">

                <style>
                    html,
                    body {
                        margin: 0 auto !important;
                        padding: 0 !important;
                        height: 100% !important;
                        width: 100% !important;
                        background: #f1f1f1;
                    }
                
                
                    * {
                        -ms-text-size-adjust: 100%;
                        -webkit-text-size-adjust: 100%;
                    }
                
                    div[style*="margin: 16px 0"] {
                        margin: 0 !important;
                    }
                
                    table,
                    td {
                        mso-table-lspace: 0pt !important;
                        mso-table-rspace: 0pt !important;
                    }
                
                    table {
                        border-spacing: 0 !important;
                        border-collapse: collapse !important;
                        table-layout: fixed !important;
                        margin: 0 auto !important;
                    }
                
                    /* What it does: Uses a better rendering method when resizing images in IE. */
                    img {
                        -ms-interpolation-mode: bicubic;
                    }
                
                    /* What it does: Prevents Windows 10 Mail from underlining links despite inline CSS. Styles for underlined links should be          inline. */
                    a {
                        text-decoration: none;
                    }
                
                    /* What it does: A work-around for email clients meddling in triggered links. */
                    *[x-apple-data-detectors],
                    /* iOS */
                    .unstyle-auto-detected-links *,
                    .aBn {
                        border-bottom: 0 !important;
                        cursor: default !important;
                        color: inherit !important;
                        text-decoration: none !important;
                        font-size: inherit !important;
                        font-family: inherit !important;
                        font-weight: inherit !important;
                        line-height: inherit !important;
                    }
                
                    /* What it does: Prevents Gmail from displaying a download button on large, non-linked images. */
                    .a6S {
                        display: none !important;
                        opacity: 0.01 !important;
                    }
                
                    /* What it does: Prevents Gmail from changing the text color in conversation threads. */
                    .im {
                        color: inherit !important;
                    }
                
                    img.g-img+div {
                        display: none !important;
                    }
                
                
                    /* iPhone 4, 4S, 5, 5S, 5C, and 5SE */
                    @media only screen and (min-device-width: 320px) and (max-device-width: 374px) {
                        u~div .email-container {
                            min-width: 320px !important;
                        }
                    }
                
                    /* iPhone 6, 6S, 7, 8, and X */
                    @media only screen and (min-device-width: 375px) and (max-device-width: 413px) {
                        u~div .email-container {
                            min-width: 375px !important;
                        }
                    }
                
                    /* iPhone 6+, 7+, and 8+ */
                    @media only screen and (min-device-width: 414px) {
                        u~div .email-container {
                            min-width: 414px !important;
                        }
                    }
                </style>
                
                <!-- CSS Reset : END -->
                
                <!-- Progressive Enhancements : BEGIN -->
                <style>
                    .primary {
                        background: #17bebb;
                    }
                
                    .bg_white {
                        background: #ffffff;
                    }
                
                    .bg_light {
                        background: #f7fafa;
                    }
                
                    .bg_black {
                        background: #000000;
                    }
                
                    .bg_dark {
                        background: rgba(0, 0, 0, .8);
                    }
                
                    .email-section {
                        padding: 2.5em;
                    }
                
                    /*BUTTON*/
                    .btn {
                        padding: 10px 15px;
                        display: inline-block;
                    }
                
                    .btn.btn-primary {
                        border-radius: 5px;
                        background: #17bebb;
                        color: #ffffff;
                    }
                
                    .btn.btn-white {
                        border-radius: 5px;
                        background: #ffffff;
                        color: #000000;
                    }
                
                    .btn.btn-white-outline {
                        border-radius: 5px;
                        background: transparent;
                        border: 1px solid #fff;
                        color: #fff;
                    }
                
                    .btn.btn-black-outline {
                        border-radius: 0px;
                        background: transparent;
                        border: 2px solid #000;
                        color: #000;
                        font-weight: 700;
                    }
                
                    .btn-custom {
                        color: rgba(0, 0, 0, .3);
                        text-decoration: underline;
                    }
                
                    h1,
                    h2,
                    h3,
                    h4,
                    h5,
                    h6 {
                        font-family: "Poppins", sans-serif;
                        color: #000000;
                        margin-top: 0;
                        font-weight: 400;
                    }
                
                    body {
                        font-family: "Poppins", sans-serif;
                        font-weight: 400;
                        font-size: 15px;
                        line-height: 1.8;
                        color: rgba(0, 0, 0, .4);
                    }
                
                    a {
                        color: #17bebb;
                    }
                
                    table {}
                
                    /*LOGO*/
                
                    .logo h1 {
                        margin: 0;
                    }
                
                    .logo h1 a {
                        color: #17bebb;
                        font-size: 24px;
                        font-weight: 700;
                        font-family: "Poppins", sans-serif;
                    }
                
                    /*HERO*/
                    .hero {
                        position: relative;
                        z-index: 0;
                    }
                
                    .hero .text {
                        color: rgba(0, 0, 0, .3);
                    }
                
                    .hero .text h2 {
                        color: #000;
                        font-size: 28px;
                        margin-bottom: 0;
                        line-height: 1.4;
                    }
                
                    .hero .text h3 {
                        font-size: 24px;
                        font-weight: 300;
                    }
                
                    .hero .text h2 span {
                        font-weight: 600;
                        color: #000;
                    }
                
                    .text-author {
                        bordeR: 1px solid rgba(0, 0, 0, .05);
                        max-width: 50%;
                        margin: 0 auto;
                        padding: 2em;
                    }
                
                    .text-author img {
                        border-radius: 50%;
                        padding-bottom: 20px;
                    }
                
                    .text-author h3 {
                        margin-bottom: 0;
                    }
                
                    ul.social {
                        padding: 0;
                    }
                
                    ul.social li {
                        display: inline-block;
                        margin-right: 10px;
                    }
                
                    /*FOOTER*/
                
                    .footer {
                        border-top: 1px solid rgba(0, 0, 0, .05);
                        color: rgba(0, 0, 0, .5);
                    }
                
                    .footer .heading {
                        color: #000;
                        font-size: 20px;
                    }
                
                    .footer ul {
                        margin: 0;
                        padding: 0;
                    }
                
                    .footer ul li {
                        list-style: none;
                        margin-bottom: 10px;
                    }
                
                    .footer ul li a {
                        color: rgba(0, 0, 0, 1);
                    }
                
                
                    @media screen and (max-width: 500px) {}
                </style>
                
                
            </head>
                
            <body width="100%" style="margin: 0; padding: 0 !important; mso-line-height-rule: exactly; background-color: #f1f1f1;">
                <center style="width: 100%; background-color: #f1f1f1;">
                    <div style="max-width: 600px; margin: 0 auto;" class="email-container">
                        <!-- BEGIN BODY -->
                        <table align="center" role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%" style="margin: auto;">
                            <tr>
                                <td class="bg_light" style="text-align: center; margin-top:15px;">
                                    <h1 style="margin-top: 20px;">Task Schedule</h1>
                                </td>
                            </tr>
                        </table>
                        <table align="center" role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%" style="margin: auto;">
                            <tr>
                                <td valign="top" class="bg_white" style="padding: 1em 2.5em 0 2.5em;">
                                    <table role="presentation" border="0" cellpadding="0" cellspacing="0" width="100%">
                                        <tr>
                                            <td>
                                                <center>
                                                    <img src="https://i.imgur.com/fC0SoNN.gif" alt="" width="500px">
                                                </center>
                                            </td>
                                        </tr>
                                    </table>
                                </td>
                            </tr><!-- end tr -->
                            <tr>
                                <td valign="middle" class="hero bg_white" style="padding: 2em 0 4em 0;">
                                    <table role="presentation" border="0" cellpadding="0" cellspacing="0" width="100%">
                                        <tr>
                                            <td style="padding: 0 2.5em; text-align: center; padding-bottom: 3em;">
                                                <div class="text">
                                                    <h2>Hai vendor ' . $vendorName . ', ada project baru dari client ' . $clientName . '</h2>
                                                    <br>
                                                    <h4 style="color:black;">Waktu Pelaksanaan</h4>
                                                    <h4 style="color:black;">' . $start . ' - ' . $deadline . '</h4>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td style="text-align: center;">
                                                <div class="text-author">
                                                    <p><a href="' . base_url() . '" class="btn btn-primary">Menuju ke Aplikasi</a></p>
                                                </div>
                                            </td>
                                        </tr>
                                    </table>
                                </td>
                            </tr><!-- end tr -->
                            <!-- 1 Column Text + Button : END -->
                        </table>
                        <table align="center" role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%" style="margin: auto;">
                            <tr>
                                <td class="bg_light" style="text-align: center;">
                                    <p>&copy; Copyright <strong><span>Task Schedule</span></strong>. Seluruh hak cipta</p>
                                </td>
                            </tr>
                        </table>
                
                    </div>
                </center>
            </body>
                
            </html>
		';
        $email->setTo($v);
        $email->setSubject($subject);
        $email->setMessage($message);
        $email->send();

        return true;
    }

    public static function sendMailPegawai($v, $subject, $pegawaiName, $clientName, $start, $deadline)
    {
        $email = \Config\Services::email();
        $message = '
		<!DOCTYPE html>
            <html lang="en" xmlns="http://www.w3.org/1999/xhtml" xmlns:v="urn:schemas-microsoft-com:vml"            xmlns:o="urn:schemas-microsoft-com:office:office">

            <head>
                <meta charset="utf-8">
                <meta name="viewport" content="width=device-width">
                <meta http-equiv="X-UA-Compatible" content="IE=edge">
                <meta name="x-apple-disable-message-reformatting">
                <title>Verifikasi Akun Anda</title>

                <link href="https://fonts.googleapis.com/css?family=Poppins:200,300,400,500,600,700" rel="stylesheet">

                <style>
                    html,
                    body {
                        margin: 0 auto !important;
                        padding: 0 !important;
                        height: 100% !important;
                        width: 100% !important;
                        background: #f1f1f1;
                    }
                
                
                    * {
                        -ms-text-size-adjust: 100%;
                        -webkit-text-size-adjust: 100%;
                    }
                
                    div[style*="margin: 16px 0"] {
                        margin: 0 !important;
                    }
                
                    table,
                    td {
                        mso-table-lspace: 0pt !important;
                        mso-table-rspace: 0pt !important;
                    }
                
                    table {
                        border-spacing: 0 !important;
                        border-collapse: collapse !important;
                        table-layout: fixed !important;
                        margin: 0 auto !important;
                    }
                
                    /* What it does: Uses a better rendering method when resizing images in IE. */
                    img {
                        -ms-interpolation-mode: bicubic;
                    }
                
                    /* What it does: Prevents Windows 10 Mail from underlining links despite inline CSS. Styles for underlined links should be          inline. */
                    a {
                        text-decoration: none;
                    }
                
                    /* What it does: A work-around for email clients meddling in triggered links. */
                    *[x-apple-data-detectors],
                    /* iOS */
                    .unstyle-auto-detected-links *,
                    .aBn {
                        border-bottom: 0 !important;
                        cursor: default !important;
                        color: inherit !important;
                        text-decoration: none !important;
                        font-size: inherit !important;
                        font-family: inherit !important;
                        font-weight: inherit !important;
                        line-height: inherit !important;
                    }
                
                    /* What it does: Prevents Gmail from displaying a download button on large, non-linked images. */
                    .a6S {
                        display: none !important;
                        opacity: 0.01 !important;
                    }
                
                    /* What it does: Prevents Gmail from changing the text color in conversation threads. */
                    .im {
                        color: inherit !important;
                    }
                
                    img.g-img+div {
                        display: none !important;
                    }
                
                
                    /* iPhone 4, 4S, 5, 5S, 5C, and 5SE */
                    @media only screen and (min-device-width: 320px) and (max-device-width: 374px) {
                        u~div .email-container {
                            min-width: 320px !important;
                        }
                    }
                
                    /* iPhone 6, 6S, 7, 8, and X */
                    @media only screen and (min-device-width: 375px) and (max-device-width: 413px) {
                        u~div .email-container {
                            min-width: 375px !important;
                        }
                    }
                
                    /* iPhone 6+, 7+, and 8+ */
                    @media only screen and (min-device-width: 414px) {
                        u~div .email-container {
                            min-width: 414px !important;
                        }
                    }
                </style>
                
                <!-- CSS Reset : END -->
                
                <!-- Progressive Enhancements : BEGIN -->
                <style>
                    .primary {
                        background: #17bebb;
                    }
                
                    .bg_white {
                        background: #ffffff;
                    }
                
                    .bg_light {
                        background: #f7fafa;
                    }
                
                    .bg_black {
                        background: #000000;
                    }
                
                    .bg_dark {
                        background: rgba(0, 0, 0, .8);
                    }
                
                    .email-section {
                        padding: 2.5em;
                    }
                
                    /*BUTTON*/
                    .btn {
                        padding: 10px 15px;
                        display: inline-block;
                    }
                
                    .btn.btn-primary {
                        border-radius: 5px;
                        background: #17bebb;
                        color: #ffffff;
                    }
                
                    .btn.btn-white {
                        border-radius: 5px;
                        background: #ffffff;
                        color: #000000;
                    }
                
                    .btn.btn-white-outline {
                        border-radius: 5px;
                        background: transparent;
                        border: 1px solid #fff;
                        color: #fff;
                    }
                
                    .btn.btn-black-outline {
                        border-radius: 0px;
                        background: transparent;
                        border: 2px solid #000;
                        color: #000;
                        font-weight: 700;
                    }
                
                    .btn-custom {
                        color: rgba(0, 0, 0, .3);
                        text-decoration: underline;
                    }
                
                    h1,
                    h2,
                    h3,
                    h4,
                    h5,
                    h6 {
                        font-family: "Poppins", sans-serif;
                        color: #000000;
                        margin-top: 0;
                        font-weight: 400;
                    }
                
                    body {
                        font-family: "Poppins", sans-serif;
                        font-weight: 400;
                        font-size: 15px;
                        line-height: 1.8;
                        color: rgba(0, 0, 0, .4);
                    }
                
                    a {
                        color: #17bebb;
                    }
                
                    table {}
                
                    /*LOGO*/
                
                    .logo h1 {
                        margin: 0;
                    }
                
                    .logo h1 a {
                        color: #17bebb;
                        font-size: 24px;
                        font-weight: 700;
                        font-family: "Poppins", sans-serif;
                    }
                
                    /*HERO*/
                    .hero {
                        position: relative;
                        z-index: 0;
                    }
                
                    .hero .text {
                        color: rgba(0, 0, 0, .3);
                    }
                
                    .hero .text h2 {
                        color: #000;
                        font-size: 28px;
                        margin-bottom: 0;
                        line-height: 1.4;
                    }
                
                    .hero .text h3 {
                        font-size: 24px;
                        font-weight: 300;
                    }
                
                    .hero .text h2 span {
                        font-weight: 600;
                        color: #000;
                    }
                
                    .text-author {
                        bordeR: 1px solid rgba(0, 0, 0, .05);
                        max-width: 50%;
                        margin: 0 auto;
                        padding: 2em;
                    }
                
                    .text-author img {
                        border-radius: 50%;
                        padding-bottom: 20px;
                    }
                
                    .text-author h3 {
                        margin-bottom: 0;
                    }
                
                    ul.social {
                        padding: 0;
                    }
                
                    ul.social li {
                        display: inline-block;
                        margin-right: 10px;
                    }
                
                    /*FOOTER*/
                
                    .footer {
                        border-top: 1px solid rgba(0, 0, 0, .05);
                        color: rgba(0, 0, 0, .5);
                    }
                
                    .footer .heading {
                        color: #000;
                        font-size: 20px;
                    }
                
                    .footer ul {
                        margin: 0;
                        padding: 0;
                    }
                
                    .footer ul li {
                        list-style: none;
                        margin-bottom: 10px;
                    }
                
                    .footer ul li a {
                        color: rgba(0, 0, 0, 1);
                    }
                
                
                    @media screen and (max-width: 500px) {}
                </style>
                
                
            </head>
                
            <body width="100%" style="margin: 0; padding: 0 !important; mso-line-height-rule: exactly; background-color: #f1f1f1;">
                <center style="width: 100%; background-color: #f1f1f1;">
                    <div style="max-width: 600px; margin: 0 auto;" class="email-container">
                        <!-- BEGIN BODY -->
                        <table align="center" role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%" style="margin: auto;">
                            <tr>
                                <td class="bg_light" style="text-align: center; margin-top:15px;">
                                    <h1 style="margin-top: 20px;">Task Schedule</h1>
                                </td>
                            </tr>
                        </table>
                        <table align="center" role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%" style="margin: auto;">
                            <tr>
                                <td valign="top" class="bg_white" style="padding: 1em 2.5em 0 2.5em;">
                                    <table role="presentation" border="0" cellpadding="0" cellspacing="0" width="100%">
                                        <tr>
                                            <td>
                                                <center>
                                                    <img src="https://i.imgur.com/fC0SoNN.gif" alt="" width="500px">
                                                </center>
                                            </td>
                                        </tr>
                                    </table>
                                </td>
                            </tr><!-- end tr -->
                            <tr>
                                <td valign="middle" class="hero bg_white" style="padding: 2em 0 4em 0;">
                                    <table role="presentation" border="0" cellpadding="0" cellspacing="0" width="100%">
                                        <tr>
                                            <td style="padding: 0 2.5em; text-align: center; padding-bottom: 3em;">
                                                <div class="text">
                                                    <h2>Hai pegawai ' . $pegawaiName . ', ada project baru dari client ' . $clientName . '</h2>
                                                    <br>
                                                    <h4 style="color:black;">Waktu Pelaksanaan</h4>
                                                    <h4 style="color:black;">' . $start . ' - ' . $deadline . '</h4>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td style="text-align: center;">
                                                <div class="text-author">
                                                    <p><a href="' . base_url() . '" class="btn btn-primary">Menuju ke Aplikasi</a></p>
                                                </div>
                                            </td>
                                        </tr>
                                    </table>
                                </td>
                            </tr><!-- end tr -->
                            <!-- 1 Column Text + Button : END -->
                        </table>
                        <table align="center" role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%" style="margin: auto;">
                            <tr>
                                <td class="bg_light" style="text-align: center;">
                                    <p>&copy; Copyright <strong><span>Task Schedule</span></strong>. Seluruh hak cipta</p>
                                </td>
                            </tr>
                        </table>
                
                    </div>
                </center>
            </body>
                
            </html>
		';
        $email->setTo($v);
        $email->setSubject($subject);
        $email->setMessage($message);
        $email->send();

        return true;
    }
}
