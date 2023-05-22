<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Auth extends MY_Controller {

	// Menggunakan layout di file layouts/auth/index
	protected $layout = 'layouts/auth/index';

	public function __construct()
	{
		parent::__construct();
		$this->load->library('auth_library', NULL, 'auth');
	}
	
	// Redirect ke login
	public function index()
	{
		return redirect('auth/login');
	}

	// Routing untuk ke auth/login
	public function login_get()
	{
		$this->require_guest();
		$this->payload['title'] = 'Login';
		$this->render('login/index');
	}

	public function register_post()
	{
		$this->handle_as_json = TRUE;
		$params = $this->input->post([
			'npm',
			'nama',
			'password',
			'confirm_password',
			'email',
			'id_jurusan',
			'no_telepon',
		]);

		extract($params);

		if(empty($npm)) throw new Exception('NPM tidak boleh kosong', ERR_INVALID_FORM_DATA);
		if(empty($nama)) throw new Exception('Nama tidak boleh kosong', ERR_INVALID_FORM_DATA);
		if(empty($email)) throw new Exception('Email tidak boleh kosong', ERR_INVALID_FORM_DATA);
		if(empty($id_jurusan)) throw new Exception('Jurusan tidak boleh kosong', ERR_INVALID_FORM_DATA);
		if(empty($no_telepon)) throw new Exception('Nomor telepon tidak boleh kosong', ERR_INVALID_FORM_DATA);

		if(empty($password))
		{
			throw new Exception('Password tidak boleh kosong', ERR_INVALID_FORM_DATA);
		}
		elseif($password != $confirm_password)
		{
			throw new Exception('Konfirmasi password tidak sama', ERR_INVALID_FORM_DATA);
		}

		$this->load->model('user_model');
		$this->load->library('jwt_library');

		$user = $this->user_model->register($params);
		
		$jti = bin2hex(random_bytes(4));

		$this->render_json([
			'token' => $this->jwt_library->encode([
				'jti' => $jti,
				'user_id' => $user->id,
				'id_mahasiswa' => $user->id_mahasiswa,
			]),
		]);
	}

	// Proses untuk login
	public function login_post()
	{
		$this->require_guest();
		$username = $this->input->post('username');
		$password = $this->input->post('password');

		$this->auth->login($username, $password);
		$redirect_to = $this->input->get('r') ?? 'dashboard';
		redirect($redirect_to);
	}

	// Proses untuk logout
	public function logout_post()
	{
		$this->require_auth();
		$this->auth->logout();
		redirect('auth/login');
	}

	public function login_jwt_post()
	{
		$this->handle_as_json = TRUE;
		$username = $this->input->post('username');
		$password = $this->input->post('password');

		$user = $this->auth->login($username, $password);
		$this->load->library('jwt_library');
		
		$jti = bin2hex(random_bytes(4));

		$this->render_json([
			'token' => $this->jwt_library->encode([
				'jti' => $jti,
				'user_id' => $user->id,
				'id_mahasiswa' => $user->id_mahasiswa,
			]),
		]);
	}

	public function public_key_get()
	{
		$key = file_get_contents(FCPATH . 'storage/keys/jwt.key.pub');
		$this->output->set_content_type('text/plain')
			->set_output($key);
	}

	// Routing untuk halaman ubah password
	public function ubah_password_get()
	{
		$this->require_auth();
		if($this->session->userdata('role') == ROLE_SUPERADMIN)
		{
			$this->layout = 'layouts/dashboard/index';
		}
		elseif($this->session->userdata('role') == ROLE_MAHASISWA)
		{
			$this->layout = 'layouts/mahasiswa/index';
		}
		$this->payload['title'] = 'Ubah Password';
		$this->payload['bottom_scripts'] = 'ubah_password/script';
		$this->render('ubah_password/index');
	}

	public function daftar_get($token = NULL)
	{
		$this->require_guest();

		$this->load->model('Mahasiswa_model', 'mahasiswa');
		$mahasiswa = $this->mahasiswa->get_by_active_register_token($token);

		if(empty($mahasiswa))
		{
			return show_404();
		}

		$this->payload['title'] = 'Daftar';
		$this->render('daftar/index');
	}

	public function daftar_post($token = NULL)
	{
		$this->require_guest();

		$params = $this->input->post(['npm', 'password', 'confirm_password']);
		extract($params);

		if(empty($password))
		{
			throw new Exception('Password tidak boleh kosong', ERR_INVALID_FORM_DATA);
		}
		elseif($password != $confirm_password)
		{
			throw new Exception('Konfirmasi password tidak sama', ERR_INVALID_FORM_DATA);
		}

		$this->load->model('Mahasiswa_model', 'mahasiswa');
		$mahasiswa = $this->mahasiswa->get_by_active_register_token($token);

		if($mahasiswa->npm != $npm)
		{
			throw new Exception('NPM salah', ERR_INVALID_FORM_DATA);
		}
		// var_dump($mahasiswa);exit();

		$this->mahasiswa->create_account($mahasiswa, $password);
		$this->auth->login($mahasiswa->npm, $password);

		redirect('dashboard');
	}

	// Proses untuk ubah password
	public function ubah_password_post()
	{
		$this->require_auth();

		$old_password     = $this->input->post('old_password');
		$password         = $this->input->post('password');
		$confirm_password = $this->input->post('confirm_password');

		if(empty($old_password))
		{
			throw new Exception('Password lama tidak boleh kosong', ERR_INVALID_FORM_DATA);
		}

		if(empty($password))
		{
			throw new Exception('Password baru tidak boleh kosong', ERR_INVALID_FORM_DATA);
		}

		if(empty($confirm_password))
		{
			throw new Exception('Konfirmasi password tidak boleh kosong', ERR_INVALID_FORM_DATA);
		}

		if($password != $confirm_password)
		{
			throw new Exception('Konfirmasi password tidak sama', ERR_INVALID_FORM_DATA);
		}

		$this->load->model('user_model');
		$user = $this->user_model->get_one($this->session->userdata('id'));

		if(empty($user))
		{
			throw new Exception('User tidak ditemukan', ERR_UNEXPECTED);
		}

		if(!$this->auth->verify_password($old_password, $user->password_hash))
		{
			throw new Exception('Password lama salah', ERR_INCORRECT_PASSWORD);
		}

		$new_hash = $this->auth->password_hash($password);
		$this->user_model->update_data($user->id, [
			'password_hash' => $new_hash,
		]);

		$this->session->set_flashdata([
			'has_alert' => TRUE,
			'alert_type' => 'success',
			'alert_message' => 'Password berhasil diubah',
		]);

		redirect('auth/ubah-password');
	}
}
