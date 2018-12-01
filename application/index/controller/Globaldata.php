<?php
namespace app\index\controller;

use Workerman\Worker;
use GlobalData\Server;
use GlobalData\Client;

/**
 * 数据共享组件服务.
 */
class Globaldata
{
    public function __construct()
    {
        $worker = new Server('0.0.0.0', 2207);
        
        if (!defined('GLOBAL_START')) {
            Worker::runAll();
        }
    }
}
