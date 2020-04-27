<?php

use MongoDB\Driver\Exception\ServerException;

class Zing
{

    /**
     * 第一题：
     *      部门优化题
     *      初始化，第一月为
     *          1:   10，7，5，4；
     *          2：  7，8，6，5     : 2
     *          3：  8，5，7，6     : 3
     *          4：  5，6，8，7     :  0
     *          5：  6，7，5，8     :  1
     *          6：  7，8，6，5 （嘿嘿，又回来了，和2是一样的，以后每四个月都是如此，除4余2的月份就行）
     *          。。。
     *          10： 7，8，6，5
     *          。。。
     *          14：7，8，6，5
     *          。。。
     *          120（10年）：120%4=0，那么为了凑够余数2，所以122是和第6月一样的，然后在6月基础上回退2个月即可。
     *          最终是5，6，8，7
     *
     *          所以只需要计算前四个月即可。
     * @param array $data
     * @param int $month
     * @return array
     * @author: LiuShiFu
     */
    public function departBetter(array $data, int $month)
    {
        if ($month <= 1) {
            return $data;
        }
        $ret[2] = $this->departModify($data);
        $ret[3] = $this->departModify($ret[2]);
        $ret[4] = $this->departModify($ret[3]);
        $ret[5] = $this->departModify($ret[4]);

        $remainder = $month % 4;
        if ($remainder == 0 || $remainder == 1) {
            return $ret[$remainder + 4];
        } else {
            return $ret[$remainder];
        }
    }


    /**
     * 一次调整
     * @param array $data
     * @return array
     * @author: LiuShiFu
     */
    public function departModify(array $data)
    {
        //找到最大值
        $max = max($data);
        //遍历
        foreach ($data as $key => $item) {
            if ($item == $max) {
                $data[$key] -= 3;
            } else {
                $data[$key]++;
            }
        }
        return $data;
    }

    /**
     * 第二题：邀请码校验
     *
     * 1 倒数奇数位的字符相加，也就是 [15,13,11,....3,1]的字符相加；
     * 2 倒数第一位数字开始，而后偶数位的数字，这些数字之和乘以2，
     *      难点：倒数第一个数字出现在，奇数位，则从下一位开始递减2遍历计算；
     *           是偶数位，从当前递减2遍历计算；
     *           不是数字的跳过；
     *
     *
     * @param $code
     * @return string
     * @author: LiuShiFu
     */
    public function checkAccessCode($code)
    {
        //初始化奇偶和
        $odd = $event = 0;
        $top = strlen($code) - 1;
        //规则一：倒数奇数位
        for ($i = $top; $i > -1; $i -= 2) {
            //走替换
            $odd += $this->letter2Num($code[$i]);
        }
        //规则二：
        $j = $top;
        //首先寻找逆向开始的第一个数字
        while ($j > -1) {
            if (is_numeric($code[$j])) {
                break;
            }
            $j--;
        }
        //所在位是奇数还是偶数位
        if ($j > -1 && $j % 2 != 0) {
            $event += $code[$j];
            $j--;
        }
        //偶数位逆向
        for (; $j > -1; $j -= 2) {
            if (!is_numeric($code[$j])) {
                continue;
            }
            //大于9，两位数；
            $event += $code[$j] * 2 > 9 ? $code[$j] * 2 - 9 : $code[$j] * 2;
        }
        if (($odd + $event) % 10) {
            return "error";
        }
        return "ok";
    }

    /**
     * 替换单个字符为一个数字
     * @param $code
     * @return string
     * @author: LiuShiFu
     */
    public function letter2Num($code)
    {
        $letter = "abcdefghijklmnopqrstuvwxyz";
        $digital = "12345678912345678912345678";
        return strtr($code, $letter, $digital);
    }

    /**
     * 游戏币组合题
     *  经过分析
     *      1游戏币只有4种且各不相等
     *      2参数给出了游戏币的数量，
     *      3给出了总分
     *       题目可以理解为，总分一定的情况下，四种不同数字的全排列
     *       然后把符合数量和总分条件的保留输出即可
     *
     * 穷举法,最直接
     * @param $num
     * @param $total
     * @return array
     * @author: LiuShiFu
     */
    public function getDigitalNum($num, $total)
    {
        $data = [];
        //10分游戏币的最大数量intdiv($total, 10)
        for ($i = 0; $i <= intdiv($total, 10); $i++) {
            //5分游戏币的数量上限intdiv($total, 5)
            for ($j = 0; $j <= intdiv($total, 5); $j++) {
                for ($m = 0; $m <= intdiv($total, 2); $m++) {
                    for ($k = 0; $k <= $total; $k++) {
                        //数量和总分检测
                        if (($i + $j + $m + $k == $num) && ($i * 10 + $j * 5 + $m * 2 + $k) == $total) {
                            $data[] = [$i, $j, $m, $k];
                        }
                    }
                }
            }
        }
        return $data;
    }

    /**
     * 有趣两位数
     *  对原来的等式做转换  34 * 86 = 43 * 68  =》 34/43 = 68/86
     *  这样观察发现，34，43分别乘以2正好是68，86；
     *      所以：
     *      只要找一个两位数做分子，分母为交换个位和10位的两位数；
     *      然后分子分母同时乘以2，3，4，5。。。等就会出现有趣的两位数；
     *      不要超过100即可
     *      1开头的两位（11 * 22 = 22 * 11这种就不考虑了）
     *          12/21 = 24/42 = 36/63 = 48/84
     *          13/31 = 26/62 = 39/93
     *          14/41 = 28/82
     *      2开头的
     *      3开头的
     *      4开头的
     *      5，乘以2超过了，不行；后面应该就没有了
     * @return array
     * @author: LiuShiFu
     */
    public function funny2Num()
    {
        //两位数，从12开始即可,因为分子分母都不超过100，随着不断的乘法，所以最大的十位是9
        $data = [];
        //十位
        for ($i = 1; $i < 9; $i++) {
            //个位(交换后成为权为10，最少要乘以2，又不能超过100，所以个位最大为5）
            for ($j = $i + 1; $j < 5; $j++) {
                //分子
                $numerator = $i * 10 + $j;
                //分母
                $denominator = $j * 10 + $i;
                $data[$numerator][] = [$numerator, $denominator];

                //开始乘法
                for ($mul = 2; $mul < 5; $mul++) {
                    //分母先乘以
                    $denominatorNew = $denominator * $mul;
                    if ($denominatorNew >= 100) {
                        break;
                    }
                    $numeratorNew = $numerator * $mul;
                    $data[$numerator][] = [$numeratorNew, $denominatorNew];
                }
            }
        }
        return $data;
    }

    /**
     *
     * 题目要求：
     *      把一个L1=【a1,a2,a3,a4,xxxx,an]的链表，重新排列得到L2=【a1,an,a2,an-1,a3,an-2,...]
     * 单链表的交换位置
     *  可以生成一个空链表L2
     *  依次按照要求的顺序把L1的节点，依次转移到L2中即可
     *      先转移L1的第一个节点，添加到L2的末尾；
     *      再转移L1的最后一个节点；添加到L2的末尾；
     *      重复上述步骤,L1为空结束
     *
     *
     * @author: LiuShiFu
     */
    public function singleListModify()
    {
        //初始化一个只有头指针的链表
        $L1 = new LinkedList(0);
        //头节点为空
        $tmpNode = $L1->head;
        $i = 1;
        //L1的长度
        $length = 7;
        //简单构造几个节点
        while ($i <= $length) {
            $node = new Node($i);
            //尾部增加一个节点
            $tmpNode->next = $node;
            //右移一位，新尾部
            $tmpNode = $tmpNode->next;
            $i++;
        }
        //初始化空链表，把$L1的添加到$L2上
        $L2 = new LinkedList(0);

        //链表长度是从下标编号开始的
        for ($i = 1; $i <= floor(($length+1)/2); $i++) {
            //根据编号找节点
            $front = $L1->findNode($i);
            //L1为空了
            if ($front == null) {
                return $L2;
            }
            //这个节点添加到L2
            $L2->add($front);
            //L1删除这个节点
            $L1->delNode($front);

            //找尾部节点
            $tail = $L1->findNode($length+1-$i);
            //L1为空了
            if ($tail == null) {
                return $L2;
            }
            //尾部节点添加到L2
            $L2->add($tail);
            //L1删除尾部节点
            $L1->delNode($tail);
        }
        return $L2;
    }

    public function nodeT()
    {
        //初始化一个只有头指针的链表
        $L1 = new LinkedList(0);
        //头节点为空
        $tmpNode = $L1->head;
        $i = 1;
        //L1的长度
        $length = 7;
        //简单构造几个节点
        while ($i <= $length) {
            $node = new Node($i);
            //尾部增加一个节点
            $tmpNode->next = $node;
            //右移一位，新尾部
            $tmpNode = $tmpNode->next;
            $i++;
        }

        //找到最后节点

    }

    /**
     * 根据编号找节点
     * 无需关心每个节点的data是啥，把单链表看成是一个数组，头指针是第0个，头指针指向的第一个节点就是编号1，依次类推
     * @param int $no 需要找的第n个节点
     * @param int $L 需要找的第n个节点
     * @author: LiuShiFu
     */
    public function findNodeByIndex(int $no,$L)
    {
        $p = $L;

    }

    /**
     * 系统唯一key
     * 每次生成后，把它存入数据库，
     * 下次再生成后，从数据库中找不到则可以使用，否则继续生成直到库中找不到为止
     *
     *  可以考虑时钟生成器，随机字符串，固定密钥
     * @author: LiuShiFu
     */
    public function UniqueKey()
    {

    }
}

/**
 * 组成链表的节点元素
 */
class Node
{
    /**
     * 数据字段
     * @var
     */
    public $data;

    /**
     * 指向下一个节点的指针
     * @var
     */
    public $next = null;

    public function __construct($data)
    {
        $this->data = $data;
    }
}

/**
 * 定义一个单链表
 */
class LinkedList
{

    /**
     * 头节点（头指针）
     * 是一个固定的，没有实际业务意义的对象，专门指向链表的第一个节点
     * @var
     */
    public $head;

    /**
     * 单链表初始化时，必有头节点
     * LinkedList constructor.
     */
    public function __construct()
    {
        $this->head = new Node(0);
    }

    /**
     * 根据节点编号找节点
     * @param int $num
     * @author: LiuShiFu
     */
    public function findNode(int $num)
    {
        $tmp = $this->head;
        while ($tmp->next) {
            if ($tmp->next->data = $num) {
                //注意，如果直接返回$tmp->next，那么返回的是链表，不是单个节点。
                return new Node($num);
            }
            $tmp = $tmp->next;
        }
        return;
    }

    /**
     * 从尾部添加节点
     * @param Node $node
     * @author: LiuShiFu
     */
    public function add(Node $node)
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
        $temp->next = $node;
    }

    /**
     * 删除链表的节点
     *
     * 如果知道当前节点，可以删除下一个节点。
     * 如果只给出要删除的节点，所以需要找到上一个节点，这是无法操作的
     * 目前为了简单化处理，为每个节点编号：1号，2号，号码位也就是链表的长度，编号不能重复。
     * @param $node
     * @throws Exception
     * @author: LiuShiFu
     */
    public function delNode($node)
    {
        //初始化为头指针
        $front = $this->head;
        //头指针的下一个节点，就是第一个节点
        while ($front->next) {
            //下一个节点的编号,等于要删除的节点
            if ($front->next->data == $node->data) {
                //删除节点后面的临时保存起来
                $tmp = $front->next->next;
                $front->next=null;//删除节点
                $front->next=$tmp;//后面的拼接上
                break;
            }
            $front = $front->next;
        }
        return;
    }
}

$obj = new Zing();

//部门调整优化
//$ret = $obj->departBetter([10,7,5,4],10);

//邀请码
//$ret = $obj->checkAccessCode("abababababababab");

//游戏币的组合
//$ret = $obj->getDigitalNum(5, 18);


//有趣两位数
//$ret = $obj->funny2Num();
//$ret = $obj->letter2Num("2");

//单链表的位置调整
$ret = $obj->singleListModify();
print_r($ret);