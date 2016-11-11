<?php
/**
 * This <lide-app.com.io> project created by : 
 * Name         : syafiq
 * Date / Time  : 10 November 2016, 8:43 PM.
 * Email        : syafiq.rezpector@gmail.com
 * Github       : syafiqq
 */
defined('BASEPATH') OR exit('No direct script access allowed');

class Wsauth extends CI_Controller
{
    /**
     * Index Page for this controller.
     *
     * Maps to the following URL
     *        http://example.com/index.php/welcome
     *    - or -
     *        http://example.com/index.php/welcome/index
     *    - or -
     * Since this controller is set as the default controller in
     * config/routes.php, it's displayed at http://example.com/
     *
     * So any other public methods not prefixed with an underscore will
     * map to /index.php/welcome/<method_name>
     * @see https://codeigniter.com/user_guide/general/urls.html
     */

    public function __construct()
    {
        parent::__construct();
        $this->load->helper('url');
        $this->load->library('session');
    }

    public function login()
    {
        if (!isset($_SESSION['user']))
        {
            if ($this->input->is_ajax_request() && ($_SERVER['REQUEST_METHOD'] === 'POST'))
            {
                log_message('ERROR', var_export(array('get' => $_GET, 'post' => $_POST), true));
                $this->load->model('Mauth', 'mauth');
                $result = $this->mauth->login($_POST['email'], $_POST['password']);
                if (!empty($result))
                {
                    $this->session->set_userdata('user', $result[0]);
                }
                echo json_encode(array('code' => 200, 'message' => 'Success'));
            }
            else
            {
                echo json_encode(array('code' => 401, 'message' => 'Bad Request'));
                return;
            }
        }
        else
        {
            echo json_encode(array('code' => 403, 'message' => 'Access Denied'));
            return;
        }
    }
}