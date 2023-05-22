<?php

class Peserta extends MY_Controller
{
    protected $load_model = 'peserta_model';

    public function index_get($id_event)
    {
        $this->require_auth(ROLE_SUPERADMIN);
        $this->payload['title'] = 'Data Peserta';
        $this->payload['nav'] = ['event'];
        $this->payload['bottom_scripts'] = 'event/scripts/peserta';
        $this->payload['id_event'] = $id_event;
        $this->load->model('event_model');
        $this->payload['data_event'] = $this->event_model->get_one($id_event);
        $this->payload['has_sertifikat'] = FALSE;

        if(!empty($kode))
        {
            $peserta = $this->model->get_by_kode_sertifikat($kode);
            if(!empty($peserta))
                $this->payload['has_sertifikat'] = $peserta->dapat_sertifikat == 1;
        }
        

        $this->render('event/peserta');
    }

    public function datatable_get($id_event)
    {
        $this->require_auth(ROLE_SUPERADMIN);
        $result = $this->model->datatable_peserta($id_event);

        $this->render_json($result);
    }

    public function index_post($id_event)
    {
        $this->require_auth(ROLE_SUPERADMIN);

        $type = $this->input->post('type');
        switch($type)
        {
            case 'set-nilai':
                $id = $this->input->post('id');
                $data = $this->input->post(['nilai']);
                $this->model->update_data($id, $data);
            break;
            case 'set-keaktifan':
                $id = $this->input->post('id');
                $data = $this->input->post(['keaktifan']);
                $this->model->update_data($id, $data);
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

    // public function viewSertifikat($id_sertifikat)
    // {
    //     header("content-type: application/pdf");

    //     readfile('./images/file_sertifikat/' . $id_sertifikat . '.pdf');

    //     // $filePath = '.images/file_sertifikat/test.pdf';

    // }
}