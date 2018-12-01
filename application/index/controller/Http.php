<?php
namespace app\index\controller;

use Workerman\Worker;
use PHPSocketIO\SocketIO;
use Channel\Client;
use GlobalData\Client as GlobalClient;
use Workerman\Connection\AsyncTcpConnection;
/**
 * HTTP服务.
 */
class Http
{
    public function __construct()
    {
        header('Access-Control-Allow-Origin:*');
        $http_worker = new Worker('http://0.0.0.0:4237');
        $http_worker->name = 'publisher';

        /**
         * 收到客户新消息
         */
        $http_worker->onMessage = function ($connection, $data) {
            $data = $data['get'];
            if (isset($data['task'])) {
                call_user_func([$this, $data['task']], $data);
                $connection->send('收到你的定时任务了');
                return;
            }
            $connection->send('访问出错');
        };

        if (!defined('GLOBAL_START')) {
            Worker::runAll();
        }
    }

    protected function helloWorld($data)
    {
        echo "调用了sayHelloWorld的func\n";
        $msg = ['task' => 'sayHelloWorld', 'data' => ['msg' => 'hello, xiaodi' . $data['job_id']]];
        $msg = json_encode($msg);

        $TaskServer = new AsyncTcpConnection('Text://127.0.0.1:55555');

        // 当连接建立成功时，发送http请求数据
        $TaskServer->onConnect = function ($connection) use ($msg) {
            echo "connect success\n";
            $connection->send($msg);
        };

        $TaskServer->onMessage = function ($connection, $http_buffer) {
            $connection->close();
        };

        $TaskServer->onClose = function ($connection) {
            echo "connection closed\n";
        };

        $TaskServer->onError = function ($connection, $code, $msg) {
            echo "Error code:$code msg:$msg\n";
        };

        $TaskServer->connect();

    }
}
