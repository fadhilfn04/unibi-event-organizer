<?php

class Auth_library
{
    private $CI;
    private $db;

    private $table = 'users';
    private $username_column = 'username';
    private $password_column = 'password_hash';

    public function __construct()
    {
        $this->CI =& get_instance();
        $this->db = $this->CI->load->database('', TRUE);
        $this->CI->load->library('session');
    }

    public function login($username, $password)
    {
        $user = $this->get_user_by_username($username);

        if(empty($user))
        {
            throw new Exception('User tidak ditemukan', ERR_USER_NOT_FOUND);
        }

        if(!$this->verify_password($password, $user->{$this->password_column}))
        {
            throw new Exception('Password salah', ERR_INCORRECT_PASSWORD);
        }

        unset($user->{$this->password_column});

        $this->CI->session->set_userdata((array) $user);

        return $user;
    }

    public function logout()
    {
        $this->CI->session->sess_destroy();
    }

    public function get_user_by_username($username)
    {
        $this->db->from($this->table)->limit(1);
        
        foreach(((array) $this->username_column) as $column)
        {
            $this->db->or_where($column, $username);
        }
        
        return $this->db->get()->row();
    }

    public function verify_password($password, $hash)
    {
        return password_verify($password, $hash);
    }

    public function password_hash($password)
    {
        return password_hash($password, PASSWORD_BCRYPT, ['cost' => 10]);
    }
}