<?php

class Api_upload_library
{
    protected $config = [
        'temp_path' => FCPATH . 'storage/temp/',
        'max_size'  => 50e6,
        'accepts'   => [
            'image/bmp',
            'image/gif',
            'image/jpeg',
            'image/png',
        ],
    ];

    private $CI;

    public function __construct()
    {
        $this->CI = &get_instance();
    }

    public function do_upload($using_jwt = FALSE)
    {
		$raw_file = $this->CI->input->raw_input_stream;
		$size = strlen($raw_file);
		if($size <= 0)
		{
			throw new Exception('File can\'t be empty', 1);
		}

		if($size > $this->config['max_size'])
		{
			throw new Exception('File too large', 2);
		}

        $credential_key = $this->get_credential_key($using_jwt);

		do
		{
			$key = bin2hex(random_bytes(4));
			$tmp_path = $this->config['temp_path'].$credential_key.'.'.$key.'.tmp';
		}
		while(file_exists($tmp_path));

		if(!file_exists(dirname($tmp_path)))
		{
			mkdir(dirname($tmp_path), 0777, true);
		}

		$resource = fopen($tmp_path, 'x+');
		fwrite($resource, $raw_file);
		fclose($resource);

		$mime = mime_content_type($tmp_path);

		if(!in_array($mime, $this->config['accepts']))
		{
			unlink($tmp_path);
			throw new Exception('Unsupported file type', 3);
		}

		return [
			'contentType' => $mime,
			'key' => $key,
		];
    }

    public function get_temp_filepath($key, $using_jwt = FALSE)
    {
		$credential_key = $this->get_credential_key($using_jwt);
        if(file_exists($filepath = $this->config['temp_path'].$credential_key.'.'.$key.'.tmp'))
        {
            return $filepath;
        }
        return FALSE;
    }

    protected function get_credential_key($using_jwt)
    {
		if($using_jwt)
		{
			$this->CI->load->library('jwt_library');
			$jwt = $this->CI->input->get_request_header('x-token');
			$payload = $this->CI->jwt_library->decode($jwt);
			return $payload->jti;
		}
        return $this->CI->session->session_id;
    }
}