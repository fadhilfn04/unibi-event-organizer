<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard extends MY_Controller {
    
    // Halaman beranda
    public function index_get()
    {
        $this->require_auth([ROLE_SUPERADMIN, ROLE_ADMIN, ROLE_PETUGAS, ROLE_MAHASISWA]);
        $this->payload['nav'] = ['beranda'];
        $this->payload['title'] = 'Beranda';

        $this->load->model('mahasiswa_model');
        $this->load->model('event_model');

        $this->payload['event_count'] = $this->event_model->get_count();
        $this->payload['mahasiswa_count'] = $this->mahasiswa_model->get_count();

        if($this->session->userdata('role') == ROLE_SUPERADMIN || ROLE_ADMIN || ROLE_PETUGAS)
        {
            $this->render('dashboard/admin');
        }
    }
}
