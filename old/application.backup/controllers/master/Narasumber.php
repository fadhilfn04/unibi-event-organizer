<?php

class Narasumber extends MY_Controller
{
    protected $load_model = 'narasumber_model';

    public function __construct()
	{
		parent::__construct();
		$this->load->library('auth_library', NULL, 'auth');
	}

    public function index_get()
    {
        $this->require_auth(ROLE_SUPERADMIN);
        $this->payload['title'] = 'Data Narasumber';
        $this->payload['nav'] = ['pengguna', 'narasumber'];
        $this->payload['bottom_scripts'] = 'master/narasumber/script';

        $this->render('master/narasumber/index');
    }

    public function datatable_get()
    {
        $this->require_auth(ROLE_SUPERADMIN);
        $result = $this->model->datatable();
        
        $this->render_json($result);
    }

    // public function select2_get()
    // {
    //     $this->require_auth(ROLE_SUPERADMIN);
    //     $result = $this->model->select2();

    //     $this->render_json($result);
    // }

    public function index_post()
    {
        $this->require_auth(ROLE_SUPERADMIN);

        $type = $this->input->post('type');
        switch($type)
        {
            case 'insert':
                $data = $this->input->post([
                    'nama',
                    'pekerjaan',
                    'keahlian',
                    'pendidikan',
                    'email',
                    'no_telepon',
                ]);
                $this->model->insert_data($data);
            break;
            case 'update':
                $id = $this->input->post('id');
                $data = $this->input->post([
                    'nama',
                    'pekerjaan',
                    'keahlian',
                    'pendidikan',
                    'email',
                    'no_telepon',
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