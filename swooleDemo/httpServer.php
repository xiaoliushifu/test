<?php

//http服务器,也就是web服务器
$serv = new Swoole\Http\Server('0.0.0.0', 8011);
//设置服务端配置参数
$serv->set(array(
    'worker_num' => 2,
    //httpServer特有的配置
    'enable_coroutine' => false,
    //这俩配置配合使用，不再监听request事件
    'enable_static_handler' =>true,
    'document_root' =>"./html",
));

//http服务器特有的事件
$serv->on('Request', function ($req, $res){
    print_r($req);
    print_r($req->get);
    $res->end("<h1>Hello Swoole. #".rand(1000, 9999)."</h1>");
});

//最终启动服务
$serv->start();