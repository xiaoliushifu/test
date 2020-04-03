<?php

// 数组模拟队列
//队尾入，对头出；

class arrayQueue
{

    public $arr = array(0, 0, 0);

    //初始化时，队列的头和尾都是指向一处
    public $f = -1;// 队列头
    public $r = -1;// 队列尾

    /**
     * 队列是否为空
     * @return bool
     * @author: LiuShiFu
     */
    public function isEmpty(): bool
    {
        return $this->f == $this->r;
    }

    /**
     * 队尾指针是否指向队尾位置
     * @return bool
     * @author: LiuShiFu
     */
    public function isFull(): bool
    {
        return $this->r == count($this->arr) - 1;
    }

    /**
     * 打印队列
     * @author: LiuShiFu
     */
    public function showQueue()
    {
        if ($this->isEmpty()) {
            print("队列为空" . PHP_EOL);
            return;
        }
        //从尾部指针开始，一直指向到头部指针
        //头部指针始终指向头部，不能遍历的移动指针，除非出队列；
        //尾部指针同理
        echo "队列数据是：" . PHP_EOL;
        for ($i = $this->r; $i > $this->f; $i--) {
            printf("arr[%d]=%d" . PHP_EOL, $i, $this->arr[$i]);
        }
    }

    /**
     * 入队列
     * @param int $num
     * @author: LiuShiFu
     */
    public function addQueue(int $num)
    {
        if ($this->isFull()) {
            print("添加数据失败，队列已满" . PHP_EOL);
            return;
        }
        $this->r++;
        $this->arr[$this->r] = $num;
    }

    /**
     * 出队列
     * @author: LiuShiFu
     */
    public function outQueue()
    {
        if ($this->isEmpty()) {
            print("获取数据失败，队列为空" . PHP_EOL);
            return;
        }
        //队列的头部
        $this->f++;
        sprintf("出队列数据：%d", $this->arr[$this->f]);
    }

    /**
     * 查看队首元素，但是不能改变指针的指向
     * @author: LiuShiFu
     */
    public function headNode()
    {
        if ($this->isEmpty()) {
            print("获取数据失败，队列为空" . PHP_EOL);
            return;
        }
        printf("队首元素：%d" . PHP_EOL, $this->arr[$this->f + 1]);
    }
}


//$queue = new ArrayQueue();
$queue = new CircleArrayQueue();
//$queue->isEmpty();
//$queue->isFull();
while (true) {
    echo "show：打印队列" . PHP_EOL;
    echo "add：添加数据" . PHP_EOL;
    echo "out：取数据" . PHP_EOL;
    echo "head：查看队首数据" . PHP_EOL;
    echo "size：查看队列大小" . PHP_EOL;
    echo "exit：退出" . PHP_EOL;
    $line = trim(fgets(STDIN));
    switch ($line) {
        case "show":
            $queue->showQueue();
            break;
        case "head":
            $queue->headNode();
            break;
        case "size":
            $queue->sizeQueue();
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
            echo "输入错误" . PHP_EOL;
    }

}

/**
 * 优化版
 * 数组队列基础上增加【取模】运算，使得队列逻辑上是一个环形队列
 *
 * 注意，虽然是四个容量的逻辑队列，但是实际上最多只能存储3个数据，
 * 因为队列判断满的条件，动脑筋理解下：
 * Class CircleArrayQueue
 */
class CircleArrayQueue
{

    //初始化大小为4的数组
    public $arr = array(0, 0, 0, 0);
    //初始化时，队列的头和尾都是指向一处
    public $f = 0;// 队列头
    public $r = 0;// 队列尾

    /**
     * 队列是否为空
     * 环形队列时，判断方式不变
     * @return bool
     * @author: LiuShiFu
     */
    public function isEmpty(): bool
    {
        return $this->f == $this->r;
    }

    /**
     *
     * 队列满的条件调整：
     *  动脑筋：
     *  尾部指针的下一个指针为头部指针，则队列满：
     *
     * r%length+1 == f
     * @return bool
     * @author: LiuShiFu
     */
    public function isFull(): bool
    {
        //队列容量会空出一个无法使用
        return (($this->r + 1) % count($this->arr)) == $this->f;
    }

    /**
     * 打印队列
     * @author: LiuShiFu
     */
    public function showQueue()
    {
        if ($this->isEmpty()) {
            print("队列为空" . PHP_EOL);
            return;
        }
        echo "队列数据是：" . PHP_EOL;
        //从头部指针开始，直到队列大小的步长（不含），[f,f+size);
        //注意，队列大小和队列容量的区别
        for ($i = $this->f; $i < $this->f + $this->sizeQueue(); $i++) {
            //注意取模
            printf("arr[%d]=%d" . PHP_EOL, $i % count($this->arr), $this->arr[$i % count($this->arr)]);
        }
    }

    /**
     * 入队列
     * @param int $num
     * @author: LiuShiFu
     */
    public function addQueue(int $num)
    {
        if ($this->isFull()) {
            print("添加数据失败，队列已满" . PHP_EOL);
            return;
        }
        //分析出：初始化指向队列头部0，先插入再移动；
        $this->arr[$this->r] = $num;
        //注意取模
        $this->r = ($this->r + 1) % count($this->arr);
    }

    /**
     * 出队列
     * @author: LiuShiFu
     */
    public function outQueue()
    {
        if ($this->isEmpty()) {
            print("获取数据失败，队列为空" . PHP_EOL);
            return;
        }
        //队列头部
        $node = $this->arr[$this->f];
        //指针右移
        $this->f = ($this->f + 1) % count($this->arr);
        sprintf("出队列数据：%d", $node);
    }

    /**
     * 查看队首元素，但是不能改变指针的指向
     * @author: LiuShiFu
     */
    public function headNode()
    {
        if ($this->isEmpty()) {
            print("获取数据失败，队列为空" . PHP_EOL);
            return;
        }
        printf("队首元素：%d" . PHP_EOL, $this->arr[$this->f]);
    }

    /**
     * 查看队列中实际数据有多少个，也就是队列大小，不是队列容量；
     * @author: LiuShiFu
     */
    public function sizeQueue()
    {
        if ($this->isEmpty()) {
            print("获取数据失败，队列为空" . PHP_EOL);
            return;
        }
        //队列大小，其实就是队尾到队首的差距即可
        //考虑是一个环形队列，所以再加上一个队列容量取模即可
        return ($this->r - $this->f + count($this->arr)) % count($this->arr);
    }
}
