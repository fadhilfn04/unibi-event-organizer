<?php

class Sertifikat extends MY_Controller
{
    protected $load_model = 'peserta_model';
    // protected $load_model = 'sertifikat_model';

    public function __construct()
	{
		parent::__construct();
		$this->load->library('auth_library', NULL, 'auth');
	}

    public function index($kode = NULL)
    {
        $this->require_auth(ROLE_SUPERADMIN);
        $this->payload['title'] = 'Pencarian Sertifikat';
        $this->payload['nav'] = ['sertifikat'];
        $this->payload['bottom_scripts'] = 'event/scripts/sertifikat';
        $this->payload['kode'] = $kode;
        $this->payload['has_sertifikat'] = FALSE;

        if(!empty($kode))
        {
            $peserta = $this->model->get_by_kode_sertifikat($kode);
            if(!empty($peserta))
                $this->payload['has_sertifikat'] = $peserta->dapat_sertifikat == 1;
        }
        
        $this->render('event/sertifikat');
    }

    // public function datatable_get()
    // {
    //     $this->require_auth(ROLE_SUPERADMIN);
    //     $result = $this->model->datatable_peserta();

    //     $this->render_json($result);
    // }

    public function datatable_get($kode)
    {
        $this->require_auth(ROLE_SUPERADMIN);
        $result = $this->model->datatable_sertifikat($kode);
        
        $this->render_json($result);
    }
}