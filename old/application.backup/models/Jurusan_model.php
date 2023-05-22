<?php

class Jurusan_model extends MY_Model
{
    protected $table = 'jurusan';
    protected $table_fakultas = 'fakultas';

    private $pagination_config = [
        'columns' => [
            'id'       => ['orderable'],
            'nama'     => ['orderable', 'searchable'],
            'fakultas' => ['orderable', 'searchable'],
        ],
    ];

    // Untuk memproses datatable kehadiran
    public function datatable($filter = [])
    {
        $this->load->library('pagination_library', $this->pagination_config, 'dt');
        $this->dt
            ->select('j.*, f.nama AS fakultas')
            ->from($this->table . ' AS j')
            ->join($this->table_fakultas . ' AS f', 'j.id_fakultas=f.id');

        return $this->dt->generate_datatable();
    }

    public function get_with_detail()
    {
        return $this->db
            ->select('j.*, f.nama AS fakultas, f.kode AS kode_fakultas, f.id AS id_fakultas')
            ->from($this->table . ' AS j')
            ->join($this->table_fakultas . ' AS f', 'f.id=j.id_fakultas')
            ->get()
            ->result();
    }
}