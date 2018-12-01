<?php
namespace app\index\controller;

use Workerman\Worker;
use PHPSocketIO\SocketIO;
use Channel\Client;

/**
 * SocketIO服务.
 */
class Socket
{

    protected $io;

    public function __construct()
    {
        $io = new SocketIO(2120);

        // 当socketio服务启动时 连接到channel服务端
        $io->on('workerStart', function()use($io) {
            Client::connect('0.0.0.0', 2206);
        });

        // 当浏览器连接入来时 监听广播事件
        $io->on('connection', function ($socket) use ($io) {

            // 收到Channel广播事件
            Client::on('sayHello', function($event_data) use($socket){
                echo 'sayHello';
                var_dump($event_data);
                $socket->emit('sayHello', $event_data);
             });

        });

        $this->io = $io;

        if (!defined('GLOBAL_START')) {
            Worker::runAll();
        }
    }
}
