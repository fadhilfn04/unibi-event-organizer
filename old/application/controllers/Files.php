<?php defined('BASEPATH') or exit('No direct script access allowed');

class Files extends MY_Controller
{
    private $public_directories = ['images'];

	public function __construct()
	{
		parent::__construct();
		$this->load->library('Api_upload_library', NULL, 'api_upload');
	}

	public function upload_post()
	{
        $result = $this->api_upload->do_upload();

        $this->render_json($result);
	}

	public function open_get(...$path)
	{
		if(!empty($path) && in_array($path[0], $this->public_directories))
		{
            $path = FCPATH.'storage/'.join('/', $path);
			if(file_exists($path))
			{
				return $this->output
					->set_content_type(mime_content_type($path))
					->set_output(file_get_contents($path));
			}
		}

		show_404();
	}

	public function test()
	{
		$content = file_get_contents(site_url('files/certificate/21/34'));
		var_dump($content);
	}

	public function sertifikat_get($kode)
	{
		$this->load->model('peserta_model');
		$peserta = $this->peserta_model->get_by_kode_sertifikat($kode);
		if(empty($peserta))
		{
			show_404();
			return;
		}

		$this->certificate_get($peserta->id_event, $peserta->id_mahasiswa);
	}

	public function certificate_get($id_event, $id_mahasiswa)
	{
		$this->load->library('Certificate_pdf', NULL, 'certificate');
		
		$this->load->model('event_model');
		$this->load->model('mahasiswa_model');
		$this->load->model('peserta_model');
		$this->load->helper('datetime');

		$peserta = $this->peserta_model->get_peserta($id_event, $id_mahasiswa);

		$event = $this->event_model->get_one($id_event);
		$mahasiswa = $this->mahasiswa_model->get_one($id_mahasiswa);
		$penanggung_jawab = $this->event_model->get_penanggung_jawab($id_event);

		if($peserta->dapat_sertifikat != 1) {
			show_404();
			return;
		}

		$this->certificate->apply_data($mahasiswa, $event, $penanggung_jawab, $peserta->kode_sertifikat);
		$this->certificate->Output('I');
	}
}
