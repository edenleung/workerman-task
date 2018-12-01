<?php
namespace app\api\controller;

use think\worker\Server;
use Workerman\Lib\Timer;

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
        sleep(5);
        $connection->send('完成');
    }
}