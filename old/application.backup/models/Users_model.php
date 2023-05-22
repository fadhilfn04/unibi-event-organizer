<?php

class Users_model extends MY_Model
{
    protected $table = 'users';

    private $pagination_config = [
        'columns' => [
            'id'   => ['orderable'],
            'nama' => ['orderable', 'searchable'],
        ],
    ];

    // Untuk memproses datatable Users
    public function datatable($filter = [])
    {
        $this->load->library('pagination_library', NULL, 'dt');
        $this->dt->from($this->table)->where('role <', '4');
        return $this->dt->generate_datatable();
    }
}