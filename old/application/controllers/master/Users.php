<?php

class Users extends MY_Controller
{
    protected $load_model = 'users_model';

    public function __construct()
	{
		parent::__construct();
		$this->load->library('auth_library', NULL, 'auth');
	}

    public function index_get()
    {
        $this->require_auth(ROLE_SUPERADMIN);
        $this->payload['title'] = 'Data Users';
        $this->payload['nav'] = ['master', 'users'];
        $this->payload['bottom_scripts'] = 'master/users/script';

        $this->render('master/users/index');
    }

    public function datatable_get()
    {
        $this->require_auth(ROLE_SUPERADMIN);
        $result = $this->model->datatable();

        $this->render_json($result);
    }

    public function index_post()
    {
        $this->require_auth(ROLE_SUPERADMIN);

        $type       = $this->input->post('type');

        switch($type)
        {
            case 'insert':
                $data = $this->input->post([
                    'username', 
                    'password_hash',
                    'role'
                ]);
                $this->model->insert_data($data);
            break;
            case 'update':
                $id = $this->input->post('id');
                $data = $this->input->post([
                    'username', 
                    'password_hash',
                    'role'
                ]);
                $this->model->update_data($id, $data);
            break;
            case 'delete':
                $id = $this->input->post('id');
                $this->model->delete_data($id);
            break;
            default:
                throw new Exception('Type not found', ERR_INVALID_FORM_DATA);
            break;
        }

        $this->render_json(['code' => 1, 'message' => 'OK']);
    }
}