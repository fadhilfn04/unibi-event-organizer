<?php

class Event_model extends MY_Model
{
    protected $table = 'event';
    protected $view = 'v_event';
    protected $table_kategori = 'kategori';
    protected $table_peserta = 'peserta';
    protected $table_mahasiswa = 'mahasiswa';
    protected $table_penanggung_jawab = 'penanggung_jawab';
    protected $table_agenda = 'agenda';

    private $pagination_config = [
        'columns' => [
            'id'              => ['orderable'],
            'nama'            => ['orderable', 'searchable'],
            'id_kategori'     => ['orderable'],
            'tema'            => ['orderable', 'searchable'],
            'tanggal_mulai'   => ['orderable'],
            'tanggal_selesai' => ['orderable'],
            'harga'           => ['orderable'],
            'file_cover'      => ['orderable'],
        ],
    ];

    public function insert_data($data)
    {
        $this->handle_upload_foto($data, 'file_cover');
        $this->handle_upload_foto($data, 'file_sertifikat');
        $data_penanggung_jawab = NULL;
        if(array_key_exists('penanggung_jawab', $data))
        {
            $data_penanggung_jawab = $data['penanggung_jawab'];
            unset($data['penanggung_jawab']);
        }
        $this->db->trans_start();
        $insert_id = parent::insert_data($data);
        if(is_array($data_penanggung_jawab))
        {
            foreach($data_penanggung_jawab as $item)
            {
                $item['id_event'] = $insert_id;
                $this->do_insert_data($this->table_penanggung_jawab, $item);
            }
        }
        
        return $this->db->trans_complete();
    }

    public function update_data($id, $data)
    {
        $this->handle_upload_foto($data, 'file_cover');
        $this->handle_upload_foto($data, 'file_sertifikat');
        $data_penanggung_jawab = NULL;
        if(array_key_exists('penanggung_jawab', $data))
        {
            $data_penanggung_jawab = $data['penanggung_jawab'];
            unset($data['penanggung_jawab']);
        }
        $this->db->trans_start();
        parent::update_data($id, $data);
        if(is_array($data_penanggung_jawab))
        {
            $this->db->where('id_event', $id)->delete($this->table_penanggung_jawab);
            foreach($data_penanggung_jawab as $item)
            {
                $item['id_event'] = $id;
                $this->do_insert_data($this->table_penanggung_jawab, $item);
            }
        }
        
        return $this->db->trans_complete();
    }

    public function delete_data($id)
    {
        $this->db->trans_start();
        parent::delete_data($id);
        $this->db->where('id_event', $id)->delete($this->table_agenda);
        $this->db->where('id_event', $id)->delete($this->table_penanggung_jawab);
        return $this->db->trans_complete();
    }

    public function get_penanggung_jawab($id)
    {
        return $this->db->from($this->table_penanggung_jawab)->where('id_event', $id)->get()->result();
    }

    public function get_event_detail($id, $id_mahasiswa)
    {
        $query_join = $this->db->compile_binds('e.id=p.id_event AND p.id_mahasiswa=?', [$id_mahasiswa]);
        return $this->db
            ->select('e.*, k.nama AS kategori, p.id_mahasiswa, p.terverifikasi, p.keaktifan AS peserta_aktif, p.nilai, p.testimoni_pendapat, p.testimoni_penilaian')
            ->from($this->view . ' AS e')
            ->join($this->table_kategori . ' AS k', 'e.id_kategori=k.id')
            ->join($this->table_peserta . ' AS p', $query_join, 'LEFT')
            ->where('e.id', $id)
            ->get()->row();
    }

    // Untuk memproses datatable kehadiran
    public function datatable($filter = [])
    {
        $this->load->library('pagination_library', $this->pagination_config, 'dt');
        $this->dt->from($this->view . ' AS t');
        return $this->dt->generate_datatable();
    }

    public function get_one($id)
    {
        return $this->db->from($this->view)->where('id', $id)->get()->row();
    }

    public function get_all()
    {
        return $this->db->from($this->view)->get()->result();
    }

    public function get_count()
    {
        return $this->db->from($this->table)->select('COUNT(0) AS count')->get()->row('count');
    }

    public function get_registered_event($id_mahasiswa)
    {
        return $this->db
            ->select('e.*, k.nama AS kategori')
            ->from($this->table_peserta . ' AS p')
            ->join($this->table . ' AS e', 'p.id_event=e.id')
            ->join($this->table_kategori . ' AS k', 'e.id_kategori=k.id')
            ->where('p.id_mahasiswa', $id_mahasiswa)
            ->get()->result();
    }

    private function handle_upload_foto(&$data, $field)
    {
        $foto = $data[$field];
        $this->load->library('Api_upload_library', NULL, 'api_upload');
        if($foto && ($path = $this->api_upload->get_temp_filepath($foto))) {
            $move_target = FCPATH . 'storage/images/' . $field;
            if(!file_exists($move_target)) {
                mkdir($move_target, 0777, true);
            }

            do $key = bin2hex(random_bytes(8));
            while(file_exists($move_target . '/' . $key));

            rename($path, $move_target . '/' . $key);
            $data[$field] = 'images/' . $field . '/' . $key;
        }
        else {
            unset($data[$field]);
        }
    }
}