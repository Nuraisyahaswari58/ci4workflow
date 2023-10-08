<?php

namespace App\Controllers;
use App\Controllers\BaseController;
use App\Models\VendorModel;
use App\Models\PegawaiModel;
use App\Models\AdminModel;

class Login extends BaseController
{
    protected $Admin;
    protected $Vendor;
    protected $Pegawai;
    
    public function __construct()
	{
	    $this->Admin = new AdminModel();
        $this->Vendor = new VendorModel();
        $this->Pegawai = new PegawaiModel();
		
	}
    public function admin()
    {
        $data = [
            'title' => 'Login Admin'
        ];

        return view('login_admin', $data);
    }

    public function vendor()
    {
        $data = [
            'title' => 'Login Vendor'
        ];

        return view('login_vendor', $data);
    }

    public function pegawai()
    {
        $data = [
            'title' => 'Login Pegawai'

        ];
        return view('login_pegawai', $data);
    }

    public function ceklogin()
    {
        $login = $this->request->getPost('login');
        $password = $this->request->getPost('password');

        $userType = $this->request->uri->getSegment(2);
        $model = $userType == 'Admin' ? $this->Admin : ($userType == 'Vendor' ? $this->Vendor : $this->Pegawai);

        $findUser = $model->where('username', $login)->orWhere('email', $login)->first();
        if (!$findUser || !password_verify((string)$password, $findUser->password)) {
            return redirect()->to('/login/' . $userType)->withInput()->with('error', 'Invalid credentials');
        }

        session()->set([
            'id' => $userType == 'Admin' ? $findUser->id_admin : ($userType == 'Pegawai' ? $findUser->id_pegawai : $findUser->id_vendor),
            'username' => $findUser->username,
            'email' => $findUser->email,
            'level' =>  $userType == 'Admin' ? 'Admin' : ($userType == 'Pegawai' ? 'Pegawai' : 'Vendor'),
        ]);

        return redirect()->to('/dashboard');
    }
    public function logout()
    {
        session()->destroy();
        return redirect()->to('/');
    }
}