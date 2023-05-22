<?php

class MY_Model extends CI_Model
{
    protected $table;
    protected $primary_key = 'id';

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    public function get_all()
    {
        return $this->db->from($this->table)->get()->result();
    }

    public function get_one($id)
    {
        return $this->db->from($this->table)->where($this->primary_key, $id)->limit(1)->get()->row();
    }

    public function insert_data(array $data)
    {
        return $this->do_insert_data($this->table, $data);
    }

    public function update_data($id, array $data)
    {
        return $this->do_update_data($this->table, $id, $data);
    }

    public function delete_data($id)
    {
        return $this->do_delete_data($this->table, $id);
    }

    protected function do_insert_data($table, array $data)
    {
        $result = $this->db->insert($table, $data);

        if(!$result)
        {
            throw new Exception('Gagal menambahkan data!', ERR_INSERT_DATA_FAIL);
        }

        return $this->db->insert_id();
    }

    protected function do_update_data($table, $id, array $data)
    {
        $result = $this->db->where($this->primary_key, $id)->limit(1)->update($table, $data);

        if(!$result)
        {
            throw new Exception('Gagal mengubah data!', ERR_UPDATE_DATA_FAIL);
        }

        return TRUE;
    }

    protected function do_delete_data($table, $id)
    {
        $result = $this->db->where($this->primary_key, $id)->limit(1)->delete($table);

        if(!$result)
        {
            throw new Exception('Gagal menghapus data!', ERR_DELETE_DATA_FAIL);
        }

        return TRUE;
    }
}