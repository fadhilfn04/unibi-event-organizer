<?php

class Fakultas extends MY_Controller
{
    protected $load_model = 'fakultas_model';

    public function index_get()
    {
        $this->require_auth(ROLE_SUPERADMIN);
        $this->payload['title'] = 'Data Fakultas';
        $this->payload['nav'] = ['master', 'fakultas'];
        $this->payload['bottom_scripts'] = 'master/fakultas/script';

        $this->render('master/fakultas/index');
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
                $data = $this->input->post(['nama', 'kode']);
                $this->model->insert_data($data);
            break;
            case 'update':
                $id = $this->input->post('id');
                $data = $this->input->post(['nama', 'kode']);
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