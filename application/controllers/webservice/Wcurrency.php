<?php
/**
 * This <lide-app.com.io> project created by :
 * Name         : syafiq
 * Date / Time  : 02 December 2016, 3:40 AM.
 * Email        : syafiq.rezpector@gmail.com
 * Github       : syafiqq
 */
defined('BASEPATH') OR exit('No direct script access allowed');

class Wcurrency extends CI_Controller
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

    public function load()
    {
        if ($this->input->is_ajax_request() && ($_SERVER['REQUEST_METHOD'] === 'POST'))
        {
            if (isset($_SESSION['user']))
            {
                if ($_SESSION['user']['role'] === 'administrator')
                {
                    $this->load->model('mcurrency');
                    $currency = $this->mcurrency->getAllData();
                    echo json_encode(array('code' => 200, 'message' => 'Accepted', 'data' => array('currency' => $currency)));
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