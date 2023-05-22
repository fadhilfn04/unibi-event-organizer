<?php

class Kategori_model extends MY_Model
{
    protected $table = 'kategori';

    private $pagination_config = [
        'columns' => [
            'id'   => ['orderable'],
            'nama' => ['orderable', 'searchable'],
        ],
    ];

    // Untuk memproses datatable kehadiran
    public function datatable($filter = [])
    {
        $this->load->library('pagination_library', NULL, 'dt');
        $this->dt->from($this->table);
        return $this->dt->generate_datatable();
    }
}