<?php

$serv = new Swoole_server("0.0.0.0",9501);
//$serv = new Swoole\Server('0.0.0.0', 9501, SWOOLE_BASE, SWOOLE_SOCK_TCP);
//设置服务端配置参数
$serv->set(array(
    //worker进程数。进程关系：启动服务时，swoole底层会开启一个主进程，并fork一个子进程。这俩进程不管业务而只管理工作进程
    //然后由子进程启动工作进程，所以实际启动的进程总是n+2
    'worker_num' => 2,
//    'daemonize' => true,
    'backlog' => 128,
));

//一些网络监听事件
$serv->on('Connect', function ($serv, $fd,$reactor_id) {
    //$fd是客户端唯一标示
    echo " 客户端{$fd} connected: 线程是: {$reactor_id}\n";
});
//$serv->on('Request', function ($req, $resp){
//    echo "hello world";
//});
$serv->on('Receive', function ($serv,$fd,$reactor_id,$data){
    //把客户端发来的数据，原样发给客户端
    $serv->send($fd,"线程: {$reactor_id}: " . $data."\n");
});

$serv->on('Close', function ($serv, $fd){
    echo "Client:{$fd}  closed\n";
});

//最终启动服务（守护进程）
$serv->start();