<?php

class MY_Controller extends CI_Controller
{
    protected $login_page = 'auth/login';
    protected $home_page = 'dashboard';
    protected $layout = 'layouts/dashboard/index';
    protected $load_model;

    protected $payload = ['title' => '', 'top_scripts' => [], 'bottom_scripts' => [], 'nav' => []];
    protected $final_redirect;

    protected $handle_as_json = FALSE;

    public function __construct()
    {
        parent::__construct();
        $this->load->library('session');
        if($this->load_model)
        {
            $this->load->model($this->load_model, 'model');
        }
    }

    protected function render($content)
    {
        $this->load->view($this->layout, array_merge($this->payload, ['content' => $content]));
    }

    protected function render_json($data, $status_code = 200, $encode = TRUE)
    {
        $this->output
            ->set_status_header($status_code)
            ->set_content_type('application/json')
            ->set_output($encode ? json_encode($data) : $data);
    }

    protected function require_auth($allowed_roles = [])
    {
        $allowed_roles = (array) $allowed_roles;
        $role = $this->session->userdata('role');
        if(!in_array($role, $allowed_roles) && !empty($allowed_roles))
        {
            throw new Exception('Require logged', ERR_REQUIRE_LOGGED);
        }
    }

    protected function require_jwt_auth($allow_get_method = FALSE)
    {
        $this->load->library('jwt_library');
        $jwt = $this->input->get_request_header('x-token');
        if($allow_get_method && is_null($jwt))
        {
            $jwt = $this->input->get('token');
        }
        
        $payload = $this->jwt_library->decode($jwt);
        if(is_null($payload))
        {
            throw new Exception('Require logged', ERR_REQUIRE_LOGGED);
        }
    }

    protected function require_guest()
    {
        $role = $this->session->userdata('role');
        if(!empty($role))
        {
            throw new Exception('Require not logged', ERR_REQUIRE_NOT_LOGGED);
        }
    }

    public function _remap($method, $args = [])
    {
        $request_method = $this->input->method();
        $original_method = $method;

        if(method_exists($this, $method . '_' . $request_method))
        {
            $method = $method . '_' . $request_method;
        }

        if(!method_exists($this, $method))
        {
            $method = 'not_found_' . $request_method;
        }

        try
        {
            call_user_func_array([$this, $method], $args);
        }
        catch(Exception $ex)
        {
            if($this->handle_as_json)
            {
                $this->output
                    ->set_status_header(400)
                    ->set_content_type('application/json')
                    ->set_output(json_encode([
                        'class' => get_class($ex),
                        'message' => $ex->getMessage(),
                    ]));
            }
            else switch($ex->getCode())
            {
                case ERR_REQUIRE_LOGGED:
                    $this->handle_require_logged_error();
                break;

                case ERR_REQUIRE_NOT_LOGGED:
                    $this->handle_require_not_logged_error();
                break;

                case ERR_USER_NOT_FOUND:
                case ERR_INCORRECT_PASSWORD:
                case ERR_INSERT_DATA_FAIL:
                case ERR_UPDATE_DATA_FAIL:
                case ERR_DELETE_DATA_FAIL:
                case ERR_INVALID_FORM_DATA:
                case ERR_UNEXPECTED:
                    $this->handle_common_error($method, $original_method, $args, $ex->getMessage());
                break;

                default: throw $ex;
            }
        }
    }

    protected function handle_require_logged_error()
    {
        $intended = $this->uri->uri_string();
        redirect($this->login_page . '?r=' . urlencode($intended));
    }

    protected function handle_require_not_logged_error()
    {
        redirect($this->home_page);
    }

    protected function handle_common_error($method, $original_method, $args, $message)
    {
        $redirect_to = $this->final_redirect;

        if(empty($redirect_to) && $method != $original_method)
        {
            $redirect_to = join('/', array_merge([strtolower(static::class), $original_method], $args));
        }

        if(!empty($redirect_to))
        {
            $this->session->set_flashdata([
                'has_alert' => TRUE,
                'alert_type' => 'danger',
                'alert_message' => $message,
            ]);
            redirect($redirect_to);
        }
    }

    protected function not_found_get()
    {
        if($this->input->is_ajax_request())
        {
            $this->output
                ->set_status_header(404)
                ->set_content_type('application/json')
                ->set_output(json_encode([
                    'message' => '404 Not found',
                ]));
        }
        else
        {
            show_404();
        }
    }

    protected function not_found_post()
    {
        $this->output->set_status_header(403);

        $message = '403 Forbidden. Your request URI is not defined in this system.';
        if($this->input->is_ajax_request())
        {
            $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode([
                    'message' => $message,
                ]));
        }
        else
        {
            $this->output
                ->set_content_type('text/plain')
                ->set_output($message);
        }
    }
}