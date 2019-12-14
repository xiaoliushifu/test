<?php
$server = new Swoole\WebSocket\Server("0.0.0.0", 9501);
$server->set(array(
    'enable_coroutine' => false,
));
$server->on('open', function (Swoole\WebSocket\Server $server, $request) {
    echo "server: handshake success with fd{$request->fd}\n";
});

//webSocket服务器必选的回调
//核心是理解$frame对象，他是消息对象
$server->on('message', function (Swoole\WebSocket\Server $server, $frame) {
    echo "receive from {$frame->fd}:{$frame->data},opcode:{$frame->opcode},fin:{$frame->finish}\n";
    $server->push($frame->fd, "I'am wsServer");
});

$server->on('close', function ($ser, $fd) {
    echo "client {$fd} closed\n";
});

$server->start();
