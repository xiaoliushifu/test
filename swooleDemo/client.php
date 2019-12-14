<?php
$client = new Swoole\Client(SWOOLE_SOCK_TCP);

//直接连接服务端即可
if (!$client->connect("127.0.0.1","9501",'5')) {
    exit('连接失败');
}

//命令行输出
fwrite(STDOUT,"请输入:");
//等待读取命令行输入
$input = trim(fgets(STDIN));
//发给服务端
$client->send($input);
//等待接收服务端发来的消息
$serData = $client->recv();
printf("来自服务端的数据: %s \n",$serData);