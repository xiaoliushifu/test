<?php


/**
 * 二保本
 * @param int $p1
 * @param int $p2
 * @param int $total
 * @author: LiuShiFu
 */
function two($p1 = 0, $p2 = 0, $total = 100)
{
    $assign = [];
    $num = [];
    for ($x = 0; $x <= $total; $x++) {
        $y = $total - $x;
        //开始判断
        if ($p1 * $x >= $total && $p2 * $y >= $total) {
            $assign[] = [$p1 * $x, $p2 * $y];
            $num[] = [$x, $y];
            // 打印结果
            //echo sprintf("第  $count 种方案：<BR>   $p1 购买：%s注,奖金为： %s<br> $p2 购买：%s注,奖金为： %s<br>$p3 购买：%s注,奖金为： %s<br><br><br>", $x,$p1 * $x,$y, $p2 * $y,$z,$p3*$z);
        }
    }
    if (count($num) == 0) {
        die("2没有方案！");
    }
    echo "共 " . count($num) . " 种方案<BR>";
    //接下来整理这些方案，通过计算方差，按照波动从小到达依次输出

    foreach ($assign as $k => $f) {
        $var[] = variance($f);
    }
    //保持索引关系排序
    asort($var);

    //按照波动从小到达输出
    foreach ($var as $k => $v) {
        echo sprintf("第  $k 种方案：<BR>   $p1 购买：%s注，奖金为： %s<br> $p2 购买：%s注，奖金为： %s<br><br><br>", $num[$k][0], $assign[$k][0], $num[$k][1], $assign[$k][1]);
    }
}

/**
 *
 *  二保本
 * @param int $p1
 * @param int $p2
 * @param int $p3
 * @param int $total
 * @author: LiuShiFu
 */
function third($p1 = 0, $p2 = 0, $p3 = 0, $total = 100)
{
    $assign = [];
    $num = [];
    for ($x = 0; $x <= $total; $x++) {
        // 设y为买母鸡的数量
        // 因为一只母鸡要5元，最多买33只母鸡，所以最大值为33
        for ($y = 0; $y <= $total; $y++) {
            // 设z的值为小鸡的数量
            // 小鸡的数量自然就是100 - 公鸡数量 - 母鸡数量
            $z = $total - $x - $y;
            //开始判断
            if ($p1 * $x >= $total && $p2 * $y >= $total && $p3 * $z >= $total) {
                $assign[] = [$p1 * $x, $p2 * $y, $p3 * $z];
                $num[] = [$x, $y, $z];
                // 打印结果
//                echo sprintf("第  $count 种方案：<BR>   $p1 购买：%s注,奖金为： %s<br> $p2 购买：%s注,奖金为： %s<br>$p3 购买：%s注,奖金为： %s<br><br><br>", $x,$p1 * $x,$y, $p2 * $y,$z,$p3*$z);
            }
        }
    }
    if (count($num) == 0) {
        die("3没有方案！");
    }
    echo "共 " . count($num) . " 种方案<BR>";
    //接下来整理这些方案，通过计算方差，按照波动从小到达依次输出

    foreach ($assign as $k => $f) {
        $var[] = variance($f);
    }
    //保持索引关系排序
    asort($var);

    //按照波动从小到达输出
    foreach ($var as $k => $v) {
        echo sprintf("第  $k 种方案：<BR>   $p1 购买：%s注，奖金为： %s<br> $p2 购买：%s注，奖金为： %s<br>$p3 购买：%s注，奖金为： %s<br><br><br>", $num[$k][0], $assign[$k][0], $num[$k][1], $assign[$k][1], $num[$k][2], $assign[$k][2]);
    }
}


/**
 *
 * 四保本
 * @param int $p1
 * @param int $p2
 * @param int $p3
 * @param int $p4
 * @param int $total
 * @author: LiuShiFu
 */
function four($p1 = 0, $p2 = 0, $p3 = 0, $p4 = 0, $total = 100)
{
    $assign = [];
    $num = [];
    for ($x = 0; $x <= $total; $x++) {
        for ($y = 0; $y <= $total; $y++) {
            for ($z = 0; $z <= $total; $z++) {
                $n = $total - $x - $y - $z;
                //开始判断
                if ($p1 * $x >= $total && $p2 * $y >= $total && $p3 * $z >= $total && $p4 * $n >= $total) {
                    $assign[] = [$p1 * $x, $p2 * $y, $p3 * $z, $p4 * $n];
                    $num[] = [$x, $y, $z, $n];
                }
            }
        }
    }
    if (count($num) == 0) {
        die("4没有方案！");
    }
    echo "共 " . count($num) . " 种方案<BR>";
    //接下来整理这些方案，通过计算方差，按照波动从小到达依次输出

    foreach ($assign as $k => $f) {
        $var[] = variance($f);
    }
    //保持索引关系排序
    asort($var);

    //按照波动从小到达输出
    foreach ($var as $k => $v) {
        echo sprintf("第  $k 种方案：<BR>   $p1 购买：%s注,奖金为： %s<br> $p2 购买：%s注,奖金为： %s<br>$p3 购买：%s注,奖金为： %s<br><br><br>", $num[$k][0], $assign[$k][0], $num[$k][1], $assign[$k][1], $num[$k][2], $assign[$k][2], $num[$k][3], $assign[$k][3]);
    }
}


/**
 * 方差：初中代数内容，
 * 描述一组数据的波动性。
 * 1总和求平均数
 * 2每个元素和平均数的差距（防止负数具体用差求平方，后续再开方即可）
 * 3平方求和再取平均数
 * 4最后开方得到的结果，就是方差
 * @param $arr
 * @return array|float
 * @author: LiuShiFu
 */
function variance($arr)
{

    $length = count($arr);
    if ($length == 0) {
        return array(0, 0);
    }

    //取平均值
    $average = array_sum($arr) / $length;
    $count = 0;

    //方差计算（各个元素与平均值的差据，为防止出现负数，再加上平方即可）
    foreach ($arr as $v) {
        $count += pow($average - $v, 2);
    }
    //再求平均值
    $variance = $count / $length;
    //结果返回
//        return array('variance' => $variance, 'square' => sqrt($variance), 'average' => $average);
//        return array('square' => sqrt($variance), 'average' => $average);
    //开平方返回
    return sqrt($variance);
}

//    two(3.25,1.41,100);

/**
 * 一种简易打印数组的方法
 * @param $arr
 * @author: LiuShiFu
 */
function printArr($arr)
{
    $str = "";
    if (count($arr)) {
        $str = "[";
        foreach ($arr as $item) {
            $str .= $item . ",";
        }
        $str = substr($str, 0, -1);
        $str .= "]";
    }
    echo $str;
}

/**
 * 给出整数n(n>1),计算1-n之间的质数；
 * 除1和本身之外，再无整除的数；
 * 如2，3，5，7，11等
 * 1不是质数
 *
 * 试除法：最笨最简单的方法
 *  从小于n的每个数比如m，1<m<n;
 *  从[2-m)不断尝试是否有能够整除m的，存在那么m不是；不存在那么m是质数；
 *  m+1,再次执行上述1，2步骤；
 * @param int $n
 * @return array
 * @author: LiuShiFu
 */
function mathZhiShu(int $n): array
{
    $ret = [];
    if ($n <= 1) {
        return $ret;
    }
    $ret[] = 2;
    //遍历
    for ($i = 3; $i <= $n; $i += 2) {
        //从2开始尝试整除
        $divide = 2;
        while ($divide < $i) {
            //能够整除，肯定不是
            if ($i % $divide == 0) {
                break;
            }
            $divide++;
        }
        //正常循环结束的
        if ($divide == $i) {
            $ret[] = $i;
        }
    }
    return $ret;
}

/**
 * 优化，穷举范围到[2,floor(sqr(n))]
 * 因为一个数如果是合数，那么因式分解必是成对出现的，比如100，有2x50,4x25,5x20,10x10；
 * 观察这些成对的因数，最中间的数就是sqrt(100)=10;
 * 如果一个因数大于等于10，那么另外一个因数必然小于等于10；
 * 所以，如果是合数，那么它的因数分解的较小的那一个因数必然在[1,10]之间；
 * 如果这区间还没有，那就真的没有了。那么它就是质数了
 * @param int $n
 * @return array
 * @author: LiuShiFu
 */
function mathZhiShu2(int $n): array
{
    $ret = [];
    if ($n <= 1) {
        return $ret;
    }
    $ret[] = 2;
    //质数肯定不能是偶数（大于2时），所以从3开始，依次+2
    for ($i = 3; $i <= $n; $i += 2) {
        $divide = 2;
        //如果在[2,sqrt($i)]区间不存在可整除的，那么（sqrt($i)，$i]也不会有整除的了
        $sqrt = floor(sqrt($i));
        while ($divide <= $sqrt) {
            //能够整除，肯定不是
            if ($i % $divide == 0) {
                break;
            }
            $divide++;
        }
        //正常循环结束的，不是break(超过$sqrt的）
        if ($divide > $sqrt) {
            $ret[] = $i;
        }
    }
    return $ret;
}


/**
 * 选择排序
 *
 * 比一开始的想法有改进：
 *  发现比手上的牌更小时，并不立即交换，而只是标记下，直到最后这一轮比较完，只需交换一次即可；
 *  多用了两个变量，减少了中间交换的次数，使得每一轮最多交换一次；
 * @param array $arr
 * @return array
 * @author: LiuShiFu
 */
function selectSort(array $arr): array
{
    $n = count($arr);
    for ($j = 0; $j < $n; $j++) {
        //初始化拿第一张牌
        $value = $arr[$j];
        $key = $j;
        for ($i = $j + 1; $i < $n; $i++) {
            //比手上牌小的
            if ($value > $arr[$i]) {
                //重置手中的牌，保持这一轮最小
                $value = $arr[$i];
                //标记下当时的下标，此时并不交换，到最后了再交换
                $key = $i;
            }
        }
        //这一轮下来如果下标发生了变化,则说明中间发生了重置，此时交换就行
        if ($j != $key) {
            $arr[$j] = $arr[$j] ^ $arr[$key];
            $arr[$key] = $arr[$j] ^ $arr[$key];
            $arr[$j] = $arr[$j] ^ $arr[$key];
        }
    }
    return $arr;
}

/**
 * 冒泡
 * 最诟病的算法，性能不行
 * @param array $arr
 * @return array
 * @author: LiuShiFu
 */
function BubbleSort(array $arr): array
{
    $n = count($arr);
    //只管比较的趟数；3个数，比较2趟就行；所以$n-1趟；
    for ($i = 1; $i < $n; $i++) {
        $k = false;
        //每一趟都确定了一个最大的在最后，所以下一次到这就无需再比较了
        for ($j = 0; $j < $n - $i; $j++) {
            //交换
            if ($arr[$j] > $arr[$j + 1]) {
                $k = true;
                $arr[$j] = $arr[$j] ^ $arr[$j + 1];
                $arr[$j + 1] = $arr[$j] ^ $arr[$j + 1];
                $arr[$j] = $arr[$j] ^ $arr[$j + 1];
            }
        }
        if (!$k) {
            return $arr;
        }
    }
    return $arr;
}

/**
 *  插入排序（从小到大）
 * 把一个待排序的数列看成两个部分：一个无序区，一个有序区；（初始时假设最左边第一个数为有序区的，剩余其他全部为无序区序列）
 * 每次从有序区取得一个数据（基准数），和有序区的序列比较，放到合适的位置：
 *  如果基准数小于有序区的第一个数，那么，有序区右移一位，占据基准数位置，第一个数腾出位置待定；
 *  依次比较有序区的第二个数，如果大于第二个就直接插入到此时的空位（有序区右数第二个）,比较结束
 *  如果有序区走完没有比基准数小的，那么基准数就是最小的，那就直接插入到有序区末尾的空位
 *
 * @param array $a
 * @author: LiuShiFu
 * @return array
 */
function insertSort(array $a) {

    $n = count($a);
    //初始化时，有序区只有左边1个，剩下全部为无序区n-1个，需要全部遍历无序区,下标[1,n-1]
    for($i=1;$i<$n;$i++) {
        //基准数
        $value = $a[$i];
        //每次基准数的左边即是有序区的最右边；
        for($j=$i-1;$j>=0;$j--) {
            //基准数小，那么右移开始
            if ($value < $a[$j]) {
                $a[$j+1] = $a[$j];
                //是否比较到最后一个数了
                if ($j == 0) {
                    $a[$j] = $value;
                }
            } else {
                //否则直接插入
                $a[$j+1] = $value;
                //一旦插入，说明成功归位，退出这一趟比较
                break;
            }
        }
    }
    return $a;
}