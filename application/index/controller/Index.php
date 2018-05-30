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
        try {
            $sms = new SmsSingleSender('1400096944', '3e283931e50258ad432858f24184c063');
            $result = $sms->send(0, '86', '18910434780', '您的验证码是123123');
            echo json_encode($result);
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }
    }

    public function hello($name = 'ThinkPHP5')
    {
        return 'hello,' . $name;
    }
}
