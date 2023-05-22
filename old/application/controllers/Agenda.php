<?php

class Agenda extends MY_Controller
{
    protected $load_model = 'agenda_model';

    public function index_get($id_event)
    {
        $this->require_auth(ROLE_SUPERADMIN);
        $this->load->helper('datetime_helper');
        $this->payload['title'] = 'Data Agenda';
        $this->payload['nav'] = ['event'];
        $this->payload['bottom_scripts'] = 'event/scripts/agenda';
        $this->payload['id_event'] = $id_event;

        $this->load->model('event_model');
        $event = $this->event_model->get_one($id_event);
        $this->payload['dates'] = generate_date_range($event->tanggal_mulai, $event->tanggal_selesai);

        $this->render('event/agenda');
    }

    public function datatable_get($id_event)
    {
        $this->require_auth(ROLE_SUPERADMIN);
        $result = $this->model->datatable($id_event);

        $this->render_json($result);
    }

    public function index_post($id_event)
    {
        $this->require_auth(ROLE_SUPERADMIN);
        $this->load->helper('datetime_helper');

        $type = $this->input->post('type');
        switch($type)
        {
            case 'insert':
                $data = $this->input->post(['tanggal', 'jam_mulai', 'jam_selesai', 'kegiatan', 'narasumber']);
                $data['id_event'] = $id_event;
                $data['jam_mulai'] = normalize_hour_format('H.i', $data['jam_mulai']);
                $data['jam_selesai'] = normalize_hour_format('H.i', $data['jam_selesai']);
                $this->model->insert_data($data);
            break;
            case 'update':
                $id = $this->input->post('id');
                $data = $this->input->post(['tanggal', 'jam_mulai', 'jam_selesai', 'kegiatan', 'narasumber']);
                $data['jam_mulai'] = normalize_hour_format('H.i', $data['jam_mulai']);
                $data['jam_selesai'] = normalize_hour_format('H.i', $data['jam_selesai']);
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