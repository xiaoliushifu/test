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

//=================================================== 队列的操作
//$queue = new ArrayQueue();
//$queue = new CircleArrayQueue();
//$queue->isEmpty();
//$queue->isFull();
//while (true) {
//    echo "show：打印队列" . PHP_EOL;
//    echo "add：添加数据" . PHP_EOL;
//    echo "out：取数据" . PHP_EOL;
//    echo "head：查看队首数据" . PHP_EOL;
//    echo "size：查看队列大小" . PHP_EOL;
//    echo "exit：退出" . PHP_EOL;
//    $line = trim(fgets(STDIN));
//    switch ($line) {
//        case "show":
//            $queue->showQueue();
//            break;
//        case "head":
//            $queue->headNode();
//            break;
//        case "size":
//            $queue->sizeQueue();
//            break;
//        case "add":
//            echo "  请输入入队数据:";
//            $line = trim(fgets(STDIN));
//            $queue->addQueue($line);
//            break;
//        case "out":
//            $queue->outQueue();
//            break;
//        case "exit":
//            break 2;
//        default:
//            echo "输入错误" . PHP_EOL;
//    }
//
//}

/* ====================循环队列，环形队列==============================*/
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

/* ========================单链表====================== */

/**
 * 组成链表的节点元素
 * 数据类型：定义一种数据结构及围绕这一数据结构可以进行的操作（增删改查）
 * Class HeroNode
 */
class HeroNode{
    /**
     * 业务字段，编号
     * @var
     */
    public $no;

    /**
     * 业务字段，名称
     * @var
     */
    public $name;

    /**
     * 必要的非业务字段：指针
     * 指向下一个节点的指针
     * 每个新的节点初始化时都是null
     * @var
     */
    public $next = null;

    public function __construct($no,$name)
    {
        $this->no = $no;
        $this->name = $name;
    }
}

/**
 * 定义一个单链表及允许的操作
 */
class SingleLinkedList {

    /**
     * 头节点（头指针）
     * 是一个固定的，没有实际业务意义的对象，专门指向链表的第一个节点
     * @var
     */
    public $head;

    /**
     * 单链表初始化时，必有头节点
     * SingleLinkedList constructor.
     */
    public function __construct()
    {
        $this->head = new HeroNode(0,"");
    }

    /**
     * 添加节点
     * 在列表的尾部，添加节点
     * 其实就是先找到队尾的节点，把新节点链入最后节点即可；
     *
     * 注意：
     *  头节点如果为空，则需要先创建头节点
     *  但是为了减少每次的判断，
     *  就默认头节点已经创建的情况下，才能调用该方法添加普通业务节点
     * @param HeroNode $node
     * @author: LiuShiFu
     */
    public function add(HeroNode $node) {
        //从头节点开始
        $temp = $this->head;
        while(true) {
            //当前节点$temp的下一个节点为空，则说明找到最后一个节点了
            if ($temp->next == null) {
                break;
            }
            $temp = $temp->next;
        }
        //php没有指针，但是每个节点都是用数据类型Object实现的，
        //php中对象是按照引用传递的（形参，实参传递的是引用的值，仍然值传递），并不是真正内存中的对象数据
        //在真正输出Object数据时，php发现是Object类型，底层C语言就知道存储的是指针，就会输出指针背后的真正的对象数据；
        //头节点head也是对象
        $temp->next = $node;
    }


    /**
     * 删除链表的某个节点
     * 当然是根据普通业务节点的业务，比如编号
     *
     * 注意：
     *  单链表，只能根据当前节点，找下一个节点，不能反向寻找，所以一般只删除末尾节点，容易些，
     * 删除中间节点，比较麻烦；
     * 所以关键点是找到，被删除节点的上一个节点，那么$temp->next就是要被删除的节点
     * @param HeroNode $node
     * @author: LiuShiFu
     */
    public function del(HeroNode $node)
    {
        if ($this->isEmpty()) {
            echo "链表为空：".PHP_EOL;
            return;
        }
        $flag = false;
        $temp = $this->head;
        echo "删除节点：".PHP_EOL;
        //遍历的条件是，下一个节点不为空
        while($temp->next != null) {
            //下一个节点的删除条件判断：编号
            if ($temp->next->no == $node->no) {
                $flag = true;
                //恰好是末尾的节点
                if ($temp->next->next == null) {
                    printf("\t hno=%d,hname=%s".PHP_EOL,$temp->next->no,$temp->next->name);
                    $temp->next = null;//删除，不要用unset
                    break;
                }
                //不是末尾节点，需要注意
                //这是兼容删除末尾节点的，其实
                $next2 = $temp->next->next;//被删除节点的下一个节点
                $temp->next = null;//删除节点；
                $temp->next = $next2;//后续节点继续拼接上
                break;

            }
            //没有找到，继续遍历
            $temp = $temp->next;
        }
        //没有找到那个节点，或者成功删除了那个节点
        if (!$flag) {
            echo "没有找到该节点：".sprintf("\t hno=%d,hname=%s".PHP_EOL,$node->no,$node->name);
        }
    }

    /**
     * 链表为空的判断
     * @return bool
     * @author: LiuShiFu
     */
    public function isEmpty() {
        //只有头节点的情况，链表为空
        return $this->head->next == null;
    }

    /**
     * 打印链表
     * 遍历节点输出节点数据
     * @author: LiuShiFu
     */
    public function show() {

        if ($this->isEmpty()) {
            echo "链表为空：".PHP_EOL;
            return;
        }

        $temp = $this->head;
        echo "打印链表：".PHP_EOL;
        while($temp != null) {
            printf("\t hno=%d,hname=%s".PHP_EOL,$temp->no,$temp->name);
            $temp = $temp->next;
        }
    }
}
//=================================================== 链表的操作
//$list = new SingleLinkedList();
//$h1 = new HeroNode(1,"松江");
//$h2 = new HeroNode(2,"林冲");
//$h3 = new HeroNode(3,"玉麒麟");
//
////添加节点
//$list->add($h1);
//$list->add($h2);
//$list->add($h3);
//
////打印下节点
//$list->show();
//
////删除中间节点
//$list->del($h2);
//$list->show();


/*==========================双向链表=================================================*/


/**
 * 双向链表的node
 * Class HeroNode2
 */
class HeroNode2{
    /**
     * 业务字段，编号
     * @var
     */
    public $no;

    /**
     * 业务字段，名称
     * @var
     */
    public $name;

    /**
     * 必要的非业务字段：指针
     * 指向下一个节点的指针
     * 每个新的节点初始化时都是null
     * @var
     */
    public $next = null;

    /**
     * 指向前一个节点
     * @var null
     */
    public $pre = null;

    public function __construct($no,$name)
    {
        $this->no = $no;
        $this->name = $name;
    }
}
/**
 * 单向链表的缺点：
 *  只能一个方向查找
 *  不能自己删除自己，需要辅助节点
 * 双向链表，每个节点增加一个pre指针，可以指向前面的节点
 * 初始化pre=null;
 */
class DoubleLinkedList {

    /**
     * 头节点（头指针）
     * 是一个固定的，没有实际业务意义的对象，专门指向链表的第一个节点
     * @var
     */
    public $head;

    /**
     * 链表初始化时，必有头节点
     * SingleLinkedList constructor.
     */
    public function __construct()
    {
        $this->head = new HeroNode2(0,"");
    }

    /**
     * 添加节点
     * 在列表的尾部，添加节点
     * 其实就是先找到队尾的节点，把新节点链入最后节点即可；
     *
     *
     * @param HeroNode2 $node
     * @author: LiuShiFu
     */
    public function add(HeroNode2 $node) {
        //从头节点开始
        $temp = $this->head;
        while(true) {
            //当前节点$temp的下一个节点为空，则说明找到最后一个节点了
            if ($temp->next == null) {
                break;
            }
            $temp = $temp->next;
        }
        //头节点head也是对象
        $temp->next = $node;
        //新节点的pre指向前一个节点
        $node->pre = $temp;
    }


    /**
     * 删除链表的某个节点
     * 当然是根据普通业务节点的业务，比如编号
     *
     * 注意：
     *  双链表可以完成自己删除自己（单链表时，需要找到被删元素的上一个元素才可以处理）
     *
     *  测试：
     *      删除第一个节点，删除最后一个节点，然后中间的一个节点
     * @param HeroNode2 $node
     * @author: LiuShiFu
     */
    public function del(HeroNode2 $node)
    {
        if ($this->isEmpty()) {
            echo "链表为空：".PHP_EOL;
            return;
        }
        $flag = false;
        $temp = $this->head;
        echo "删除节点：".PHP_EOL;
        //遍历的条件是，当前节点不为空
        while($temp != null) {
            //当前节点的删除条件判断：编号
            if ($temp->no == $node->no) {
                $flag = true;

                printf("\t hno=%d,hname=%s".PHP_EOL,$temp->no,$temp->name);
                //分三种情况吗？
                //第一个节点
                //中间一个节点
                //最后一个节点

                //先考虑中间节点的，然后再测试第一个节点，最后一个节点，看哪个不满足再修改，
                //尽量使之统一

                //当前节点的后面节点，交给上一个节点的next指针
                //删除最后一个节点，或者中间节点，下面这一行代码都兼容
                $temp->pre->next = $temp->next;

                //当后续还有节点时（非末尾节点，则后面肯定有）
                if ($temp->next != null) {
                    //后节点的pre指向上一个节点
                    $temp->next->pre = $temp->pre;
                }
                //删除节点的前后指针都置空（这一点很重要，否则发生曾经删除的节点，后续再添加时可能出现节点的情况）
                $temp->pre = null;
                $temp->next = null;
                break;
            }
            //没有找到，继续遍历
            $temp = $temp->next;
        }
        //没有找到那个节点，或者成功删除了那个节点
        if (!$flag) {
            echo "没有找到该节点：".sprintf("\t hno=%d,hname=%s".PHP_EOL,$node->no,$node->name);
        }
    }

    /**
     * 链表为空的判断
     * @return bool
     * @author: LiuShiFu
     */
    public function isEmpty() {
        //只有头节点的情况，链表为空
        return $this->head->next == null;
    }

    /**
     * 打印链表
     * 遍历节点输出节点数据
     * @author: LiuShiFu
     */
    public function show() {

        if ($this->isEmpty()) {
            echo "链表为空：".PHP_EOL;
            return;
        }

        $temp = $this->head;
        echo "打印链表：".PHP_EOL;
        while($temp != null) {
            printf("\t hno=%d,hname=%s".PHP_EOL,$temp->no,$temp->name);
            $temp = $temp->next;
        }
    }
}
//===========================双向链表的操作
$list = new DoubleLinkedList();
$h1 = new HeroNode2(1,"松江");
$h2 = new HeroNode2(2,"林冲");
$h3 = new HeroNode2(3,"玉麒麟");

//添加节点
$list->add($h1);
$list->add($h2);
$list->add($h3);

//打印下节点
$list->show();

//删除中间节点,最后节点，第一个节点，看是否报错
$list->del($h1);
$list->del($h3);
$list->del($h2);
$list->show();


$list->add($h3);
$list->show();