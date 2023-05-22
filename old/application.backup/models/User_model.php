<?php

class User_model extends MY_Model
{
    protected $table = 'users';

    public function register($params)
    {
        extract($params);
        $user = $this->db->where('username', $npm)->get($this->table)->row();
        if(!empty($user))
        {
            throw new Exception('Pengguna sudah terdaftar');
        }

        $id_fakultas = $this->db->where('id', $id_jurusan)->get('jurusan')->row('id_fakultas');

        $this->db->insert('mahasiswa', [
            'npm'         => $npm,
            'nama'        => $nama,
            'email'       => $email,
            'id_fakultas' => $id_fakultas,
            'id_jurusan'  => $id_jurusan,
            'no_telepon'  => $no_telepon,
        ]);

        $id_mahasiswa = $this->db->insert_id();

        $this->load->library('auth_library');
        
        $data = [
            'username'      => $npm,
            'password_hash' => $this->auth_library->password_hash($password),
            'role'          => ROLE_MAHASISWA,
            'name'          => $nama,
            'id_mahasiswa'  => $id_mahasiswa,
        ];
        $this->db->insert($this->table, $data);

        $data['id'] = $this->db->insert_id();

        return (object) $data;
    }
}