<?php
/**
 * This <lide-app.com.io> project created by :
 * Name         : syafiq
 * Date / Time  : 02 December 2016, 6:47 AM.
 * Email        : syafiq.rezpector@gmail.com
 * Github       : syafiqq
 */
use Carbon\Carbon;

defined('BASEPATH') OR exit('No direct script access allowed');

class Welm extends CI_Controller
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

    public function loadProfile()
    {
        if ($this->input->is_ajax_request() && ($_SERVER['REQUEST_METHOD'] === 'POST'))
        {
            if (isset($_SESSION['user']))
            {
                if ($_SESSION['user']['role'] === 'administrator')
                {
                    $this->load->model('melm_profile');
                    $profile = $this->melm_profile->load($_POST['name'], $_POST['from'], $_POST['to'], $_POST['method']);
                    if (count($profile) > 0)
                    {
                        $_SESSION['elm']['profile'] = $profile[0];
                        echo json_encode(array('code' => 200, 'message' => 'Accepted', 'data' => $_SESSION['elm']['profile']));
                    }
                    else
                    {
                        echo json_encode(array('code' => 404, 'message' => 'Profile does not exists'));
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

    public function doProcess()
    {
        if ($this->input->is_ajax_request() && ($_SERVER['REQUEST_METHOD'] === 'POST'))
        {
            if (isset($_SESSION['user']))
            {
                if ($_SESSION['user']['role'] === 'administrator' && isset($_SESSION['elm']['profile']))
                {
                    $this->load->model('mexchange');
                    $rawCurrency = $this->mexchange->loadValueWithSpecificDate(
                        $_SESSION['elm']['profile']['from'],
                        $_SESSION['elm']['profile']['to'],
                        Carbon::createFromFormat('Y-m-d', $_SESSION['elm']['profile']['start'])->addDays(-1 * ($_SESSION['elm']['profile']['feature'] + 1))->toDateString(),
                        $_SESSION['elm']['profile']['total'] + $_SESSION['elm']['profile']['feature']);
                    $formattedCurrency = $this->mexchange->formatData(
                        $rawCurrency,
                        $_SESSION['elm']['profile']['feature']
                    );

                    $this->load->model('melm');
                    $weight = $this->melm->generateWeight($_SESSION['elm']['profile']['feature']);
                    $bias = $this->melm->generateBias($_SESSION['elm']['profile']['feature']);
                    $minMax = $this->melm->generateNormalizationBound($rawCurrency, 5000);

                    $_SESSION['elm']['profile']['metadata']['weight'] = $weight;
                    $_SESSION['elm']['profile']['metadata']['bias'] = $bias;
                    $_SESSION['elm']['profile']['metadata']['minmax'] = $minMax;

                    $this->melm->registerMetadata($_SESSION['elm']['profile']['feature'], $weight, $bias, $minMax, $_SESSION['elm']['profile']['bias'] == 1 ? true : false, $_SESSION['elm']['profile']['method']);
                    $this->melm->learn($formattedCurrency);

                    $_SESSION['elm']['profile']['metadata']['beta_topi'] = $this->melm->getBetaTopi()->toArray();
                    $_SESSION['elm']['profile']['metadata']['accuracy'] = $this->melm->getData()['data']['mape']['training'];

                    echo json_encode(array('code' => 200, 'message' => 'Accepted', 'data' => array('accuracy' => $_SESSION['elm']['profile']['metadata']['accuracy'])));
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

    public function saveData()
    {
        if ($this->input->is_ajax_request() && ($_SERVER['REQUEST_METHOD'] === 'POST'))
        {
            if (isset($_SESSION['user']))
            {
                if (
                    $_SESSION['user']['role'] === 'administrator'
                    && isset($_SESSION['elm']['profile']['metadata']['weight'])
                    && isset($_SESSION['elm']['profile']['metadata']['bias'])
                    && isset($_SESSION['elm']['profile']['metadata']['accuracy'])
                    && isset($_SESSION['elm']['profile']['metadata']['minmax'])
                    && isset($_SESSION['elm']['profile']['metadata']['beta_topi'])
                )
                {
                    $this->load->model('melm_profile');
                    $this->melm_profile->updateAccuracy($_SESSION['elm']['profile']['id'], $_SESSION['elm']['profile']['metadata']['accuracy']);
                    $this->melm_profile->updateBound($_SESSION['elm']['profile']['id'], $_SESSION['elm']['profile']['metadata']['minmax']['min'], $_SESSION['elm']['profile']['metadata']['minmax']['max']);

                    $this->load->model('melm_weight');
                    $this->load->model('melm_bias');
                    $this->load->model('melm_beta_topi');
                    $this->melm_weight->add($_SESSION['elm']['profile']['id'], $_SESSION['elm']['profile']['metadata']['weight']);
                    $this->melm_bias->add($_SESSION['elm']['profile']['id'], $_SESSION['elm']['profile']['metadata']['bias']);
                    $this->melm_beta_topi->add($_SESSION['elm']['profile']['id'], $_SESSION['elm']['profile']['metadata']['beta_topi']);

                    unset($_SESSION['elm']['profile']['metadata']);

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

    public function mark()
    {
        if ($this->input->is_ajax_request() && ($_SERVER['REQUEST_METHOD'] === 'POST'))
        {
            if (isset($_SESSION['user']))
            {
                if ($_SESSION['user']['role'] === 'administrator' && isset($_SESSION['elm']['profile']))
                {
                    $this->load->model('melm_profile');

                    $this->melm_profile->disableProfile($_SESSION['elm']['profile']['from'], $_SESSION['elm']['profile']['to']);
                    $this->melm_profile->markAsActive($_SESSION['elm']['profile']['id']);

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

    public function predictAll()
    {
        if ($this->input->is_ajax_request() && ($_SERVER['REQUEST_METHOD'] === 'POST'))
        {
            if (isset($_SESSION['user']))
            {
                if ($_SESSION['user']['role'] === 'administrator' && isset($_SESSION['elm']['profile']))
                {
                    $this->load->model('melm_profile');
                    $minMax = $this->melm_profile->loadBound($_SESSION['elm']['profile']['id']);
                    if (count($minMax) > 0)
                    {
                        $minMax = $minMax[0];
                        $minMax['range'] = $minMax['max'] - $minMax['min'];

                        $this->load->model('melm_weight');
                        $weight = $this->melm_weight->load($_SESSION['elm']['profile']['id']);
                        if (count($weight) > 0)
                        {
                            $this->load->model('melm_bias');
                            $bias = $this->melm_bias->load($_SESSION['elm']['profile']['id']);
                            if (count($bias) > 0)
                            {
                                $this->load->model('melm_beta_topi');
                                $beta_topi = $this->melm_beta_topi->load($_SESSION['elm']['profile']['id']);
                                if (count($bias) > 0)
                                {
                                    $this->load->model('mexchange');
                                    $rawCurrency = $this->mexchange->loadAllForTesting(
                                        $_SESSION['elm']['profile']['from'],
                                        $_SESSION['elm']['profile']['to']);
                                    $formattedCurrency = $this->mexchange->formatDataTesting(
                                        $rawCurrency,
                                        $_SESSION['elm']['profile']['feature']
                                    );

                                    $this->load->model('melm');
                                    $this->melm->registerMetadata($_SESSION['elm']['profile']['feature'], $weight, $bias, $minMax, $_SESSION['elm']['profile']['bias'] == 1 ? true : false, $_SESSION['elm']['profile']['method']);
                                    $this->melm->assignOutputWeight($beta_topi);
                                    $this->melm->test($formattedCurrency);

                                    $this->mexchange->assignPredict(
                                        $_SESSION['elm']['profile']['from'],
                                        $_SESSION['elm']['profile']['to'],
                                        $formattedCurrency);
                                    echo json_encode(array('code' => 200, 'message' => 'Accepted'));
                                    return;
                                }
                            }
                        }
                    }
                    echo json_encode(array('code' => 407, 'message' => 'Insufficient data'));
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

    public function predict()
    {
        if ($this->input->is_ajax_request() && ($_SERVER['REQUEST_METHOD'] === 'POST'))
        {
            if (isset($_POST['value']) &&
                isset($_POST['date']) &&
                isset($_POST['value']) &&
                isset($_POST['value'])
            )
            {
                $this->load->model('melm_profile');
                $minMax = $this->melm_profile->loadBound($_SESSION['elm']['profile']['id']);
                if (count($minMax) > 0)
                {
                    $minMax = $minMax[0];
                    $minMax['range'] = $minMax['max'] - $minMax['min'];

                    $this->load->model('melm_weight');
                    $weight = $this->melm_weight->load($_SESSION['elm']['profile']['id']);
                    if (count($weight) > 0)
                    {
                        $this->load->model('melm_bias');
                        $bias = $this->melm_bias->load($_SESSION['elm']['profile']['id']);
                        if (count($bias) > 0)
                        {
                            $this->load->model('melm_beta_topi');
                            $beta_topi = $this->melm_beta_topi->load($_SESSION['elm']['profile']['id']);
                            if (count($bias) > 0)
                            {
                                $this->load->model('mexchange');
                                $rawCurrency = $this->mexchange->loadAllForTesting(
                                    $_SESSION['elm']['profile']['from'],
                                    $_SESSION['elm']['profile']['to']);
                                $formattedCurrency = $this->mexchange->formatDataTesting(
                                    $rawCurrency,
                                    $_SESSION['elm']['profile']['feature']
                                );

                                $this->load->model('melm');
                                $this->melm->registerMetadata($_SESSION['elm']['profile']['feature'], $weight, $bias, $minMax, $_SESSION['elm']['profile']['bias'] == 1 ? true : false, $_SESSION['elm']['profile']['method']);
                                $this->melm->assignOutputWeight($beta_topi);
                                $this->melm->test($formattedCurrency);

                                $this->mexchange->assignPredict(
                                    $_SESSION['elm']['profile']['from'],
                                    $_SESSION['elm']['profile']['to'],
                                    $formattedCurrency);
                                echo json_encode(array('code' => 200, 'message' => 'Accepted'));
                                return;
                            }
                        }
                    }
                }
                echo json_encode(array('code' => 407, 'message' => 'Insufficient data'));
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

    public function simulate()
    {
        echo json_encode(array('code' => 403, 'message' => 'Access Denied'));
        return;

        $this->load->model('melm_profile');
        $profiles = $this->melm_profile->loadAllActiveProfile();
        foreach ($profiles as $profile)
        {
            $this->doSimulate($profile);
        }
        echo json_encode(array('code' => 200, 'message' => 'Accepted'));
    }

    private function doSimulate($profile)
    {
        $this->load->model('mcurrency');
        $from = $this->mcurrency->getData($profile['from']);
        if (count($from) > 0)
        {
            $from = $from[0];
            $to = $this->mcurrency->getData($profile['to']);
            if (count($to) > 0)
            {
                $to = $to[0];
                $time = Carbon::now('Asia/Jakarta')->addDay(-1);
                $jsonp = false;
                do
                {
                    $jsonp = $this->getDataFixerIO($time->toDateString(), strtoupper($from['code']), strtoupper($to['code']));
                }
                while (!$jsonp);

                if ($jsonp)
                {
                    $jsonp = json_decode($jsonp, true);
                    $this->load->model('mexchange');
                    $this->mexchange->removeExchangeValueForward($time->toDateString(), $from['id'], $to['id']);
                    $this->mexchange->storeOrUpdate($from['id'], $to['id'], $time->toDateString(), $jsonp['rates'][strtoupper($to['code'])]);
                    $this->doPredict($profile, $time, 30);
                }
            }
        }
    }

    private function getDataFixerIO($now, $from, $to)
    {
        //  Initiate curl
        $ch = curl_init();
        // Disable SSL verification
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        // Will return the response, if false it print the response
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        // Set the url
        curl_setopt($ch, CURLOPT_URL, "http://api.fixer.io/${now}?base=${from}&symbols=${to}");
        // Execute
        $result = curl_exec($ch);
        // Closing
        curl_close($ch);

        return $result;
    }

    /**
     * @param array $profile
     * @param Carbon $now
     * @param int $days
     */
    private function doPredict($profile, $now, $days)
    {
        $this->load->model('melm_profile');
        $minMax = $this->melm_profile->loadBound($profile['id']);
        if (count($minMax) > 0)
        {
            $minMax = $minMax[0];
            $minMax['range'] = $minMax['max'] - $minMax['min'];

            $this->load->model('melm_weight');
            $weight = $this->melm_weight->load($profile['id']);
            if (count($weight) > 0)
            {
                $this->load->model('melm_bias');
                $bias = $this->melm_bias->load($profile['id']);
                if (count($bias) > 0)
                {
                    $this->load->model('melm_beta_topi');
                    $beta_topi = $this->melm_beta_topi->load($profile['id']);
                    if (count($bias) > 0)
                    {
                        $this->load->model('mexchange');
                        $raw = $this->mexchange->loadValueFromWithLimit($profile['from'], $profile['to'], $now->toDateString(), $profile['feature']);
                        $formatted = array(array(
                            'data' => $raw,
                            'date' => ($now = $now->addDay(1))->toDateString()
                        ));

                        $this->load->model('melm');
                        $this->melm->registerMetadata($profile['feature'], $weight, $bias, $minMax, $profile['bias'] == 1 ? true : false, $profile['method']);
                        $this->melm->assignOutputWeight($beta_topi);
                        for ($ct = -1, $cs_t = $days, $t_feature = $profile['feature']; ++$ct < $cs_t;)
                        {
                            $this->melm->test($formatted);
                            $this->mexchange->assignPredict(
                                $profile['from'],
                                $profile['to'],
                                $formatted);
                            //log_message('ERROR', var_export(array($formatted[0]), true));
                            foreach ($formatted as $tk => $tkv)
                            {
                                for ($cf = -1, $cs_f = $t_feature - 1; ++$cf < $cs_f;)
                                {
                                    $formatted[$tk]['data'][$cf] = $formatted[$tk]['data'][$cf + 1];
                                }
                                $formatted[$tk]['data'][$t_feature - 1] = $formatted[$tk]['class']['actual'];
                                $formatted[$tk]['date'] = ($now = $now->addDay(1))->toDateString();
                            }
                        }
                    }
                }
            }
        }
    }
}