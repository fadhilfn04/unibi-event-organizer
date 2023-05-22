<?php

class Peserta_model extends MY_Model
{
    protected $table = 'peserta';
    protected $table_mahasiswa = 'mahasiswa';
    protected $table_jurusan = 'jurusan';

    protected $view_peserta = 'v_peserta';

    private $pagination_config = [
        'columns' => [
            'id'                  => ['orderable'],
            'npm'                 => ['orderable', 'searchable'],
            'nama'                => ['orderable', 'searchable'],
            'jurusan'             => ['orderable', 'searchable'],
            'no_telepon'          => ['orderable', 'searchable'],
            'tanggal_bukti'       => ['orderable'],
            'testimoni_penilaian' => ['orderable'],
            'testimoni_pendapat'  => ['orderable'],
        ],
    ];
    
    //untuk memproses datatable peserta event
    public function datatable_pesertaEvent($id_peserta, $filter = [])
    {
        $this->load->library('pagination_library', $this->pagination_config, 'dt');
        $this->dt
            ->from($this->view_event . ' AS t')
            ->where('id_peserta', $id_peserta);
        return $this->dt->generate_datatable();
    }

    // Untuk memproses datatable kehadiran
    public function datatable_peserta($id_event, $filter = [])
    {
        $this->load->library('pagination_library', $this->pagination_config, 'dt');
        $this->dt
            ->from($this->view_peserta . ' AS t')
            ->where('id_event', $id_event);
        return $this->dt->generate_datatable();
    }
    
    public function datatable_sertifikat($kode, $filter = [])
    {
        $this->load->library('pagination_library', $this->pagination_config, 'dt');
        $this->dt
            ->from($this->view_peserta . ' AS t')
            ->where('kode_sertifikat', $kode);
        return $this->dt->generate_datatable();
    }

    public function datatable_pendaftaran($id_event)
    {
        $this->load->library('pagination_library', $this->pagination_config, 'dt');
        $this->dt->select('t.*, m.npm, m.nama, m.no_telepon, m.id_jurusan, j.nama AS jurusan')
            ->from($this->table . ' AS t')
            ->join($this->table_mahasiswa . ' AS m', 'm.id=t.id_mahasiswa')
            ->join($this->table_jurusan . ' AS j', 'j.id=m.id_jurusan')
            ->where('id_event', $id_event);
        return $this->dt->generate_datatable();
    }

    public function verifikasi($id)
    {
        $this->db->where('id', $id)->update($this->table, ['terverifikasi' => 1]);
    }

    public function get_peserta($id_event, $id_mahasiswa)
    {
        return $this->db->from($this->view_peserta)
            ->where('id_event', $id_event)
            ->where('id_mahasiswa', $id_mahasiswa)
            ->get()->row();
    }

    public function get_by_kode_sertifikat($kode)
    {
        return $this->db->from($this->view_peserta)->where('kode_sertifikat', $kode)->get()->row();
    }

    public function daftar($id_event, $id_mahasiswa, $file_key)
    {
        $peserta = $this->db->from($this->table)
            ->where('id_event', $id_event)
            ->where('id_mahasiswa', $id_mahasiswa)
            ->get()->row();

        $file_path = $this->handle_upload_foto($file_key, 'bukti_bayar');
        
        if(!is_null($peserta))
        {
            if($peserta->terverifikasi) {
                throw new \Exception('Sudah melakukan pendaftaran');
            }
        }

        $payload = [
            'id_event'      => $id_event,
            'id_mahasiswa'  => $id_mahasiswa,
            'file_bukti'    => $file_path,
            'tanggal_bukti' => date('Y-m-d H:i:s'),
            'terverifikasi' => 0,
            'keaktifan'     => 1,
        ];
        
        if(is_null($peserta))
        {
            $this->db->insert($this->table, $payload);
        }
        else
        {
            $this->db->where('id', $peserta->id)->update($this->table, $payload);
        }
    }

    private function handle_upload_foto($foto, $field)
    {
        $this->load->library('Api_upload_library', NULL, 'api_upload');
        if($foto && ($path = $this->api_upload->get_temp_filepath($foto, TRUE))) {
            $move_target = FCPATH . 'storage/images/' . $field;
            if(!file_exists($move_target)) {
                mkdir($move_target, 0777, true);
            }

            do $key = bin2hex(random_bytes(8));
            while(file_exists($move_target . '/' . $key));

            rename($path, $move_target . '/' . $key);
            return 'images/' . $field . '/' . $key;
        }
    }
}