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

    public function lupaPassword()
    {
        $data = [
            'title' => 'Lupa Password'
        ];

        return view('lupa_password', $data);
    }

    public function sendMail()
    {
        $email = $this->request->getPost('email');
        $userType= $this->request->getPost('role');
        $model = $userType == 'Admin' ? $this->Admin : ($userType == 'Vendor' ? $this->Vendor : $this->Pegawai);

        $findUser = $model->where('email', $email)->first();
        if (!$findUser) {
            return redirect()->to('/login/' . $userType . '/lupapassword')->withInput()->with('error', 'Email tidak terdaftar');
        }

        $token = bin2hex(random_bytes(50));
        $model->update( $userType == 'Admin' ? $findUser->id_admin : ($userType == 'Pegawai' ? $findUser->id_pegawai : $findUser->id_vendor), [
            'reset_token' => $token,
            'reset_at' => date('Y-m-d H:i:s')
        ]);

        $mail = \Config\Services::email();
        $message = '
        <p>Seseorang meminta pengaturan ulang kata sandi di alamat email ini ' . site_url() . '</p> <p>To reset the password use this code or URL and follow the instructions.</p> <p>Kode mu: ' . $token  . '</p> <p>Mengunjungi <a href="' . site_url('login/'. $userType .'/resetPassword') . '?token=' . $token . '">Formulir Reset</a>.</p><br><p>Jika Anda tidak meminta reset kata sandi, Anda dapat dengan aman mengabaikan email ini.</p>
        ';

        $mail->setTo($email);
        $mail->setSubject('Reset Password');
        $mail->setMessage($message);

        if ($mail->send()) {
            return redirect()->to('/login/' . $userType)->with('success', 'Silahkan cek email untuk reset password');
        } else {
            return redirect()->to('/login/' . $userType . '/lupapassword')->withInput()->with('error', 'Gagal mengirim email');
        }

    }

    public function resetPassword()
    {
        $token = $this->request->getGet('token');
        $userType = $this->request->uri->getSegment(2);
        $model = $userType == 'Admin' ? $this->Admin : ($userType == 'Vendor' ? $this->Vendor : $this->Pegawai);

        $findUser = $model->where('reset_token', $token)->first();
        if (!$findUser) {
            return redirect()->to('/login/' . $userType . '/lupapassword')->withInput()->with('error', 'Token tidak valid');
        }

        $data = [
            'title' => 'Reset Password',
            'token' => $token,
            'userType' => $userType
        ];

        return view('reset_password', $data);
    }

    public function resetPasswordAction()
    {
        $token = $this->request->getPost('token');
        $role = $this->request->getPost('role');
        $password = $this->request->getPost('password');
        $confirmPassword = $this->request->getPost('cpassword');

        if ($password != $confirmPassword) {
            return redirect()->to('/login/' . $role . '/resetPassword?token=' . $token)->withInput()->with('error', 'Password tidak sama');
        }

        $userType = $this->request->getPost('role');
        $model = $userType == 'Admin' ? $this->Admin : ($userType == 'Vendor' ? $this->Vendor : $this->Pegawai);

        $findUser = $model->where('reset_token', $token)->first();
        if (!$findUser) {
            return redirect()->to('/login/' . $role . '/lupapassword')->withInput()->with('error', 'Token tidak valid');
        }

        $model->update($userType == 'Admin' ? $findUser->id_admin : ($userType == 'Pegawai' ? $findUser->id_pegawai : $findUser->id_vendor), [
            'password' => password_hash((string)$password, PASSWORD_DEFAULT),
            'reset_token' => null,
            'reset_at' => null
        ]
        );

        return redirect()->to('/login/' . $userType)->with('success', 'Password berhasil diubah');
    }

    

}
