<?php

class Pwoole {
    static $host = "127.0.0.1";
    static $port = 9501;
    static $options = [
        "worker_num" => 4,
        "task_worker_num" => 4,
        "enable_static_handler" => true,
        "document_root" => '/Users/Andy/demo/music-mobile-back/public',
    ];

    /**
     * 开启swoole http server
     * @param int|null $argPort
     * @param string|null $argHost
     */
    public static function Start(int $argPort = null, string $argHost = null)
    {
        $host = $argHost ?? static::$host;
        $port = $argPort ?? static::$port;
        $server = new swoole_http_server($host, $port);
        $GLOBALS['swoole_server'] = $server;
        $server->set(static::$options);
        $server->on("WorkerStart",  'Pwoole::onWorkerStart');
        $server->on("request",  'Pwoole::onRequest');
        $server->on("task",  'Pwoole::onTask');
        $server->on("finish",  'Pwoole::onFinish');
        $server->start();
    }

    public function onWorkerStart(swoole_server $server, int $worker_id)
    {
        printf("%s\n", $worker_id);
        require __DIR__.'/../thinkphp/base.php';
    }

    public function onRequest($request, $response)
    {
        foreach ($request->server as $key => $value) {
            $_SERVER[ strtoupper($key) ] = $value;
        }
        $_GET = $request->get ?? [];
        $_POST = $request->post ?? [];
        ob_start();
        \think\Container::get('app')->run()->send();
        $output = ob_get_contents();
        ob_end_clean();

        printf("%s %s %s \n",
            $request->server['request_method'],
            $request->server['request_uri'],
            date("Y-m-d H:i:s", $request->server['request_time'])
        );

        $response->end($output);
    }

    public function onTask(swoole_server $server, int $task_id, int $src_worker_id, $data)
    {
        if ($data == 'sendSms') {
            try {
                $sms = new \Qcloud\Sms\SmsSingleSender('1400096944', '3e283931e50258ad432858f24184c063');
                $sms->sendWithParam('86', '18910434780', 130089, [123123]);
            } catch (Exception $e) {
                throw new Exception($e->getMessage());
            }
        }
    }

    public function onFinish(swoole_server $server, int $task_id, $data)
    {
        echo "on finish";
    }
}

//new Pwoole();

Pwoole::Start(9501);