<?php

/**
 * 面向对象方式使用swoole的ws服务
 *
 * task是所有Server支持的，用来异步执行耗时的逻辑（比如发邮件短信等）
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
        //关闭协程
        $this->ws->set([
            'enable_coroutine'=>false,
            'worker_num'=>2,
            //使用task必须设置task进程池数量
            'task_worker_num'=>2,
        ]);

        $this->ws->on('open',[$this,'onOpen']);
        $this->ws->on('message',[$this,'onMessage']);
        $this->ws->on('close',[$this,'onClose']);
        $this->ws->on('task',[$this,'onTask']);
        $this->ws->on('finish',[$this,'onFinish']);
        $this->ws->start();
    }

    public function onOpen($ws,$req)
    {
        echo "WSserver: handshake success with fd{$req->fd}\n";
    }

    public function onMessage($ws,$frame)
    {
        echo "receive from {$frame->fd}:{$frame->data},opcode:{$frame->opcode},fin:{$frame->finish}\n";

        //自定义task任务（就是任意的php变量）
        $data=[
            'fd'=>$frame->fd,
            'task'=>2
        ];
        //投递给task进程池的任务，可以指定池中的哪个task进程，默认是空闲的task进程
        $ws->task($data);
        $ws->push($frame->fd, "I'am wsServer");
    }

    public function onClose($ws,$fd)
    {
        echo "client {$fd} closed\n";
    }

    /**
     * 在task进程内调用而不是swoole的worker进程内
     * 服务对象通过task()方法，开启投递任务，也就开始执行onTask
     * @param $ws
     * @param $taskId
     * @param $workId
     * @param $taskData
     * @return string
     * @author: LiuShiFu
     */
    public function onTask($ws,$taskId,$workId,$taskData)
    {
        echo "task:taskID:{$taskId} workId:{$workId},任务";print_r($taskData);
        sleep(10);
        //返回的数据，就是finish回调的数据
        return "task事件结束了";
    }

    /**
     * onTask结束后调用的方法
     * @param $ws
     * @param $taskId
     * @param $taskResult
     * @author: LiuShiFu
     */
    public function onFinish($ws,$taskId,$taskResult)
    {
        echo "finish:taskID:{$taskId},任务结果:{$taskResult}\n";
    }
}

$class = new WebSocketServer();