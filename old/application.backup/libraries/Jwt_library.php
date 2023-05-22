<?php

class Jwt_library
{
    private $private_key;
    private $public_key;

    public function get_private_key()
    {
        return $this->get_private_key ?? (
            $this->private_key = file_get_contents(FCPATH.'storage/keys/jwt.key')
        );
    }

    public function get_public_key()
    {
        return $this->get_public_key ?? (
            $this->public_key = file_get_contents(FCPATH.'storage/keys/jwt.key.pub')
        );
    }

    public function decode($jwt)
    {
        return \Firebase\JWT\JWT::decode($jwt, $this->get_public_key(), ['RS256']);
    }

    public function encode($payload)
    {
        return \Firebase\JWT\JWT::encode($payload, $this->get_private_key(), 'RS256');
    }
}