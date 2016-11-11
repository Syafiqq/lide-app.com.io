<?php
/**
 * This <lide-app.com.io> project created by : 
 * Name         : syafiq
 * Date / Time  : 10 November 2016, 8:43 PM.
 * Email        : syafiq.rezpector@gmail.com
 * Github       : syafiqq
 */
defined('BASEPATH') OR exit('No direct script access allowed');

class Test extends CI_Controller
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
        // Your own constructor code
    }

    public function index()
    {
        log_message('ERROR', var_export($_POST, true));
        log_message('ERROR', var_export($_GET, true));
        log_message('ERROR', var_export($_REQUEST, true));
        if ($this->input->is_ajax_request())
        {
            log_message('ERROR', "is_ajax");
            header('Content-type: application/json');
            echo json_encode(array('value' => 200));
        }
        else
        {
            log_message('ERROR', "is_not_ajax");
            header('Content-type: application/json');
            echo json_encode(array('value' => 404));
        }

    }
}