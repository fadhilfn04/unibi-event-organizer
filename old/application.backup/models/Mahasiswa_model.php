<?php

class Mahasiswa_model extends MY_Model
{
    protected $table = 'mahasiswa';
    protected $table_fakultas = 'fakultas';
    protected $table_jurusan = 'jurusan';
    protected $table_user = 'users';
    protected $table_token = 'token_pendaftaran';

    private $pagination_config = [
        'columns' => [
            'id'           => ['orderable'],
            'npm'          => ['orderable', 'searchable'],
            'nama'         => ['orderable', 'searchable'],
            'email'        => ['orderable', 'searchable'],
            'id_jurusan'   => ['orderable'],
            'id_fakultas'  => ['orderable'],
            'jurusan'      => ['orderable', 'searchable'],
            'fakultas'     => ['orderable', 'searchable'],
            'no_telepon'   => ['orderable', 'searchable'],
        ],
    ];

    public function datatable($filter = [])
    {
        $this->load->library('pagination_library', $this->pagination_config, 'dt');
        $this->dt
            ->select('m.*, f.nama AS fakultas, j.nama AS jurusan')
            ->from($this->table . ' AS m')
            ->join($this->table_fakultas . ' AS f', 'm.id_fakultas=f.id')
            ->join($this->table_jurusan . ' AS j', 'm.id_jurusan=j.id');

        return $this->dt->generate_datatable();
    }

    public function select2($filter = [])
    {
        $this->load->library('pagination_library', $this->pagination_config, 'dt');
        $this->dt
            ->select('m.*, f.nama AS fakultas, j.nama AS jurusan')
            ->from($this->table . ' AS m')
            ->join($this->table_fakultas . ' AS f', 'm.id_fakultas=f.id')
            ->join($this->table_jurusan . ' AS j', 'm.id_jurusan=j.id');

        return $this->dt->generate_select2([
            'text_column' => 'nama'
        ]);
    }

    public function get_mahasiswa_count()
    {
        return $this->db->select('COUNT(0) as count')->from($this->table)->get()->row('count');
    }

    public function get_count()
    {
        return $this->db->from($this->table)->select('COUNT(0) AS count')->get()->row('count');
    }

    public function insert_data($data)
    {
        $this->db->trans_start();
        $insert_id = $this->do_insert_data($this->table, $data);

        $this->load->library('Auth_library', NULL, 'auth');

        $user_data = [
            'username'      => $data['npm'],
            'name'          => $data['nama'],
            'role'          => ROLE_MAHASISWA,
            'password_hash' => $this->auth->password_hash($data['npm'] . strtolower(substr($data['nama'], 0, 2))),
            'id_mahasiswa'  => $insert_id,
        ];

        $this->do_insert_data($this->table_user, $user_data);
        $this->db->trans_complete();

        return $insert_id;
    }

    public function update_data($id, $data)
    {
        $this->db->trans_start();

        $this->do_update_data($this->table, $id, $data);

        $success = $this->db->where('id_mahasiswa', $id)->update($this->table_user, [
            'username' => $data['npm']
        ]);

        $this->db->trans_complete();

        if(!$success)
        {
            throw new Exception('Gagal mengubah data!', ERR_UPDATE_DATA_FAIL);
        }

        return TRUE;
    }

    public function delete_data($id)
    {
        $this->db->trans_start();

        $this->do_delete_data($this->table, $id);

        $success = $this->db->where('id_mahasiswa', $id)->delete($this->table_user);

        $this->db->trans_complete();

        if(!$success)
        {
            throw new Exception('Gagal menghapus data!', ERR_DELETE_DATA_FAIL);
        }

        return TRUE;
    }
}