<?php

class Jurusan extends MY_Controller
{
    protected $load_model = 'jurusan_model';

    public function index_get()
    {
        $this->require_auth(ROLE_SUPERADMIN);
        $this->payload['title'] = 'Data Jurusan';
        $this->payload['nav'] = ['master', 'jurusan'];
        $this->payload['bottom_scripts'] = 'master/jurusan/script';

        $this->load->model('fakultas_model');
        $this->payload['fakultas_list'] = $this->fakultas_model->get_all();

        $this->render('master/jurusan/index');
    }

    public function json_get()
    {
        $data = $this->model->get_with_detail();
        $this->render_json($data);
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

        $type = $this->input->post('type');
        switch($type)
        {
            case 'insert':
                $data = $this->input->post(['nama', 'id_fakultas']);
                $this->model->insert_data($data);
            break;
            case 'update':
                $id = $this->input->post('id');
                $data = $this->input->post(['nama', 'id_fakultas']);
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