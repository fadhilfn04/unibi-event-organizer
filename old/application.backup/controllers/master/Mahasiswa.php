<?php

class Mahasiswa extends MY_Controller
{
    protected $load_model = 'mahasiswa_model';

    public function index_get()
    {
        $this->require_auth(ROLE_ADMIN);
        $this->payload['title'] = 'Data Mahasiswa';
        $this->payload['nav'] = ['mahasiswa'];
        $this->payload['bottom_scripts'] = 'master/mahasiswa/script';

        $this->load->model('fakultas_model');
        $this->payload['fakultas_list'] = $this->fakultas_model->get_all();

        $this->load->model('jurusan_model');
        $this->payload['jurusan_list'] = $this->jurusan_model->get_all();

        $this->render('master/mahasiswa/index');
    }

    public function datatable_get()
    {
        $this->require_auth(ROLE_ADMIN);
        $result = $this->model->datatable();
        
        $this->render_json($result);
    }

    public function select2_get()
    {
        $this->require_auth(ROLE_ADMIN);
        $result = $this->model->select2();

        $this->render_json($result);
    }

    public function index_post()
    {
        $this->require_auth(ROLE_ADMIN);

        $type = $this->input->post('type');
        switch($type)
        {
            case 'insert':
                $data = $this->input->post([
                    'npm',
                    'nama',
                    'email',
                    'id_jurusan',
                    'id_fakultas',
                    'no_telepon',
                ]);
                $this->model->insert_data($data);
            break;
            case 'update':
                $id = $this->input->post('id');
                $data = $this->input->post([
                    'npm',
                    'nama',
                    'email',
                    'id_jurusan',
                    'id_fakultas',
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