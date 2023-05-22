<?php

class Narasumber_model extends MY_Model
{
    protected $table = 'narasumber';

    private $pagination_config = [
        'columns' => [
            'id'           => ['orderable'],
            'nama'         => ['orderable', 'searchable'],
            'pekerjaan'    => ['orderable', 'searchable'],
            'keahlian'   => ['orderable', 'searchable'],
            'pendidikan'  => ['orderable', 'searchable'],
            // 'jurusan'      => ['orderable', 'searchable'],
            // 'fakultas'     => ['orderable', 'searchable'],
            'email'        => ['orderable', 'searchable'],
            'no_telepon'   => ['orderable', 'searchable'],
        ],
    ];

    public function datatable($filter = [])
    {
        $this->load->library('pagination_library', $this->pagination_config, 'dt');
        $this->dt->from($this->table);
        return $this->dt->generate_datatable();
    }

}