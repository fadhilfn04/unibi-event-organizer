<?php

class Kehadiran extends MY_Controller
{
    protected $load_model = 'kehadiran_model';

    public function index_get($id_event, $tanggal = NULL)
    {
        $this->require_auth(ROLE_ADMIN);
        $this->payload['title'] = 'Data Kehadiran';
        $this->payload['nav'] = ['event'];
        $this->payload['bottom_scripts'] = 'event/scripts/kehadiran';
        $this->payload['id_event'] = $id_event;
        $this->payload['tanggal'] = $tanggal ?? date('Y-m-d');

        $this->load->model('event_model');
        $event = $this->event_model->get_one($id_event);
        
        $this->payload['data_event'] = $event;
        $this->load->helper('datetime');
        $this->payload['tanggal_list'] = generate_date_range($event->tanggal_mulai, $event->tanggal_selesai);

        $this->render('event/kehadiran');
    }

    public function datatable_get($id_event, $tanggal = NULL)
    {
        $this->require_auth(ROLE_ADMIN);
        $result = $this->model->datatable_peserta($id_event, $tanggal);

        $this->render_json($result);
    }

    public function index_post($id_event, $tanggal = NULL)
    {
        $this->require_auth(ROLE_ADMIN);

        $type = $this->input->post('type');
        switch($type)
        {
            case 'set-hadir':
                $id = $this->input->post('id');
                $data = $this->input->post(['id_mahasiswa', 'hadir']);
                $this->model->set_kehadiran($id_event, $data['id_mahasiswa'], $data['hadir'] == 1, NULL, $tanggal);
            break;
            case 'set-ijin':
                $id = $this->input->post('id');
                $data = $this->input->post(['id_mahasiswa', 'ijin']);
                $this->model->set_ijin($id_event, $data['id_mahasiswa'], $data['ijin'] == 1, $tanggal);
            break;
            default:
                throw new Exception('Type not found', ERR_INVALID_FORM_DATA);
            break;
        }

        $this->render_json(['code' => 1, 'message' => 'OK']);
    }

    public function absen_post()
    {
        $this->handle_as_json = TRUE;
        $this->require_jwt_auth();

        $this->load->library('jwt_library');
        $this->load->library('Api_upload_library', NULL, 'api_upload');
        $jwt = $this->input->get_request_header('x-token');
        $payload = $this->jwt_library->decode($jwt);
        $id_event = $this->input->post('id_event');
        $token = $this->input->post('absen_token');
        $tanggal = $this->input->post('tanggal');

        $this->model->set_kehadiran($id_event, $payload->id_mahasiswa, TRUE, $token, $tanggal);
        $this->render_json(['code' => 1, 'message' => 'OK']);
    }

    public function qrcode_get($id_event)
    {
        $this->require_auth(ROLE_ADMIN);
        $tanggal = $this->input->get('tanggal');
        $token = $this->model->get_absen_token($id_event, $tanggal);
        $qrCode = new \Endroid\QrCode\QrCode('unibi-event#'.$token->kode);
        $qrCode->setSize(480);
        $qrCode->setMargin(1);
        $qrCode->setWriterByName('png');
        $qrCode->setEncoding('UTF-8');
        $qrCode->setErrorCorrectionLevel(\Endroid\QrCode\ErrorCorrectionLevel::HIGH());
        
        $this->output
            ->set_content_type($qrCode->getContentType())
            ->set_output($qrCode->writeString());
    }

    public function qrcode_mahasiswa_get($id_event, $tanggal = NULL) {
        $this->require_jwt_auth(TRUE);

        $this->load->library('jwt_library');
        $this->load->library('Api_upload_library', NULL, 'api_upload');
        $jwt = $this->input->get_request_header('x-token') ?? $this->input->get('token');
        $payload = $this->jwt_library->decode($jwt);

        $token = $this->model->get_absen_token($id_event, $tanggal);
        $data = site_url('a/'.$payload->id_mahasiswa.'/'.$token->kode);
        $qrCode = new \Endroid\QrCode\QrCode($data);

        $qrCode->setSize(480);
        $qrCode->setMargin(1);
        $qrCode->setWriterByName('png');
        $qrCode->setEncoding('UTF-8');
        $qrCode->setErrorCorrectionLevel(\Endroid\QrCode\ErrorCorrectionLevel::HIGH());
        
        $this->output
            ->set_content_type($qrCode->getContentType())
            ->set_output($qrCode->writeString());
    }

    public function absen_by_admin_get($id_mahasiswa, $token) {
        $this->require_auth(ROLE_ADMIN);

        $success = $this->model->set_kehadiran_by_admin($id_mahasiswa, $token);

        echo $success ? 'Absen berhasil' : 'Token / QRCode Salah';
    }
}