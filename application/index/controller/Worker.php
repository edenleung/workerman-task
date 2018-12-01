<?php
namespace app\index\controller;

use think\worker\Server;
use Workerman\Lib\Timer;
use Channel\Client;

class Worker extends Server
{
    protected $socket = 'Text://0.0.0.0:55555';

    protected $processes = 100;
    protected $name = 'TaskWorker';

    /**
     * worker启动.
     */
    public function onWorkerStart($worker)
    {
        $this->worker = $worker;
        $worker->name = 'TaskWorker';
        Client::connect('0.0.0.0', 2206);
    }

    public function onConnect($connection)
    {
    }

    public function onMessage($connection, $data)
    {
        echo '定时任务收到你的消息了';
        echo 'data:' . $data . "\n";

        $data = \json_decode($data, true);
        call_user_func([$this, $data['task']], [$connection, $data]);
        
    }

    public function onClose($connection)
    {
    }

    protected function sayHelloWorld($data)
    {
        list($connection, $params) = $data;
        echo "正在处理task: sayHelloWorld\n";
        sleep(rand(1, 5));
        $event_name = 'sayHello';
        // 广播事件
        Client::publish($event_name, $params);
        $connection->send('完成');
    }
}