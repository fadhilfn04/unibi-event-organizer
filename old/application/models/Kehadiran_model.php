<?php

class Kehadiran_model extends MY_Model
{
    protected $table = 'kehadiran';
    protected $table_peserta = 'peserta';
    protected $table_event = 'event';
    protected $table_mahasiswa = 'mahasiswa';
    protected $table_token = 'absen_token';

    public function datatable_peserta($id_event, $tanggal = NULL)
    {
        if($tanggal == NULL) $tanggal = date('Y-m-d');
        $this->load->library('pagination_library', NULL, 'dt');
        $tanggal_query = $this->db->compile_binds('k.tanggal=?', [$tanggal]);
        
        $this->dt
            ->select('p.*, k.tanggal, k.ijin, k.jam, k.id_token, k.cara_absen, m.nama AS nama, m.npm')
            ->from($this->table_peserta . ' AS p')
            ->join($this->table . ' AS k', 'p.id_mahasiswa=k.id_mahasiswa AND p.id_event=k.id_event AND ' . $tanggal_query, 'LEFT')
            ->join($this->table_mahasiswa . ' AS m', 'p.id_mahasiswa=m.id')
            ->where('p.id_event', $id_event)
            ->where('p.terverifikasi', 1);
        return $this->dt->generate_datatable();
    }

    public function set_ijin($id_event, $id_mahasiswa, $is_ijin, $tanggal = NULL)
    {
        if($tanggal == NULL) $tanggal = date('Y-m-d');
        $row = $this->db
            ->from($this->table)
            ->where('id_event', $id_event)
            ->where('id_mahasiswa', $id_mahasiswa)
            ->where('tanggal', $tanggal)
            ->get()->row();
        if(is_null($row))
        {
            $this->db->insert($this->table, [
                'id_event' => $id_event,
                'id_mahasiswa' => $id_mahasiswa,
                'tanggal' => $tanggal,
                'ijin' => $is_ijin,
            ]);
        }
        else
        {
            $this->db->where('id', $row->id)->update($this->table, [
                'ijin' => $is_ijin
            ]);
        }
    }

    public function set_kehadiran_by_admin($id_mahasiswa, $token)
    {
        $absen_token = $this->db->where('kode', $token)->get($this->table_token)->row();
        if(is_null($absen_token)) return FALSE;
        
        $payload = [
            'id_event'     => $absen_token->id_event,
            'id_mahasiswa' => $id_mahasiswa,
            'tanggal'      => $absen_token->tanggal,
            'id_token'     => $absen_token->id,
            'cara_absen'   => 1,
            'jam'          => date('H:i:s'),
        ];

        $row = $this->db
            ->from($this->table)
            ->where('id_event', $absen_token->id_event)
            ->where('id_mahasiswa', $id_mahasiswa)
            ->where('tanggal', $absen_token->tanggal)
            ->get()->row();
        
        if(is_null($row)) $this->db->insert($this->table, $payload);
        else $this->db->where('id', $row->id)->update($this->table, $payload);

        return TRUE;
    }

    public function set_kehadiran($id_event, $id_mahasiswa, $is_hadir, $token = NULL, $tanggal = NULL)
    {
        if($tanggal == NULL) $tanggal = date('Y-m-d');
        $row = $this->db
            ->from($this->table)
            ->where('id_event', $id_event)
            ->where('id_mahasiswa', $id_mahasiswa)
            ->where('tanggal', $tanggal)
            ->get()->row();
        
        if($is_hadir && is_null($row))
        {
            $id_token = NULL;
            if(!is_null($token))
            {
                $id_token = $this->db
                    ->where('kode', $token)
                    ->where('tanggal', $tanggal)
                    ->where('id_event', $id_event)
                    ->get($this->table_token)
                    ->row('id');

                if(is_null($id_token))
                {
                    throw new Exception('Token absen salah');
                }
            }
            $payload = [
                'id_event'     => $id_event,
                'id_mahasiswa' => $id_mahasiswa,
                'tanggal'      => $tanggal,
                'id_token'     => $id_token,
                'cara_absen'   => $token == NULL ? 2 : 1,
                'jam'          => date('H:i:s'),
            ];

            $this->db->insert($this->table, $payload);
        }
        elseif(!$is_hadir && !is_null($row))
        {
            $this->db->where('id', $row->id)->delete($this->table);
        }
    }

    public function get_absen_token($id_event, $tanggal = NULL)
    {
        if($tanggal == NULL) $tanggal = date('Y-m-d');
        $row = $this->db
            ->where('tanggal', $tanggal)
            ->where('id_event', $id_event)
            ->get($this->table_token)
            ->row();

        if(is_null($row))
        {
            do {
                $token = bin2hex(random_bytes(16));
                $is_exist = $this->db->select('1 AS exists')
                    ->where('kode', $token)
                    ->get($this->table_token)
                    ->row('exists');
            }
            while($is_exist);
            $payload = [
                'tanggal' => $tanggal,
                'id_event' => $id_event,
                'kode' => $token,
            ];
            $this->db->insert($this->table_token, $payload);
            $payload['id'] = $this->db->insert_id();
            return (object) $payload;
        }

        return $row;
    }

    public function get_kehadiran($id_event, $id_mahasiswa)
    {
        $event = $this->db->where('id', $id_event)->get($this->table_event)->row();
        $this->load->helper('datetime');
        $dates = generate_date_range($event->tanggal_mulai, $event->tanggal_selesai);
        $kehadiran = $this->db
            ->where('id_event', $id_event)
            ->where('id_mahasiswa', $id_mahasiswa)
            ->where('tanggal <=', $event->tanggal_selesai)
            ->where('tanggal >=', $event->tanggal_mulai)
            ->get($this->table)->result();

        $result_map = [];
        foreach($dates as $date)
        {
            $result_map[$date] = (object) [
                'id'      => NULL,
                'tanggal' => $date,
                'jam'     => NULL,
            ];
        }

        foreach($kehadiran as $item)
        {
            $result_map[$item->tanggal] = $item;
        }

        return array_values($result_map);
    }
}