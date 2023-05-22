<?php

class Kategori_event extends MY_Controller
{
    protected $load_model = 'kategori_model';

    public function index_get()
    {
        $this->require_auth(ROLE_ADMIN);
        $this->payload['title'] = 'Data Kategori Event';
        $this->payload['nav'] = ['master', 'kategori-event'];
        $this->payload['bottom_scripts'] = 'master/kategori-event/script';

        $this->render('master/kategori-event/index');
    }

    public function datatable_get()
    {
        $this->require_auth(ROLE_ADMIN);
        $result = $this->model->datatable();

        $this->render_json($result);
    }

    public function index_post()
    {
        $this->require_auth(ROLE_ADMIN);

        $type = $this->input->post('type');
        switch($type)
        {
            case 'insert':
                $data = $this->input->post(['nama']);
                $this->model->insert_data($data);
            break;
            case 'update':
                $id = $this->input->post('id');
                $data = $this->input->post(['nama']);
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