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
    printf("request come\n");
    printf("%s\n",$req->server['request_uri']);

    if ($req->server['request_uri'] === '/') {
        $res->header('Content-Type', 'text/html');

//        $res->header('Cache-control', 'no-cache');
//        $res->header('Etag', 'xxxxxu');
//        $res->header('Cache-control', 'private');
        $res->sendfile("./html/index.html");
    }

    //配合nginx代理，测试http缓存
    if ($req->server['request_uri'] === '/data') {
        $res->header('Content-Type', 'text/plain');
        $res->header('Cache-control', 'max-age=10,s-maxage=20');
        $res->header('Vary', 'X-test-cache');
        $res->end("i am a data hello world");
    }

//    $res->end("<h2>Hello Swoole. ".rand(1000, 9999)."</h2>");
});

//最终启动服务
$serv->start();