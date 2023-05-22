<?php

class Event extends MY_Controller
{
    protected $load_model = 'event_model';

    public function index_get()
    {
        $this->require_auth([ROLE_SUPERADMIN, ROLE_ADMIN, ROLE_PETUGAS]);
        $this->payload['title'] = 'Data Event';
        $this->payload['nav'] = ['event'];
        $this->payload['bottom_scripts'] = 'event/scripts/index';

        $this->load->model('kategori_model');
        $this->payload['kategori_list'] = $this->kategori_model->get_all();

        $this->render('event/index');
    }

    public function index_post()
    {
        $this->require_auth([ROLE_SUPERADMIN, ROLE_ADMIN, ROLE_PETUGAS]);

        $type = $this->input->post('type');
        $this->load->helper('datetime');
        
        switch($type)
        {
            case 'insert':
                $data = $this->input->post([
                    'file_cover',
                    'file_sertifikat',
                    'nama',
                    'tema',
                    'id_kategori',
                    'tanggal_mulai',
                    'tanggal_selesai',
                    'harga',
                    'nilai_minimum',
                    'kehadiran_minimum',
                    'penanggung_jawab',
                    'bersertifikat',
                    'keaktifan',
                ]);

                $data['tanggal_mulai']   = normalize_date_format('d/m/Y', $data['tanggal_mulai']);
                $data['tanggal_selesai'] = normalize_date_format('d/m/Y', $data['tanggal_selesai']);
                $data['bersertifikat'] = $data['bersertifikat'] ?? 0;
                $data['keaktifan'] = $data['keaktifan'] ?? 0;

                $this->model->insert_data($data);
            break;
            case 'update':
                $id = $this->input->post('id');
                $data = $this->input->post([
                    'file_cover',
                    'file_sertifikat',
                    'nama',
                    'tema',
                    'id_kategori',
                    'tanggal_mulai',
                    'tanggal_selesai',
                    'harga',
                    'nilai_minimum',
                    'kehadiran_minimum',
                    'penanggung_jawab',
                    'bersertifikat',
                    'keaktifan',
                ]);

                $data['tanggal_mulai']   = normalize_date_format('d/m/Y', $data['tanggal_mulai']);
                $data['tanggal_selesai'] = normalize_date_format('d/m/Y', $data['tanggal_selesai']);
                $data['bersertifikat'] = $data['bersertifikat'] ?? 0;
                $data['keaktifan'] = $data['keaktifan'] ?? 0;

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

    public function get_event_list_get()
    {
        $data = $this->model->get_all();
        $this->render_json($data);
    }

    public function get_event_detail_get($id)
    {
        $this->handle_as_json = TRUE;
        $this->require_jwt_auth();

        $this->load->library('jwt_library');
        $this->load->library('Api_upload_library', NULL, 'api_upload');
        $jwt = $this->input->get_request_header('x-token');
        $payload = $this->jwt_library->decode($jwt);
        $data = $this->model->get_event_detail($id, $payload->id_mahasiswa);
        $this->load->model('agenda_model');
        $data->agenda = $this->agenda_model->get_by_id_event($id);
        $this->load->model('kehadiran_model');
        $data->kehadiran = $this->kehadiran_model->get_kehadiran($id, $payload->id_mahasiswa);
        $this->render_json($data);
    }

    public function get_registered_event_list_get()
    {
        $this->handle_as_json = TRUE;
        $this->require_jwt_auth();

        $this->load->library('jwt_library');
        $this->load->library('Api_upload_library', NULL, 'api_upload');
        $jwt = $this->input->get_request_header('x-token');
        $payload = $this->jwt_library->decode($jwt);
        $data = $this->model->get_registered_event($payload->id_mahasiswa);
        $this->render_json($data);
    }

    public function get_penanggung_jawab_get($id)
    {
        $this->require_auth([ROLE_SUPERADMIN, ROLE_ADMIN, ROLE_PETUGAS]);

        $data = $this->model->get_penanggung_jawab($id);

        $this->render_json(['code' => 1, 'message' => 'OK', 'data' => $data]);
    }

    public function datatable_get()
    {
        $this->require_auth([ROLE_SUPERADMIN, ROLE_ADMIN, ROLE_PETUGAS]);
        $result = $this->model->datatable();

        $this->render_json($result);
    }
}