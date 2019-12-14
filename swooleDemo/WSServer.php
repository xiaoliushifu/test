<?php

/**
 * 面向对象方式使用swoole的ws服务
 * Class WebSocketServer
 */

class WebSocketServer
{
    CONST HOST="0.0.0.0";
    CONST PORT="9502";
    private $ws;
    public function __construct()
    {
        $this->ws = new Swoole\WebSocket\Server(self::HOST,self::PORT);
        $this->ws->on('open',[$this,'onOpen']);
        $this->ws->on('message',[$this,'onMessage']);
        $this->ws->on('close',[$this,'onClose']);
        $this->ws->start();
    }

    public function onOpen($ws,$req)
    {
        echo "WSserver: handshake success with fd{$req->fd}\n";
    }

    public function onMessage($ws,$frame)
    {
        echo "receive from {$frame->fd}:{$frame->data},opcode:{$frame->opcode},fin:{$frame->finish}\n";
        $ws->push($frame->fd, "I'am wsServer");
    }

    public function onClose($ws,$fd)
    {
        echo "client {$fd} closed\n";
    }
}

$class = new WebSocketServer();