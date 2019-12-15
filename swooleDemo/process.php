<?php


/**
 * php process.php
 */
(new class{
    public $mpid=0;
    public $works=[];
    public $max_process=2;
    public $new_index=0;

    public function __construct(){
        try {
            //修改进程名（默认情况是 php xxxx.php)
//            swoole_set_process_name(sprintf('php-ps:%s', 'master'));
            //用php原生函数修改,但某些平台不支持
//            cli_set_process_title(sprintf('php-ps:%s', 'master'));
            //当前进程id
            $this->mpid = posix_getpid();
            echo "主进程:".$this->mpid.PHP_EOL;
            $this->run();
            //主进程监听在run方法中开启的所有子进程
            $this->processWait();
        }catch (\Exception $e){
            die('ALL ERROR: '.$e->getMessage());
        }
    }

    public function run(){
        for ($i=0; $i < $this->max_process; $i++) {
            $this->CreateProcess($i);
        }
    }

    /**
     * 创建子进程
     * @param null $index
     * @return int
     * @author: LiuShiFu
     */
    public function CreateProcess($index=null){
        //创建swooleProcess对象，并配置子进程任务（也就是回调函数），这里只是初始化
        //还没有启动子进程
        $process = new swoole_process(function(swoole_process $worker)use($index){
            echo "I am process {$index}".PHP_EOL;
            echo "子进程回调函数中，当前进程是:".posix_getpid().PHP_EOL;

            //修改子进程名称，由于子进程会继承父进程的东西，如果不修改名称，那么正常应该是 php process.php
            //mac的命令行执行 ps -ef | grep process.php 可以看到
//            cli_set_process_title(sprintf('php-child-ps:%s', $index));
            //检测父进程是否退出
            for ($j = 0; $j < 10; $j++) {
                //$this->checkMpid($worker);
                echo "子进程:{$index}-msg: {$j}\n";
                sleep(1);
            }
            //自己销毁,暂不知其他消除当前子进程的方法
            $worker->exit();
        }, false, false);

        echo "start前当前进程是:".posix_getpid().PHP_EOL;
        //这一步是fork出子进程
        $pid=$process->start();
        //子进程的id存起来
        $this->works[$index]=$pid;
        return $pid;
    }

//    public function checkMpid(&$worker){
//        //主进程是否退出
//        if(!swoole_process::kill($this->mpid,0)){
//            $worker->exit();
//            // 这句提示,实际是看不到的.需要写到日志中
//            echo "Master process exited, I [{$worker['pid']}] also quit\n";
//        }
//    }

//    public function rebootProcess($ret){
//        $pid=$ret['pid'];
//        //找到有问题的子进程，重新启动
//        $index=array_search($pid, $this->works);
//        if($index!==false){
//            $index=intval($index);
//            //复用$index，并不是复用pid
//            $new_pid=$this->CreateProcess($index);
//            echo "rebootProcess: {$index}={$new_pid} Done\n";
//            return;
//        }
//        throw new \Exception('rebootProcess Error: no pid');
//    }


    /**
     * swoole的开启的子进程需要开发人员自己销毁，而不是pcntl会自动销毁
     * @param $ret
     * @author: LiuShiFu
     */
    public function closeChildProcess($ret){
        if ($ret['code'] == 0) {
            $pid = $ret['pid'];
            //找到子进程
            $index = array_search($pid, $this->works);
            if ($index !== false) {
                $index = intval($index);
                unset($this->works[$index]);
                echo "{$index}={$pid} Done\n";
                return;
            }
            throw new \Exception('closeProcess Error: no pid');
        }
        echo "{$ret[pid]}未结束\n";
    }

    /**
     * 在主进程监听它的子进程
     * @author: LiuShiFu
     */
    public function processWait(){
        while(1) {
            echo "processWait前当前进程是:".posix_getpid().PHP_EOL;
            if(count($this->works)){
                //阻塞监听
                $ret = swoole_process::wait();
                if ($ret) {
                    echo "监听到子进程信息:".PHP_EOL;
                    print_r($ret);
                    $this->closeChildProcess($ret);
                }
            }else{
                echo "主进程销毁:".PHP_EOL;
                swoole_process::kill($this->mpid,0);
                break;
            }
        }
    }
});