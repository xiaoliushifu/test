<?php

// 数组模拟队列
//队尾入，对头出；

class arrayQueue {

    public $arr = array(0,0,0);

    //初始化时，队列的头和尾都是指向一处
    public $f = -1;// 队列头
    public $r = -1;// 队列尾

    /**
     * 队列是否为空
     * @return bool
     * @author: LiuShiFu
     */
    public function isEmpty():bool {
        return $this->f == $this->r;
    }

    /**
     * 队尾指针是否指向队尾位置
     * @return bool
     * @author: LiuShiFu
     */
    public function isFull():bool {
        return $this->r == count($this->arr) -1;
    }

    /**
     * 打印队列
     * @author: LiuShiFu
     */
    public function showQueue() {
        if($this->isEmpty()) {
            print("队列为空".PHP_EOL);
            return;
        }
        //从尾部指针开始，一直指向到头部指针
        //头部指针始终指向头部，不能遍历的移动指针，除非出队列；
        //尾部指针同理
        echo "队列数据是：".PHP_EOL;
        for($i=$this->r;$i>$this->f;$i--) {
            printf("arr[%d]=%d".PHP_EOL,$i,$this->arr[$i]);
        }
    }

    /**
     * 入队列
     * @param int $num
     * @author: LiuShiFu
     */
    public function addQueue(int $num) {
        if ($this->isFull()) {
            print("添加数据失败，队列已满".PHP_EOL);
            return;
        }
        $this->r++;
        $this->arr[$this->r] = $num;
    }

    /**
     * 出队列
     * @author: LiuShiFu
     */
    public function outQueue() {
        if ($this->isEmpty()) {
            print("获取数据失败，队列为空".PHP_EOL);
            return;
        }
        //队列的头部
        $this->f++;
        sprintf("出队列数据：%d",$this->arr[$this->f]);
    }
}


$queue = new ArrayQueue();
//$queue->isEmpty();
//$queue->isFull();
while(true) {
    echo "show：打印队列".PHP_EOL;
    echo "add：添加数据".PHP_EOL;
    echo "out：取数据".PHP_EOL;
    echo "exit：退出".PHP_EOL;
    $line = trim(fgets(STDIN));
    switch($line) {
        case "show":
            $queue->showQueue();
            break;
        case "add":
            echo "  请输入入队数据:";
            $line = trim(fgets(STDIN));
            $queue->addQueue($line);
            break;
        case "out":
            $queue->outQueue();
            break;
        case "exit":
            break 2;
        default:
            echo "输入错误".PHP_EOL;
    }
}