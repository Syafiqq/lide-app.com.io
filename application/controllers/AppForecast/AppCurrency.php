<?php
/**
 * This <lide-app.com.io> project created by :
 * Name         : syafiq
 * Date / Time  : 25 November 2016, 9:36 AM.
 * Email        : syafiq.rezpector@gmail.com
 * Github       : syafiqq
 */
defined('BASEPATH') OR exit('No direct script access allowed');

class AppCurrency extends CI_Controller
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

    public function configure()
    {
        if (isset($_SESSION['user']))
        {
            if ($_SESSION['user']['role'] === 'administrator')
            {
                log_message('ERROR', var_export($_SESSION, true));
                $this->load->view('appforecast/appcurrency/configuration');
            }
            else
            {
                echo 'Access Denied';
                //redirect('/');
            }
        }
        else
        {
            echo 'Access Denied';
            //redirect('/');
        }
    }

    public function wsgetsupportedcurrency()
    {
        if ($this->input->is_ajax_request() && ($_SERVER['REQUEST_METHOD'] === 'POST'))
        {
            if (isset($_SESSION['user']))
            {
                if ($_SESSION['user']['role'] === 'administrator')
                {
                    $this->load->model('appforecast/appcurrency/mapac_currency', 'mapac_currency');
                    $currency = $this->mapac_currency->getAllData();
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

    public function wssetworkon()
    {
        if ($this->input->is_ajax_request() && ($_SERVER['REQUEST_METHOD'] === 'POST'))
        {
            if (isset($_SESSION['user']))
            {
                if ($_SESSION['user']['role'] === 'administrator')
                {
                    if ($_POST['from'] > $_POST['to'])
                    {
                        $tmpVal = $_POST['from'];
                        $_POST['from'] = $_POST['to'];
                        $_POST['to'] = $tmpVal;
                        $_POST['flipped'] = true;
                        unset($tmpVal);
                    }
                    else
                    {
                        $_POST['flipped'] = false;
                    }
                    $this->load->model('appforecast/appcurrency/mapac_exchange', 'mapac_exchange');
                    $max_data = $this->mapac_exchange->getTotalData($_POST['from'], $_POST['to']);
                    $_SESSION['app_forecast']['app_currency']['setting']['workon'] = $_POST;
                    $_SESSION['app_forecast']['app_currency']['setting']['total']['data'] = $max_data;
                    echo json_encode(array('code' => 200, 'message' => 'Accepted', 'data' => array('total' => $max_data)));
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

    public function wsloaddata()
    {
        if ($this->input->is_ajax_request() && ($_SERVER['REQUEST_METHOD'] === 'POST'))
        {
            if (isset($_SESSION['user']))
            {
                if ($_SESSION['user']['role'] === 'administrator')
                {
                    $_SESSION['app_forecast']['app_currency']['setting']['total']['selected'] = $_POST['total'];
                    $_SESSION['app_forecast']['app_currency']['setting']['total']['feature'] = $_POST['feature'];

                    $this->load->model('appforecast/appcurrency/mapac_exchange', 'mapac_exchange');
                    $raw_data = $this->mapac_exchange->getData(
                        $_SESSION['app_forecast']['app_currency']['setting']['workon']['from'],
                        $_SESSION['app_forecast']['app_currency']['setting']['workon']['to'],
                        $_SESSION['app_forecast']['app_currency']['setting']['total']['selected'],
                        $_SESSION['app_forecast']['app_currency']['setting']['workon']['flipped']
                    );

                    $_SESSION['app_forecast']['app_currency']['data']['raw'] = $raw_data;

                    //This issue is not effective blob can barely hold 64 KB
                    $dataa = $this->mapac_exchange->formatData($raw_data, $_POST['feature']);
                    $_SESSION['app_forecast']['app_currency']['data']['formatted'] = $dataa;

                    //log_message('ERROR', var_export($dataa, true));
                    //$_SESSION['app_forecast']['app_currency']['data']['formatted'] = array(1, 2, 3, 4, 5);


                    echo json_encode(array('code' => 200, 'message' => 'Accepted'));
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

    public function wsdestroysess()
    {
        session_destroy();
        echo json_encode(array('code' => 200, 'message' => 'Accepted'));
    }
}