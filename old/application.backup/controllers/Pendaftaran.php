<?php

class Pendaftaran extends MY_Controller
{
    protected $load_model = 'peserta_model';

    public function index_get($id_event)
    {
        $this->require_auth(ROLE_ADMIN);
        $this->payload['title'] = 'Data Pendaftar';
        $this->payload['nav'] = ['event'];
        $this->payload['bottom_scripts'] = 'event/scripts/pendaftaran';
        $this->payload['id_event'] = $id_event;

        $this->render('event/pendaftaran');
    }

    public function datatable_get($id_event)
    {
        $this->require_auth(ROLE_ADMIN);
        $result = $this->model->datatable_pendaftaran($id_event);

        $this->render_json($result);
    }

    public function index_post($id_event)
    {
        $this->require_auth(ROLE_ADMIN);

        $type = $this->input->post('type');
        switch($type)
        {
            case 'delete':
                $id = $this->input->post('id');
                $this->model->delete_data($id);
            break;
            case 'verification':
                $id = $this->input->post('id');
                $this->model->verifikasi($id);
            break;
            default:
                throw new Exception('Type not found', ERR_INVALID_FORM_DATA);
            break;
        }

        $this->render_json(['code' => 1, 'message' => 'OK']);
    }

    public function daftar_post($id_event)
    {
        $this->handle_as_json = TRUE;
        $this->require_jwt_auth();

        $this->load->library('jwt_library');
        $this->load->library('Api_upload_library', NULL, 'api_upload');
        
        $jwt = $this->input->get_request_header('x-token');
        
        $upload_result = $this->api_upload->do_upload(TRUE);
        
        $payload = $this->jwt_library->decode($jwt);
        $this->model->daftar($id_event, $payload->id_mahasiswa, $upload_result['key']);

        $this->render_json(['success' => 1, 'message' => 'OK']);
    }

    public function test_get()
    {
        var_dump(json_decode('{"x" : "x"}'));
    }
}