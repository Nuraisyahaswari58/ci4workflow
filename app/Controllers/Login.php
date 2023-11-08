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
            'title' => 'Login Admin',
            'cookie_mail_admin' => isset($_COOKIE['auth']) ? json_decode(aeshash('dec', $_COOKIE['auth'], env('encryption.key')))->email : null,
        ];

        return view('login_admin', $data);
    }

    
    public function vendor()
    {
        $data = [
            'title' => 'Login Vendor',
            'cookie_mail_vendor' => isset($_COOKIE['auth']) ? json_decode(aeshash('dec', $_COOKIE['auth'], env('encryption.key')))->email : null,
        ];

        return view('login_vendor', $data);
    }

    public function pegawai()
    {
        $data = [
            'title' => 'Login Pegawai',
            'cookie_mail_pegawai' => isset($_COOKIE['auth']) ? json_decode(aeshash('dec', $_COOKIE['auth'], env('encryption.key')))->email : null,
        ];
        return view('login_pegawai', $data);
    }

    // public function ceklogin()
    // {
    //     $login = $this->request->getPost('login');
    //     $password = $this->request->getPost('password');
    //     $remember = isset($_POST["remember"]) ? true : false;

    //     $userType = $this->request->uri->getSegment(2);
    //     $model = $userType == 'Admin' ? $this->Admin : ($userType == 'Vendor' ? $this->Vendor : $this->Pegawai);

    //     $findUser = $model->where('username', $login)->orWhere('email', $login)->first();
    //     if (!$findUser || !password_verify((string)$password, $findUser->password)) {
    //         return redirect()->to('/login/' . $userType)->withInput()->with('error', 'Invalid credentials');
    //     }

    //     $params = [
    //                 'id' => $userType == 'Admin' ? $findUser->id_admin : ($userType == 'Pegawai' ? $findUser->id_pegawai : $findUser->id_vendor),
    //                 'username' => $findUser->username,
    //                 'email' => $findUser->email,
    //                 'level' =>  $userType == 'Admin' ? 'Admin' : ($userType == 'Pegawai' ? 'Pegawai' : 'Vendor'),
    //     ];
        
    //     helper('aeshash');

    //   $auth_hash = aeshash('enc', json_encode($params), env('encryption.key'));

    //     session()->set('auth', $params);

    //     if ($remember) {
    //         setcookie('auth', $auth_hash, time() + (86400 * 30), '/');
    //     }

    //     session()->set($params);
    //     return redirect()->to('/dashboard');
       
    // }
    
    public function ceklogin()
{
    $login = $this->request->getPost('login');
    $password = $this->request->getPost('password');
    $remember = isset($_POST["remember"]) ? true : false;

    $userType = $this->request->uri->getSegment(2);
    $model = $userType == 'Admin' ? $this->Admin : ($userType == 'Vendor' ? $this->Vendor : $this->Pegawai);

    $findUser = $model->where('username', $login)->orWhere('email', $login)->findAll();
    if (empty($findUser)) {
        return redirect()->to('/login/' . $userType)->withInput()->with('error', 'Invalid credentials');
    }

    $authenticated = false;

    // Loop through the results to check the password
    foreach ($findUser as $user) {
        if (password_verify((string)$password, $user->password)) {
            $params = [
                'id' => $userType == 'Admin' ? $user->id_admin : ($userType == 'Pegawai' ? $user->id_pegawai : $user->id_vendor),
                'username' => $user->username,
                'email' => $user->email,
                'level' => $userType == 'Admin' ? 'Admin' : ($userType == 'Pegawai' ? 'Pegawai' : 'Vendor'),
            ];

            helper('aeshash');
            $auth_hash = aeshash('enc', json_encode($params), env('encryption.key'));

            session()->set('auth', $params);

            if ($remember) {
                setcookie('auth', $auth_hash, time() + (86400 * 30), '/');
            }

            session()->set($params);
            $authenticated = true;
            break;
        }
    }

    if (!$authenticated) {
        return redirect()->to('/login/' . $userType)->withInput()->with('error', 'Invalid credentials');
    }

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
    try {
        $email = $this->request->getPost('email');
        $userType = $this->request->getPost('role');
        $model = $userType == 'Admin' ? $this->Admin : ($userType == 'Vendor' ? $this->Vendor : $this->Pegawai);

        $findUser = $model->where('email', $email)->first();
        if (!$findUser) {
            return redirect()->to('/login/' . $userType . '/lupapassword')->withInput()->with('error', 'Email tidak terdaftar');
        }

        $token = bin2hex(random_bytes(50));
        $model->update($userType == 'Admin' ? $findUser->id_admin : ($userType == 'Pegawai' ? $findUser->id_pegawai : $findUser->id_vendor), [
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
    } catch (\Exception $e) {
        // Tangani pengecualian di sini, misalnya, catat pesan kesalahan atau lakukan tindakan lain yang sesuai.
        log_message('error', 'Gagal mengirim email: ' . $e->getMessage());
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
