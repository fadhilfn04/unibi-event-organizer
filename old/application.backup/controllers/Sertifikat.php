<?php

class Sertifikat extends MY_Controller
{
    protected $load_model = 'peserta_model';

    public function index($kode = NULL)
    {
        $this->require_auth(ROLE_ADMIN);
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
}