<?php
namespace app\index\controller;

use Workerman\Worker;
use Channel\Server;

/**
 * 分布式通信服务.
 */
class Channel
{
    public function __construct()
    {
        $channel_server = new Server('0.0.0.0', 2206);

        if (!defined('GLOBAL_START')) {
            Worker::runAll();
        }
    }
}

