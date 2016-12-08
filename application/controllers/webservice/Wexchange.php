<?php
/**
 * This <lide-app.com.io> project created by :
 * Name         : syafiq
 * Date / Time  : 02 December 2016, 4:52 AM.
 * Email        : syafiq.rezpector@gmail.com
 * Github       : syafiqq
 */
defined('BASEPATH') OR exit('No direct script access allowed');

class Wexchange extends CI_Controller
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
        // Your own constructor code
    }

    public function index()
    {

    }

    public function store()
    {
        if ($this->input->is_ajax_request() && ($_SERVER['REQUEST_METHOD'] === 'POST'))
        {
            if (isset($_SESSION['user']))
            {
                if ($_SESSION['user']['role'] === 'administrator')
                {
                    if (isset($_POST['value']))
                    {
                        $this->load->model('mexchange');
                        $this->mexchange->storeOrUpdate($_POST['base'], $_POST['to'], $_POST['date'], $_POST['value']);
                        echo json_encode(array('code' => 200, 'message' => 'Accepted'));
                    }
                    else
                    {
                        echo json_encode(array('code' => 407, 'message' => 'Value not defined'));
                    }
                }
                else
                {
                    echo json_encode(array('code' => 403, 'message' => 'Access Denied'));
                }
            }
            else
            {
                echo json_encode(array('code' => 403, 'message' => 'Access Denied'));
            }
        }
        else
        {
            echo json_encode(array('code' => 401, 'message' => 'Bad Request'));
        }
    }
}