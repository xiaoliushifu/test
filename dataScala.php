<?php

// 数组模拟队列
//队尾入，对头出；

//include("./funs.php");

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
class HeroNode
{
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

    public function __construct($no, $name)
    {
        $this->no = $no;
        $this->name = $name;
    }
}

/**
 * 定义一个单链表及允许的操作
 */
class SingleLinkedList
{

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
        $this->head = new HeroNode(0, "");
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
    public function add(HeroNode $node)
    {
        //从头节点开始
        $temp = $this->head;
        while (true) {
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
            echo "链表为空：" . PHP_EOL;
            return;
        }
        $flag = false;
        $temp = $this->head;
        echo "删除节点：" . PHP_EOL;
        //遍历的条件是，下一个节点不为空
        while ($temp->next != null) {
            //下一个节点的删除条件判断：编号
            if ($temp->next->no == $node->no) {
                $flag = true;
                //恰好是末尾的节点
                if ($temp->next->next == null) {
                    printf("\t hno=%d,hname=%s" . PHP_EOL, $temp->next->no, $temp->next->name);
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
            echo "没有找到该节点：" . sprintf("\t hno=%d,hname=%s" . PHP_EOL, $node->no, $node->name);
        }
    }

    /**
     * 链表为空的判断
     * @return bool
     * @author: LiuShiFu
     */
    public function isEmpty()
    {
        //只有头节点的情况，链表为空
        return $this->head->next == null;
    }

    /**
     * 打印链表
     * 遍历节点输出节点数据
     * @author: LiuShiFu
     */
    public function show()
    {
        if ($this->isEmpty()) {
            echo "链表为空：" . PHP_EOL;
            return;
        }

        $temp = $this->head;
        echo "打印链表：" . PHP_EOL;
        while ($temp != null) {
            printf("\t hno=%d,hname=%s" . PHP_EOL, $temp->no, $temp->name);
            $temp = $temp->next;
        }
    }

    /**
     * 假设单链表存储字符串，每个节点存储一个字符，那么
     * 如何判断这个字符串是回文字符串呢？
     * 回文就是对称，比如abccba;     abcba;
     * 区分奇数和偶数
     * 这里引用了快慢指针【这个用法才是学习的重点,用于快速找到中间结点】
     * 代码只是找到中间结点就结束了。
     * 如果真的找到回文，还需要再次对部分结点进行逆序操作才方便比较，
     * 具体思路就是慢指针每次向中间移动前，加一步逆序操作，就是把当前结点移动到第一个结点。
     * 也就是慢指针一边逆序一边移动；
     * 这里就不写代码了。
     * @author: LiuShiFu
     */
    public function isHuiWen()
    {
        //初始化，快慢指针都指向第一个节点
        $first = $this->head->next;
        $low = $fast = $first;
        while(1) {
            //快指针首先移动
            $i=1;
            while($i < 3) {
                if ($fast->next) {
                    $fast=$fast->next;
                    $i++;
                    continue;
                }
                break;
            }
            //根据$i判断，链表长度是奇数还是偶数
            //如果是1，快指针走到终点，且链表为奇数个结点，此时$low指向中间结点；
            //如果是2，快指针走到终点，且链表为偶数个结点，此时$low指向中间两个结点的上结点（比如4个结点，那么中间俩就是23，$low指向2结点）
            if($i==1) {
                //同样移动一次指针，指向后半部分第一个结点
                $low=$low->next;
                //由于逆序操作，此时链表的第一个结点其实是原来中间结点,比如 abcba中间的c,此时变成cbaba,下面比较环节应该从第二个结点开始比较
                $left = $this->head->next->next;
                break;
            } elseif($i==2) {
                $low=$low->next;
                //偶数长度的单链表，从第一个结点开始比较
                $left = $this->head->next;
                break;
            } else {
                //移动一位
//                $low=$low->next;
                //逆序操作start，其中自带了向后移动一位
                $this->moveFirst($low);
                //逆序操作end
            }
        }
        //此时$low指向后半部分的第一个结点，$fast指向最后的结点
        //$left指向左半边
        //两个指针同时移动比较即可
        while($low) {
            if ($left->name == $low->name) {
                $left=$left->next;
                $low=$low->next;
                continue;
            }
            //走到这里说明不是回文
            echo "no!".PHP_EOL;
            break;
        }
        echo "yes";
    }

    /**
     * 把指定结点的下一个结点，移动到第一个结点
     * 这就是逆序操作，是回文判断中的一个小环节
     * @param $low
     * @author: LiuShiFu
     */
    private function moveFirst($low) {
        $next = $low->next;
        //第三个结点及以后
        $low->next = $next->next;
        //指向第一个结点，借助头指针
        $next->next = $this->head->next;
        //头结点指向它
        $this->head->next = $next;
    }

    /**
     * 判断单链表中是否有环出现
     * 这个有环和是环要理解有这么几种情况
     *  1： 1-》2-》3-》4-》2-》xxxxxx   1不在环内，从234开始这三个结点组成了环；
     *  2： 1-》2-》3-》4-》1-》xxxxxx    1也在环内，1234整个链表组成了环
     *  该方法就是判断上述两种情况的
     *  思路1：
     *      快慢两个指针，开始都指向第一个结点；
     *      快指针一次走两步，慢指针一次走一步，这就算走了一回
     *      当快指针赶上了慢指针（除了起点之外），说明一定有环（走得快的还能【追上】慢指针，说明快指针开始转圈了）
     *      如果快指针走到最后（next=null)，没有追上慢指针，则说明无环；
     *  思路2：
     *      这个也简单，把遍历过的结点存到一个数据结构中，比如数组map标记为1，默认没出现就是0；
     *      从头结点开始往后遍，判断map[p]的值；如果是1说明出现过，有环；如果是0，则标记为1；
     *      直到遍历到链表末尾都没有出现1，则说明没有环
     *      难点：php如何比较两个对象相等呢？？c语言可以通过指针地址判断，php该咋整呢？
     * @author: LiuShiFu
     */
    public function hasLoop() {
        //头结点必须有
        $tmp = $this->head;

        //第一个结点
        $fast = $low = $first = $tmp->next;
        //第一个结点不存在，肯定不是环
        if ($first == null) {
            return false;
        }
        while(1) {
            //慢指针走一步
            $low = $low->next;
            if ($low == null) {
                return false;
            }
            //快指针走两步
                //1快指针先走一步
            $fast = $fast->next;
            //只有一个结点
            if ($fast == null) {
                return false;
            }
                //2快指针再走一步
            $fast = $fast->next;

            //快慢指针各自走完自己的步数后称之为走一回，走一回后判断是否相等（快指针走向了慢指针）
            if ($fast == $low) {
                return true;
            }
        }
    }

    /**
     * 头插法逆序一个单链表
     *
     * 1头插法，
     *      $node = new Node();
     *
     *      $node->next = head->next;
     *      head->next = $node;
     * 2删除结点
     *      $temp = p->next;
     *      $p->next = $temp->next;
     *      $temp=null
     * 所以，所谓头插法逆序一个单链表其实就是先删除一个结点，然后把这个结点头插到链表前端；就是【删除结点】+【头插法】结合
     * @author: LiuShiFu
     */
    public function reverseList()
    {
        //第一个结点，不是头结点！
        $p = $this->head->next;

        if (!$p) {
            return ;
        }

        while($p->next != null) {
            //下面两行代码是删除$p的下一个结点，因为单链表要想删除某个结点，必须知道它的上一个结点，必须！
                //1待删除结点存入临时变量$temp
            $temp = $p->next;

                //2为了删除后不能断开链表，还得把$temp的前后两端结点接起来。

            $p->next = $temp->next;

            //下面两行代码是把$temp结点，插到链表头部（第一个结点）
            //因为要想插到链表头部，必须得有一个指针指向第一个结点，无论是头结点还是真正的第一个结点。
            //否则无法头插！
            //目前所有的链表都是带有头结点的，这是有原因的

                //把第一个结点接到$temp的后面，$temp就成为了第一个结点的前驱，那么$temp就是第一个结点了
            $temp->next = $this->head->next;
                //在把$temp交给头指针管理
            $this->head->next = $temp;

            //上述完成后，$p自动向后移动了一位：因为它把$p的下一个结点转移到了左边而已。
        }
    }

    /**
     * 逆序一个链表的方法，
     * 需要用到三个指针，觉得比较简单就搬过来了在leetcode上看的
     * 整体思路是：不移动结点，而是移动指向结点的指针方向：把指向后继的指针指向前驱。
     *                  核心代码就这一行：
     *                              q->next = p
     * 无需借助头结点
     * @author: LiuShiFu
     */
    public function reverseV2()
    {
        //$p,$q,$r  分别指向第一，第二，第三个结点的指针（兼容头结点的情况）
        $p=$this->head;
        $q=$p->next;
//        $r=$q->next;

        //第一个结点断开
        $p->next = null;

        //通过书本上演算，最终q为空，则走到了头
        while($q) {
            //q后一个结点存起来
            $r = $q->next;
            //$q指针方向调整，指向左边刚刚断开的$p
            $q->next = $p;

            //p,q指针整体后移一个结点
            $p = $q;
            $q = $r;
        }
        //此时p指向最终结点，且指针已经反向完成
        //如果头结点不参与的话，那就是$this->head->next=$p;
        $this->head = $p;
    }

    /**
     * 快慢指针
     * 兼容1个结点，2个结点
     * 3个结点
     * 奇数和偶数的情况要考虑
     * @author: LiuShiFu
     */
    public function fastAndSlow()
    {
        $p = $this->head->next;
        if (!$p) {
            return;
        }
        //初始化，快慢指针都指向第一个结点（不是头结点）
        $fast = $slow = $p;


        while ($fast->next != null) {
            //fast后两个结点都有，就可以大张旗鼓向后移动
            if ($fast->next->next != null) {
                $slow = $slow->next;
                $fast = $fast->next->next;
            } else {
                //当快指针不够移两步时，只有偶数才符合这里的情况，奇数个根本不会走到这
                //偶数个，快指针还得移动一次，慢指针此时指向中间两结点的靠左结点
//                $slow = $slow;
                $fast = $fast->next;
            }
        }
        echo "slow:".$slow->no.PHP_EOL;
        echo "fast:".$fast->no.PHP_EOL;
    }

    /**
     * 快慢指针
     * 第二种方法，感觉比上一个方法简单点，没有那么多if else
     * @author: LiuShiFu
     */
    public function fastAndSlowV2()
    {
        //从第一个结点遍历,如何看待第一个结点：
        //如果是头指针，直接head;
        //否则就是head->next;
        $p = $this->head->next;
        if (!$p) {
            return;
        }
        //初始化，快慢指针都指向第一个结点
        $fast = $slow = $p;

        while ($fast->next && $fast->next->next) {
                $slow = $slow->next;
                $fast = $fast->next->next;
        }
        //当偶数个结点时，快指针还需要多走一步才到最后结点
        if($fast->next) {
            $fast = $fast->next;
        }
        echo "slow:".$slow->no.PHP_EOL;
        echo "fast:".$fast->no.PHP_EOL;
    }

    /**
     * 用快慢指针做回文判断
     * 额外使用一个栈数据结构，并不是在原来链表基础上操作。
     * 练习php的spl库，SPLStack
     * 快慢指针就为了找到链表的中点而已，其后快指针就无用了
     * @author: LiuShiFu
     */
    public function isHuiWenV2()
    {
        $p = $this->head->next;
        if (!$p) {
            return;
        }
        //初始化栈结构
        $stack = new SplStack();
        //初始化，快慢指针都指向第一个结点
        $fast = $slow = $p;
        //第一个结点入栈
        $stack->push($slow->name);

        while ($fast->next && $fast->next->next) {
            $slow = $slow->next;
            //慢指针走过结点的值都入栈
            $stack->push($slow->name);
            $fast = $fast->next->next;
        }
        //如果是奇数个结点时，此时慢指针指向绝对中间结点，该值无需入栈
        if(!$fast->next) {
            $stack->pop();
        }
        //走链表的后半段，并和栈中数据比对即可,快指针已经没有用了
        while($slow->next) {
            $slow = $slow->next;
            $top = $stack->pop();
            if ($top != $slow->name) {
                exit("no\n");
            }
        }
        exit("yes\n");
    }

    /**
     * 调整单链表的结点顺序
     * 用到了三个小模块
     *  1快慢指针找中点
     *  2针对一半部分的链表逆序操作（无需借用头指针的方法，切换指针方向）
     *  3两个半链表合并（两种方法）
     * @author: LiuShiFu
     */
    // 1-2-3-4-5===> 1-5-2-4-3
    public function recordList()
    {
        //少于两个指针的，就算了
        if (!$this->head || !$this->head->next || !$this->head->next->next) {
            return;
        }
        //1快慢指针找点
        $slow = $fast = $this->head->next;

        while($fast->next && $fast->next->next) {
            $slow=$slow->next;
            $fast=$fast->next->next;
        }
        //slow确定到了中点，无论链表是奇数还是偶数个

        //下面对链表的右半部分进行逆序
        $right = $slow->next;
        $slow->next = null;

        $p = $right;
        $q = $p->next;
        //右半部分第一个结点断开
        $p->next=null;
        while($q) {
            $r = $q->next;
            $q->next = $p;

            $p = $q;
            $q = $r;
        }

        //后半部分链表已经倒序，且p为第一个结点了

        //已经拆分为两个部分了，接下来就是组合，组合的方式有很多

        //方法1：依次从两个链表各摘取一个头结点，尾插法到一个新的链表$last中
        /**
            //初始化一个新链表
            $last = new SingleLinkedList();
            //fast是临时指针，新链表的头指针
            $fast = $last->head;

        while($this->head->next && $p) {

            //左边摘一个
            $fromLeft = $this->head->next;
            $this->head->next = $fromLeft->next;

            //放到新链表中
            $fast->next = $fromLeft;
            $fast = $fast->next;


            //右边摘一个
            $fromRight = $p;
            $p = $p->next;

            //放到新链表中
            $fast->next = $fromRight;
            $fast = $fast->next;
        }
        //左半部分有可能多一个
        if ($this->head->next) {
            $fast->next = $this->head->next;
        }

        //最终放到原链表中
        $this->head->next = $last->head->next;
         * */
//        return $last;


        //方法2：把其中一个链表合并到另一个链表中
        $left = $this->head->next;
        while($left && $p) {
            //右边摘取一个
            $rightOne = $p;
            $p = $p->next;

            //放到左边
            $rightOne->next = $left->next;
            $left->next = $rightOne;
            //$left右移
            $left = $rightOne->next;
        }
    }
}

//=================================================== 链表的操作
$list = new SingleLinkedList();
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

//测试回文字符串
//$testArr=['a','b','c','c','b','a'];
//$testArr=['a','b','c','b','a'];
//$testArr=['a','a'];
//foreach($testArr as $k=>$v) {
//    $h1 = new HeroNode($k,$v);
//    $list->add($h1);
//}
//$list->isHuiWen();
//$list->isHuiWenV2();


//是否有环
//$h1 = new HeroNode(1,1);
//$h2 = new HeroNode(2,1);
//$h3 = new HeroNode(3,1);
//$h4 = new HeroNode(4,1);
//$h5 = new HeroNode(5,1);
//$h6 = new HeroNode(6,1);
//$list->add($h1);
//$list->add($h2);
//$list->add($h3);
//$list->add($h4);
//$list->add($h5);
//$list->add($h6);
//$list->add($h6);

//$h4->next=$h2;
//$h4->next=$h3;
//$ret = $list->hasLoop();
//if ($ret) {
//    echo "有环";
//    return;
//}
//echo "无环";

//先打印
//$list->show();

//测试头插法逆序
//$list->reverseList();
//逆序方法2
//$list->reverseV2();
//echo "逆序后";

//综合操作（快慢指针，逆序，合并）
//$list->recordList();
//再打印
//$list->show();

#快慢指针
//$list->fastAndSlow();
//$list->fastAndSlowV2();
//再打印
//$list->show();
/*==========================双向链表=================================================*/


/**
 * 双向链表的node
 * Class HeroNode2
 */
class HeroNode2
{
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

    public function __construct($no, $name)
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
class DoubleLinkedList
{

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
        $this->head = new HeroNode2(0, "");
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
    public function add(HeroNode2 $node)
    {
        //从头节点开始
        $temp = $this->head;
        while (true) {
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
            echo "链表为空：" . PHP_EOL;
            return;
        }
        $flag = false;
        $temp = $this->head;
        echo "删除节点：" . PHP_EOL;
        //遍历的条件是，当前节点不为空
        while ($temp != null) {
            //当前节点的删除条件判断：编号
            if ($temp->no == $node->no) {
                $flag = true;

                printf("\t hno=%d,hname=%s" . PHP_EOL, $temp->no, $temp->name);
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
            echo "没有找到该节点：" . sprintf("\t hno=%d,hname=%s" . PHP_EOL, $node->no, $node->name);
        }
    }

    /**
     * 链表为空的判断
     * @return bool
     * @author: LiuShiFu
     */
    public function isEmpty()
    {
        //只有头节点的情况，链表为空
        return $this->head->next == null;
    }

    /**
     * 打印链表
     * 遍历节点输出节点数据
     * @author: LiuShiFu
     */
    public function show()
    {

        if ($this->isEmpty()) {
            echo "链表为空：" . PHP_EOL;
            return;
        }

        $temp = $this->head;
        echo "打印链表：" . PHP_EOL;
        while ($temp != null) {
            printf("\t hno=%d,hname=%s" . PHP_EOL, $temp->no, $temp->name);
            $temp = $temp->next;
        }
    }
}

//===========================双向链表的操作
//$list = new DoubleLinkedList();
//$h1 = new HeroNode2(1,"松江");
//$h2 = new HeroNode2(2,"林冲");
//$h3 = new HeroNode2(3,"玉麒麟");
//
////添加节点
//$list->add($h1);
//$list->add($h2);
//$list->add($h3);
//
////打印下节点
//$list->show();
//
////删除中间节点,最后节点，第一个节点，看是否报错
//$list->del($h1);
//$list->del($h3);
//$list->del($h2);
//$list->show();
//
//
//$list->add($h3);
//$list->show();


/*==============================走迷宫的路径==============================*/

/**
 *
 * 其实是一个递归算法
 *
 * 用二维数组实现
 *  约定：  0：可以走但是尚未走的路；    1：墙，挡板等不通的路；     2：走过的路；     3：已走过，但是不通的路；
 * 给出走出的路径
 * 给出最短的路径（多种策略尝试后）
 * 1给个起始位置
 * 2    一种策略：
 *          1：下-》右-》上-》左
 *      第二种策略：
 *          2：右-》下-》左-》上
 */
class MapWay
{
    //地图
    public $map = [];

    public $xLen = 0;
    public $yLen = 0;

    public function __construct(int $x, int $y)
    {
        $this->xLen = $x;
        $this->yLen = $y;
    }

    /**
     * 绘制地图（迷宫）
     * 正方形，四面都是墙，其他地方有挡板
     * @author: LiuShiFu
     */
    public function drawMap()
    {
        //初始化全是0
        $this->map = array_fill(0, 7, array_fill(0, 7, 0));
        //上下都是1
        for ($i = 0; $i < $this->xLen; $i++) {
            $this->map[0][$i] = 1;
            $this->map[$this->yLen - 1][$i] = 1;
        }
        //左右都是1
        for ($i = 0; $i < $this->xLen; $i++) {
            $this->map[$i][0] = 1;
            $this->map[$i][$this->yLen - 1] = 1;
        }

        //挡板
        $this->map[3][1] = 1;
        $this->map[3][2] = 1;

        //增加挡板难度，测试【3不通路】
        $this->map[1][2] = 1;
        $this->map[2][2] = 1;
    }

    /**
     * 打印地图
     * @author: LiuShiFu
     */
    public function showMap()
    {
        foreach ($this->map as $xItem) {
            foreach ($xItem as $yItem) {
                printf("%d ", $yItem);
            }
            print(PHP_EOL);
        }
    }

    /**
     * 开始走迷宫,策略1
     *
     * 不断递归
     *
     * 判断是否走到出口了，比如（6，6）是出口；
     * @param int $x 起始位置
     * @param int $y 起始位置
     * @return bool
     * @author: LiuShiFu
     */
    public function setWay(int $x, int $y): bool
    {
        //越界，或者墙，走不通
        //修改了对【走过的路】的return false之后，其实这段代码也可以注释掉了：因为不可能越界了
//        if ($x >= $this->xLen || $y >= $this->yLen || $this->map[$x][$y] == 1) {
//            //print("越界了");
//            return false;
//        }


        //这一行代码，影响了回溯（3），在策略尝试【上】的时候，有bug,不不会出现3
//        if ($this->map[$x][$y] == 2) {
//            return true;
//        }

        //走到了迷宫的出口,则认为成功，此时退出即可（否则还会走直到走不通为止）
        if ($this->map[5][5] == 2) {
            return true;
        } else {
            //没走过的路,首先假设可以走，然后开始策略（尝试下一步是否可以走通）
            if ($this->map[$x][$y] == 0) {
                $this->map[$x][$y] = 2;

                //开始策略
                if ($this->setWay($x + 1, $y)) {  //继续往下走-------》下
                    return true;
                } elseif ($this->setWay($x, $y + 1)) {  //继续往右走-------》右
                    return true;
                } elseif ($this->setWay($x - 1, $y)) {  //继续往上走-------》上
                    return true;
                } elseif ($this->setWay($x, $y - 1)) {  //继续往左走-------》左
                    return true;
                } else {
                    //都没有走通，则假设失败，把它重新标记为死路，这就是回溯，重点。好好想想这种思路！
                    $this->map[$x][$y] = 3;
                    return false;
                }
            } else {
                //不是0的情况，那么就只有：1墙 2走过的路（或者假设的路） 3不通的路 三种情况
                //怕越界，走过的路，统统在这里可以统一返回false处理，思路新理解
                return false;
            }
        }
    }
}


//===========================地图的操作
//$miGong = new MapWay(7,7);
//$miGong->drawMap();
//$miGong->showMap();
//
////起始位置，开始走
//$miGong->setWay(1, 1);
//echo PHP_EOL;
////最后显示路径，重点关注为2的
//$miGong->showMap();


/*========================查找===========================================================*/

/**
 * 二分查找，前提是一个有序列表
 * false 未找到，也有返回-1；找到则返回索引
 * @param $arr
 * @param $left
 * @param $right
 * @param $findVal
 * @return bool
 * @author: LiuShiFu
 */
function BinarySearch($arr, $left, $right, $findVal)
{
    if ($left > $right) {
        return false;
    }
    //取中间值
    $midIndex = intdiv($left + $right, 2);
    $midVal = $arr[$midIndex];

    //比较
    if ($midVal < $findVal) {
        $midIndex = BinarySearch($arr, $midIndex + 1, $right, $findVal);
    } elseif ($midVal > $findVal) {
        $midIndex = BinarySearch($arr, $left, $midIndex - 1, $findVal);
    }
    return $midIndex;
}

/**
 * 二分查找，改进版
 * 如果有序列表出现多个相同的值，则如果恰好找这样的值时把所有的下标都返回
 * 意思就是返回数组了
 * 未找到返回 []；找到则返回装有索引的数组
 * @param $arr
 * @param $left
 * @param $right
 * @param $findVal
 * @return bool
 * @author: LiuShiFu
 */
function BinarySearch2($arr, $left, $right, $findVal)
{
    if ($left > $right) {
        return [];
    }
    //取中间值
    $midIndex = intdiv($left + $right, 2);
    $midVal = $arr[$midIndex];

    //比较
    if ($midVal < $findVal) {
        return BinarySearch2($arr, $midIndex + 1, $right, $findVal);
    } elseif ($midVal > $findVal) {
        return BinarySearch2($arr, $left, $midIndex - 1, $findVal);
    }
    //走到这说明找到了，因为是有序列表，所以相同的值一定是挨着的
    //分别往左往右直到下标结束或者不再是$findVal就说明结束了
    //先把找到的$midIndex加进去
    $indexArr = [$midIndex];
    //右边走
    $tmpIndex = ($midIndex + 1);
    while (true) {
        if ($tmpIndex == count($arr) || $arr[$tmpIndex] != $findVal) {
            break;
        }
        $indexArr[] = $tmpIndex;
        $tmpIndex++;
    }
    //左边走
    $tmpIndex = ($midIndex - 1);
    while (true) {
        if ($tmpIndex == -1 || $arr[$tmpIndex] != $findVal) {
            break;
        }
        $indexArr[] = $tmpIndex;
        $tmpIndex--;
    }
    return $indexArr;
}

//=========================================二分查找测试
//$arr = range(4,8);
//$arr = [3, 3,4, 5, 5, 5, 6, 9, 10,10];
//printArr($arr);
//echo PHP_EOL;
//$n = BinarySearch2($arr, 0, count($arr)-1, 3);
//if (!$n) {
//    echo "no found" . PHP_EOL;
//} else {
//    printArr($n);
//}

/*=============================二叉树===============================*/

/**
 * 二叉树的节点
 *
 * 每个节点的遍历
 * Class TreeNode
 */
class TreeNode
{
    public $no;
    public $name;
    //左右节点
    public $left = null;
    public $right = null;

    public function __construct(int $no, string $name)
    {
        $this->no = $no;
        $this->name = $name;
    }

    /**
     * 前序遍历,递归
     * @author: LiuShiFu
     */
    public function preOrder()
    {
        //先输出中间节点
        printf("no=%s,name=%s\t".PHP_EOL, $this->no, $this->name);

        //再去左子树递归
        if ($this->left) {
            $this->left->preOrder();
        }

        //再去右子树递归
        if ($this->right) {
            $this->right->preOrder();
        }

    }

    /**
     * 中序遍历
     * @author: LiuShiFu
     */
    public function infixOrder()
    {
        //左子树递归
        if ($this->left) {
            $this->left->infixOrder();
        }

        //输出中间节点
        printf("no=%s,name=%s\t".PHP_EOL, $this->no, $this->name);

        //再去右子树递归
        if ($this->right) {
            $this->right->infixOrder();
        }
    }

    /**
     * 后序遍历
     * @author: LiuShiFu
     */
    public function postOrder()
    {
        //左子树递归
        if ($this->left) {
            $this->left->postOrder();
        }
        //再去右子树递归
        if ($this->right) {
            $this->right->postOrder();
        }
        //输出中间节点
        printf("no=%s,name=%s\t".PHP_EOL, $this->no, $this->name);
    }

    /**
     * 层序遍历，类似于广度优先搜索
     * 需要借用队列数据结构，再加两层循环
     * @author: LiuShiFu
     */
    public function levelOrder() {
        //需要一个队列
        $que = new SplQueue();
        //当前节点入队列
        $que->enqueue($this);

        while(!$que->isEmpty()) {
            $s = 0;
            //当前层的数量
            //这个队列长度是动态的，后续会往队列中增加属于下一层的节点，这里就是提前获取到当前层的数量，使得接下来的队列遍历可以控制只访问到当前层的大小）
            //不会越界访问到属于下一层的节点
            $len = $que->count();
            $stack2 = new SplQueue();
            //这个循环注意是有$len上限的，这就使得本次while只循环指定数量的节点，这些节点都属于同层，一次遍历不会跨层；
            //而在某层遍历的同时，又会往队列中增加不属于当前层的节点（确切的说就是下一层节点），从队列角度来看，某时刻它存储
            //着属于不同层的节点（也就是跨层的节点）
            while($s++<$len) {
                //出队列，就是遍历它
                $t = $que->dequeue();
                //$stack2->push($t);
                printf("no=%s,name=%s\t".PHP_EOL, $t->no, $t->name);
                //当前节点的孩子节点（就是它的下一层节点）入队列，这些孩子节点之间属于兄弟关系，按照从左往右顺序放入到队列中
                //所以要强调的注意点就是：本次while循环中往队列que中添加的节点都是属于同层的节点
                //每个兄弟关系的节点的孩子节点都加入队列，就构成了整个下一层的节点。
                //在遍历第1层的节点时，会同时把第二层的节点加入队列中，方便第一层遍历完毕时能够继续遍历第二层，完成衔接
                //遍历第n层的同时，会准备第n+1层的节点加入队列（且只添加n+1层的节点），使得后续循环可以按照层级铺展开来，
                //当前层遍历完毕时，队列中就只剩下n+1层的节点，重新读取队列长度，照着这个数量遍历即可，重复该步骤

                if($t->left) $que->enqueue($t->left);
                if($t->right) $que->enqueue($t->right);
            }
        }

    }

    /**
     * 层序遍历2
     * 与上面相比，同样使用队列，但是使用一层循环
     * 简洁
     * 从这里可以看出，队列的先进先出特性，我们的程序只要按照层级的顺序往队列中添加节点就行，
     * 把访问节点和往队列添加节点交叉执行（一段代码逻辑是【访问一个节点】和【把当前节点的字节点入队列】交叉执行的）
     * 而不是纯【访问所有节点】之后，再纯【添加所有节点】
     * @author: LiuShiFu
     */
    public function levelOrder2() {
        //需要一个队列
        $que = new SplQueue();
        //当前节点入队列
        $que->enqueue($this);

        while(!$que->isEmpty()) {
            $t = $que->dequeue();
            printf("no=%s,name=%s\t".PHP_EOL, $t->no, $t->name);

            if($t->left) $que->enqueue($t->left);
            if($t->right) $que->enqueue($t->right);
        }

    }

    /**
     * 前序查找
     * 先比较中间节点，再比较左子树，比较右子树
     * @param int $no
     * @return TreeNode
     * @author: LiuShiFu
     */
    public function preOrderSearch(int $no)
    {
        //中间节点是否是目标号码
        if ($this->no == $no) {
            return $this;
        }
        //左子树
        if ($this->left) {
            $return = $this->left->preOrderSearch($no);
            //左边没有，就再去右子树找
            if(!$return) {
                if ($this->right) {
                    return $this->right->preOrderSearch($no);
                }
            }
            return $return;
        }
    }



    /**
     * 二叉排序树方法
     * 根据参数节点的值查找节点
     * 影响二叉排序树的删除
     * 比当前节点值小的，就往左递归；
     * 比当前节点值大的，就往右递归；
     * @param int $no
     * @return TreeNode
     * @author: LiuShiFu
     */
    public function searchSortValue(int $no)
    {
        if ( $no == $this->no) {
            return $this;
            //比当前值小，
        } elseif ($no < $this->no) {
            if ($this->left) {
                return $this->left->searchSortValue($no);
            }
        } elseif ($no > $this->no) {
            //比当前值大
            if ($this->right) {
                return $this->right->searchSortValue($no);
            }
        }
    }

    /**
     * 二叉排序树方法
     * 根据参数节点的值，找到参数节点的父节点
     * 这个有点难度
     *  由于链表的单向性，还是老办法，需要从父节点的角度来寻找：
     *      如果left或者right匹配成功，就返回this；
     *      否则,比this的值小，则应该去左子树递归；比当前this值大，则去右子树递归；
     *      特别，如果恰好this就是no的节点，那么这个this应该只能是根节点了。此时没有父节点
     * @param int $no
     * @return TreeNode
     * @author: LiuShiFu
     */
    public function searchParent(int $no): ?TreeNode
    {
        if (($this->left && $this->left->no == $no)  || ($this->right && $this->right->no == $no)) {
            return $this;
        }

        //左边递归
        if ($this->left && $no < $this->no ) {
            return $this->left->searchParent($no);
        }
        //右边递归
        if ($this->right && $no > $this->no ) {
            return $this->right->searchParent($no);
        }
        return null;
    }

    /**
     * 节点的删除
     *  约定：
     *      当是叶子节点时，直接删除该节点；
     *      当非叶子节点时，直接删除该树；
     *      不支持根节点删除
     *  扩展约定：
     *      如果删除非叶子节点A时：
     *          1 假如A有一个子节点B，那么子节点B替代它；
     *          2 假如A有两个子节点：左节点B和右节点C，那么左节点B替代它；(C呢？不管了吗？还有左节点B如果也有左右两个子节点呢？如何重组？）
     * @param int $no
     * @author: LiuShiFu
     */
    public function  delNode(int $no)
    {
        //当前节点的左子节点
        if ($this->left) {
            if ($this->left->no == $no) {
                $this->left = null;
                return;
            }
        }

        //右子节点
        if ($this->right) {
            if ($this->right->no == $no) {
                $this->right = null;
                return;
            }
        }

        //既然上述左右孩子节点都不是，那么开始左递归
        if ($this->left) {
            $this->left->delNode($no);
        }
        //右递归
        if ($this->right) {
            $this->right->delNode($no);
        }
    }

    /**
     * 创建二叉排序树（添加节点）
     * 根据节点的值大小，决定放置的位置：
     *  比当前值小的，往左递归；
     *  比当前值大的，往右递归
     * @param TreeNode $node
     * @author: LiuShiFu
     */
    public function addSearchNode(TreeNode $node)
    {
        if ($node) {
            //插入节点的值，小于当前节点的值
           if ($node->no < $this->no) {
               //当前节点无左子节点
               if (!$this->left) {
                   $this->left = $node;
               } else {
                   //当前节点有左子节点，递归
                   $this->left->addSearchNode($node);
               }
           } else {
               //插入节点的值，大于等于当前节点的值
               //当前节点无右子节点
               if (!$this->right) {
                   $this->right = $node;
               } else {
                   //当前节点有右子节点，递归
                   $this->right->addSearchNode($node);
               }
           }
        }
    }
}

/**
 * 二叉树
 * 其实就是一个关键节点：跟节点
 * 应有的方法：
 *      构造二叉树
 *      删除二叉树
 *      增加节点
 *      删除节点
 *      查找节点
 *      查找父节点
 *      。。。。。。
 *
 * Class BinaryTree
 */
class BinaryTree
{
    //跟节点
    public $root = null;

    /**
     * 主动构造一个二叉树
     * 用于测试
     * BinaryTree constructor.
     */
    public function createSimpleTree()
    {
        //跟节点
        $this->root = $tmp = new TreeNode(1, "宋江");

        //其他节点
        $node2 = new TreeNode(2, "无用");
        $node3 = new TreeNode(3, "卢俊义");
        $node4 = new TreeNode(4, "林冲");
        $node5 = new TreeNode(5, "关胜");
        $node6 = new TreeNode(6, "武松");
        $node7 = new TreeNode(7, "鲁达");

        //节点之间建立关系
        $tmp->left = $node2;
        $tmp->right = $node3;

        $node3->left = $node5;
        $node3->right = $node4;

//        $node2->left=$node6;
//        $node2->right=$node7;
    }


    /**
     * 二叉树的前序遍历
     * @author: LiuShiFu
     */
    public function preOrder()
    {
        if ($this->root) {
            $this->root->preOrder();
        } else {
            printf("空二叉树，不能遍历");
        }
    }

    /**
     * 二叉树的中序遍历
     * @author: LiuShiFu
     */
    public function infixOrder()
    {
        if ($this->root) {
            $this->root->infixOrder();
        } else {
            printf("空二叉树，不能遍历");
        }
    }

    /**
     * 二叉树的后序遍历
     * @author: LiuShiFu
     */
    public function postOrder()
    {
        if ($this->root) {
            $this->root->postOrder();
        } else {
            printf("空二叉树，不能遍历");
        }
    }

    /**
     * 二叉树的层序遍历
     * @author: LiuShiFu
     */
    public function levelOrder()
    {
        if ($this->root) {
            $this->root->levelOrder2();
        } else {
            printf("空二叉树，不能遍历");
        }
    }

    /**
     * 二叉树的前序查找
     * @param int $no
     * @author: LiuShiFu
     */
    public function preOrderSearch(int $no)
    {
        if ($this->root) {
            $returnNode = $this->root->preOrderSearch($no);
            if ($returnNode) {
                printf("找到了，n=%d,name=%s",$returnNode->no,$returnNode->name);
                exit;
            }
            printf("没有找到～".PHP_EOL);
        } else
            {
            printf("空二叉树，不能查找");
        }
    }

    /**
     * 节点删除
     *
     *  根节点的删除要特别处理：在这里完成而不是在节点对象中完成
     * @param int $no
     * @author: LiuShiFu
     */
    public function delNode(int $no)
    {
        if ($this->root) {
            //跟节点的删除
            if ($this->root->no == $no) {
                $this->root = null;
                return;
            }
            $this->root->delNode($no);
        } else {
            printf("空二叉树，不能删除");
        }
    }

    /**
     * 二叉排序树的添加节点方法
     * @param TreeNode $node
     * @author: LiuShiFu
     */
    public function addSearchNode(TreeNode $node)
    {
        if (!$this->root) {
           $this->root = $node;
           return;
        }else {
            //调用节点的方法处理
            $this->root->addSearchNode($node);
        }
    }

    /**
     * 二叉排序树的方法
     * 根据参数值的查找，调用节点的方法即可
     * @param $no
     * @return TreeNode
     * @author: LiuShiFu
     */
    public function searchSortNode($no):TreeNode
    {
        if (!$this->root) {
            return null;
        } else {
            //调用节点的方法处理
            return $this->root->searchSortValue($no);
        }
    }

    /**
     * 二叉排序树的方法
     * 根据参数值查找父节点，调用节点的方法即可
     * @param $no
     * @return TreeNode
     * @author: LiuShiFu
     */
    public function searchParentNode($no): ?TreeNode
    {
        if (!$this->root) {
            return null;
        } else {
            //调用节点的方法处理
            return $this->root->searchParent($no);
        }
    }

    /**
     * 二叉排序树方法
     * 用于删除包含两个子节点的节点时，需要的内部方法
     * 给定参数节点，删除它的最小值节点，并返回最小值
     * @param TreeNode $node
     * @return int
     * @author: LiuShiFu
     */
    private function delRightTreeMin(TreeNode $node): int
    {
        //左子树是小值，循环到最左边的叶子节点即可
        while ($node->left) {
            $node = $node->left;
        }
        $minValue = $node->no;
        //删除这个节点
        $this->delNode($minValue);
        return $minValue;
    }

    /**
     * 二叉排序树的方法
     *      删除节点，由简到难
     *          1删除叶子节点
     *          2非叶子节点，再分两种情况
     *                  1 只有一个子节点的情况
     *                  2 有两个子节点的情况
     * @param $no
     * @return bool
     * @author: LiuShiFu
     */
    public function delSortNode($no): ?bool
    {
        //空树
        if (!$this->root) {
            return false;
        }
        //先找到该节点
        $targetNode = $this->searchSortNode($no);
        //没有该节点,不能删除
        if (!$targetNode) {
            return false;
        }
        //找到父节点(页子节点，或者只有一个子节点的情况需要使用）
        $parentNode = $this->searchParentNode($no);

        //1 叶子节点的情况
        if ($targetNode->left == null && $targetNode->right == null ) {
            //父节点存在的情况下
            if ($parentNode) {
                //目标节点是父节点的右子节点
                if ($parentNode->right && $parentNode->right->no == $no) {
                    $parentNode->right = null;
                } else {
                    //否则左子节点
                    $parentNode->left = null;
                }
            } else {
              //叶子节点，且父节点为空,那么该树只有一个节点，且一定是根节点，
                $this->root = null;
            }
            return true;
            //有两个子节点的情况（无需借助父节点）
        } elseif ($targetNode->left != null && $targetNode->right != null ) {
            printf("含有两个子节点的情况".PHP_EOL);
            $minValue = $this->delRightTreeMin($targetNode->right);
            $targetNode->no = $minValue;
            return true;
        } else {
            //$targetNode只有一个子节点的情况，则把它的子节点上调一层
            printf("含有一个子节点的情况".PHP_EOL);
            //判断$targetNode是父节点的左右，还有$targetNode的子节点的左右
                //$targetNode是父节点的左节点
            if ($parentNode->left && $parentNode->left->no == $targetNode->no) {
                //$targetNode的子节点是左孩子节点
                if ($targetNode->left) {//左左
                    //左子节点上调一层
                    $parentNode->left = $targetNode->left;
                } else {//左右
                    //右子节点上调一层
                    $parentNode->left = $targetNode->right;
                }
                //$targetNode是父节点的右节点
            } elseif ($parentNode->right && $parentNode->right->no == $targetNode->no) {
                //$targetNode的子节点是左孩子节点
                if ($targetNode->left) {//右左
                    //左子节点上调一层
                    $parentNode->right = $targetNode->left;
                } else {//右右
                    //右子节点上调一层
                    $parentNode->right = $targetNode->right;
                }
            }
            return true;
        }
    }

}

//======================二叉树的遍历操作
$binTree = new BinaryTree();
$binTree->createSimpleTree();
//$binTree->preOrder();     //前序，就是中左右的顺序遍历节点
//$binTree->infixOrder();   //中序，就是左中右的顺序遍历
////$binTree->postOrder();      //后序，就是左右中的顺序遍历
//$binTree->levelOrder();      //层序，就是层1，层2为单位，一层一层的顺序遍历
//
////$binTree->preOrderSearch(5);    //前序查找
//$binTree->delNode(1);    //删除节点
//printf("删除后".PHP_EOL);
//$binTree->preOrder();     //前序，就是中左右的顺序遍历节点

//二叉排序树的操作
//从一个数组表示的二叉树，创建为二叉排序树
//线性存储转化为直观二叉树的方式
//$arr = [7,3,10,12,5,1,9,2];
//foreach($arr as $item) {
//    $binTree->addSearchNode(new TreeNode($item,"xxx"));
//}
////中序遍历[二叉排序树】，应该是递增的结果就对了
//$binTree->infixOrder();
//$ret = $binTree->delSortNode(3);   // 3，7，10这样的情况
//$ret = $binTree->delSortNode(7);   // 3，7，10这样的情况
//$ret = $binTree->delSortNode(10);   // 3，7，10这样的情况
//$ret = $binTree->delSortNode(2);   // 3，7，10这样的情况
//$ret = $binTree->delSortNode(5);   // 3，7，10这样的情况
//$ret = $binTree->delSortNode(9);   // 3，7，10这样的情况
//$ret = $binTree->delSortNode(1);   // 3，7，10这样的情况
//$ret = $binTree->delSortNode(12);   // 3，7，10这样的情况
//printf("删除后".PHP_EOL);
//var_dump($ret);
//$binTree->infixOrder();


#===============约瑟夫问题

/**
 * Class MonkeyKing
 * 带有头结点的单链表
 * 成为环：最末尾的节点指向第一个节点。
 * 注意：头结点不在环内，仍然指向第一个节点。
 */
class MonkeyKing {
    public $num;
    public $head;

    /**
     * MonkeyKing constructor.
     * @param int $num 猴子数量
     */
    public function __construct(int $num)
    {
        $this->num=$num;
        $this->head = new HeroNode(0,'头');
    }

    /**
     * 成为环形
     * @author: LiuShiFu
     */
    public function circle()
    {
        $tmp = $this->head;
        //构建单链表
        for($i=1;$i<=$this->num;$i++) {
            $m = new HeroNode($i,$i);
            $tmp->next = $m;
            $tmp = $tmp->next;
        }
        //下面这一行是重点，把末尾节点指向第一个节点，就构成了环
        //头节点不参与环，是第一个节点$this->head->next被末尾节点$tmp指向
        $tmp->next = $this->head->next;
    }

    /**
     * 打印下环看看
     * @author: LiuShiFu
     */
    public function show()
    {
        $i=0;
        $tmp = $this->head;
        while($tmp->next && ($i < $this->num+5)) {
            echo "(".$tmp->next->no."|".$tmp->next->name."),";
            $tmp=$tmp->next;
            $i++;
        }
    }

    /**
     * 开始数号码，并出圈
     * @param int $num
     * @author: LiuShiFu
     */
    public function selectKing(int $num)
    {
        //初始化
        //tmp指向第一个猴子节点
        //$pre指向前一个节点，方便猴子出圈处理
        $pre = $this->head;
        $tmp = $pre->next;
        while($this->num > 1) {
            $i=1;
            while($i<$num) {
                //为了出圈方便，需要保留出圈猴子的前一个，就是$pre
                $pre = $tmp;
                $tmp = $tmp->next;
                $i++;
            }
            //$tmp的下一个，交给前一个$pre
            $pre->next = $tmp->next;
            echo "出队：(".$tmp->no."|".$tmp->name.")".PHP_EOL;
            //猴子数量减去1
            $this->num--;
            unset($tmp);
            //$tmp指向下一个节点，继续下一轮数号码
            $tmp=$pre->next;
        }
        echo "最后留队：(".$tmp->no."|".$tmp->name.")".PHP_EOL;
    }
}
//===============测试约瑟夫环
//$monkey = new MonkeyKing(5);
//$monkey->circle();
//$monkey->show();
//echo PHP_EOL;
//$monkey->selectKing(2);


//==========================================图的数据结构

/**
 * 图的存储结构有好几种（邻接矩阵，邻接链表，xxx）这里选择邻接链表；
 * 邻接链表需要一个【顶点数组】+ 【链表】
 *
 *   邻接链表：（四个顶点，4条边）
 *
 * 1[1]===>(3)====>(2)=====>(4)
 * 2[2]===>(1)
 * 3[3]===>(1)====>(4)
 * 4[4]===>(1)====>(3)
 *
 *  示例图：
 *             【1】
 *              = =
 *             =  =  =
 *            =   =   =
 *          【2】  =  【3】
 *                =  =
 *                = =
 *                =
 *             【4】
 *
 */

//临接链表结点，构成边的结点
class arcNode{

    public $idx; //顶点的下标
    public $next=null; //下一个边的指针

    public function __construct($idx)
    {
        $this->idx = $idx;
    }
}


//顶点
class vNode{

    public $num;    //顶点信息
    public $firstArc=null;       //指向第一个边arcNode的顶点指针

    public function __construct(int $num)
    {
        $this->num = $num;
    }
}

//图（就是顶点数组）
class graph{
    public $count;  //结点数量
    public $nodeList=[];   //顶点vNode的数组

    /**
     * 构造方法，构造图
     * graph constructor.
     * @param int $count 顶点数
     */
    public function __construct(int $count=8){

        $this->count = $count;
        //先构造顶点数组
        foreach(range(1,$count) as $v) {
            $this->nodeList[$v] = new vNode($v);
        }

        //每个顶点伸出的边(v1,v2)
        //[1,4]表示顶点1到顶点4有边（无向边）
        foreach([[1,4],[1,3],[1,2],[2,5],[3,7],[4,6],[6,8]] as $item) {

            //构建边的一端arcNode，右端；
            $arcNodeRight = new arcNode($item[1]);
            //该顶点原有的链表，放到新结点的后面，（也就是用头插法为某顶点增加边）
            $arcNodeRight->next = $this->nodeList[$item[0]]->firstArc;
            $this->nodeList[$item[0]]->firstArc = $arcNodeRight;

            //边的另一端，左端；
            $arcNodeLeft = new arcNode($item[0]);
            //该顶点原有的链表，放到新结点的后面，（也就是用头插法为某顶点增加边）
            $arcNodeLeft->next = $this->nodeList[$item[1]]->firstArc;
            $this->nodeList[$item[1]]->firstArc = $arcNodeLeft;

        }
    }

    /**
     * 广度优先算法
     * @author: LiuShiFu
     */
    public function bfs() {
        //初始化标记某顶点是否被访问的数组，0未访问，1访问过
        $visit=array_fill(1,20,0);

        //队列初始化
        $queue = new SplQueue();

        //初始选择一个顶点开始遍历，比如1；
        //注意，初始顶点选择不同，遍历顺序当然会有不同
        $node = $this->nodeList[8];
        //第一个顶点（的下标）入队列
        $queue->enqueue($node->num);
        //输出并标记访问过
        printf("node:%d".PHP_EOL,$node->num);
        $visit[$node->num] = 1;

        //队列存储的是顶点下标
        while($queue->count()) {

            $nodeNum = $queue->dequeue();
            //尝试获取顶点的边（单链表不为空，说明有以顶点$nodeNum为出度的边）
            $arcNode = $this->nodeList[$nodeNum]->firstArc;

            //链表不结束就一直遍历
            while($arcNode) {
                //没有被访问过
                if(!$visit[$arcNode->idx]) {
                    //输出
                    printf("node:%s".PHP_EOL,$arcNode->idx);
                    $visit[$arcNode->idx]=1;//标记访问
                    //$nodeNum的所有邻接点都入队
                    $queue->enqueue($arcNode->idx);
                }
                //链表下一个结点，也就是$nodeNum的下一个邻接点；
                $arcNode = $arcNode->next;
            }
        }
    }
}
//====================测试
$g = new graph();
$g->bfs();

