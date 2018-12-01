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
        $io->on('connection', function ($socket) use ($io) {
        });

        $this->io = $io;

        if (!defined('GLOBAL_START')) {
            Worker::runAll();
        }
    }
}
