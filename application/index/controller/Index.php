<?php
namespace app\index\controller;

use Qcloud\Sms\SmsSingleSender;

class Index
{
    public function index()
    {
        return view();
    }

    public function ken()
    {
        $GLOBALS['swoole_server']->task('sendSms');

        return "短信发送成功";
        /*try {
            $sms = new SmsSingleSender('1400096944', '3e283931e50258ad432858f24184c063');
            $result = $sms->sendWithParam('86', '18910434780', 130089, [123123]);
            print_r($result);
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }*/
    }

    public function hello($name = 'ThinkPHP5')
    {
        return 'hello,' . $name;
    }
}
