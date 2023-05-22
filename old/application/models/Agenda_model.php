<?php

class Agenda_model extends MY_Model
{
    protected $table = 'agenda';

    // Untuk memproses datatable kehadiran
    public function datatable($id_event, $filter = [])
    {
        $this->load->library('pagination_library', NULL, 'dt');
        $this->dt->from($this->table)->where('id_event', $id_event);
        return $this->dt->generate_datatable();
    }

    public function get_by_id_event($id_event)
    {
        return $this->db
            ->where('id_event', $id_event)
            ->order_by('tanggal')
            ->order_by('jam_mulai')
            ->get($this->table)->result();
    }
}