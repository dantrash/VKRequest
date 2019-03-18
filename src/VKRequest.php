<?php
//error_reporting(E_ALL);
//ini_set('display_errors', 'on');
namespace VKRequestClass;

class VKRequest
{
    private $vvk = 5.92, $token, $vk_method, $lang, $options = '';
    const VK_URL = 'https://api.vk.com/method/', ERROR_LOG = 'Errors_VK.txt', LOG_FILE = 'VK_log.txt';
    private static $logs = array();
    private static $errors = array();
    private function addError ($log)
    {
        if (is_array($log))
            $srt_log = var_export($log, 1);
        else
            $srt_log = $log;

        self::$logs[] = '* ' . date('d.m.Y H:i:s') . ' | method: ' . $this -> vk_method .' --> ' . $srt_log;
    }

    private function saveLogs ()
    {
        if (!empty(self::$logs))
            file_put_contents(self::LOG_FILE , self::$logs, FILE_APPEND);
        if (!empty(self::$errors))
            file_put_contents(self::ERROR_LOG , self::$errors, FILE_APPEND);
    }
    public function setVvk ($v)
    {
        $this -> vvk = $v;
    }
    public function setToken ($s)
    {
        $this -> token = $s;
    }

    public function setMethod ($m)
    {
        $this -> vk_method = $m;
    }
    function __construct($token, $vk_method, $vvk = 5.92, $lang = 'ru')
    {
        $this -> token = $token;
        $this -> vk_method = $vk_method;
        $this -> vvk = $vvk;
        $this -> lang = $lang;
    }

    public function __destruct()
    {
        $this -> saveLogs();
    }

    public function setOptions ($opt = array()){
        if(!empty($opt))
            $this -> options = http_build_query($opt);
    }

    /**
     * @param bool $to_array
     * @param null $user_token
     * @return bool|string
     */
    public function vkGet ($to_array = true, $user_token = null)
    {
        $t = ($user_token)?:$this -> token;
        $ful_url = self::VK_URL . $this -> vk_method . '?' . ($this -> options?($this -> options . '&'):'') . 'lang=' . $this->lang . '&v=' . $this->vvk . '&access_token=' . $t;
        try {
            $result = file_get_contents($ful_url);
            $decode_res = json_decode($result, 1);
        }
        catch (\Error $e){
            $exception_text = 'No server answer! (info: ' . $e .')';
            print_r($exception_text);
            $this->addError($exception_text);
            return "ERROR: $exception_text";
        }
        if(isset($decode_res['response'])) {
            if ($to_array)
                return $decode_res['response'];
            else
                return $result;
        }elseif($decode_res ) {
            $this->addError($decode_res);
            return "ERROR: (" . print_r($decode_res, 1) .")";
        }
        else {
            return $result;
        }
    }

    /**
     * @param $count
     * @param float $pause
     * @param bool $to_array
     * @param null $user_token
     * @return array|mixed
     */
    public function vkManyGet($count, $pause = 1.5, $to_array = true, $user_token = null){
        $all_answers = $all_errors = array();
        $one_answer = null;
        while ($count > 0) {
            $one_answer = $this -> vkGet (true, $user_token?:$this -> token);
            if (isset($one_answer['items'])){
                $all_answers = array_merge($all_answers, $one_answer['items']);
            }else{
                if (is_string($one_answer) && strripos($one_answer, 'ERROR') !== false)
                    $all_errors[] = $one_answer;
                else
                    $all_answers[] = $one_answer;
            }
            sleep($pause);
            $count--;
        }
        if (isset($one_answer['count']) && $one_answer['items'])
            $final_array = array(
                'count' => $one_answer['count'],
                'items_count' => count($all_answers),
                'items' => $all_answers,
                'errors' => empty($all_errors)?null:$all_errors
            );
        else
            $final_array = $all_answers;
        if ($to_array)
            return $final_array;
        else
            return print_r($final_array, 1);
    }

    public function vkPrint ($print_data) {
        if (is_array($print_data)){
            echo '<pre>';
            print_r($print_data);
            echo '</pre>';
        }else{
            print_r($print_data);
        }
    }

}

$request_test = new VKRequest(
    '62331ec8745ced335f1d3c2a410f6a8975f8ee393cc3a0b923505fdfaa4bfe5446cf61713c491ea19c5f4',
    'wall.get'
);
$request_test -> setOptions(['count' => 5,]);
$request_test->vkPrint($request_test -> vkManyGet(3));