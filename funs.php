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
 * 每一轮的比较，都能归位一个数，都能把手上的基准数确定放到哪个位置；
 *
 * 比一开始的想法有改进：
 *  发现比手上的牌更小时，并不立即交换，而只是标记下，直到最后这一轮比较完，只需交换一次即可；
 *  多用了两个变量，减少了中间交换的次数，使得每一轮最多交换一次；
 *
 *
 * 时间复杂度：
 *  两层循环：所以应该是O(m x n)系列（m指的是外层循环次数，n是内层循环次数）
 *  再进一步想想，外层m是[0,n);跟n成线性相关；
 *  而内层循环跟外层$j有关：
 *  当$j=0时，内层为n;    $j=1,内层为n-1;    $j=2,内层为n-2;
 *  所以，加起来： n+(n-1)+(n-2)+....+1 = (1+n)n/2;    其实是数学公式的考察；
 *  O((1+n)n/2) = O(n平方）
 *  虽然【选择排序】和【冒泡排序】在一个数量级上，但是我们也要知道，【选择排序】还是比【冒泡】略快
 *  在含有百万元素的数组中排序，还是能差个20秒以上的（mac上）
 * 10000个（0，80000）的数组： 【冒泡】平均30秒；【选择】平均11秒；【快速】一秒不到！
 *
 *
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
        //一个小小的优化：不交换，而改为互置
        if ($j != $key) {
            //重置式，三者中最快的，有微小的快
            $arr[$key] = $arr[$j];
            $arr[$j] = $value;    //重置最小值

            //纯交换式，最慢
//            $tmp = $arr[$j];
//            $arr[$j] = $arr[$key];
//             $arr[$key] = $tmp;

            //位交换式 感觉比纯交换式略快，但是测试时多了两个11秒
//            $arr[$j] = $arr[$j] ^ $arr[$key];
//            $arr[$key] = $arr[$j] ^ $arr[$key];
//            $arr[$j] = $arr[$j] ^ $arr[$key];
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
            } else {
                break;
            }
        }
        //循环结束，则说明找到了插入的位置
        $a[$j+1] = $value;
    }
    return $a;
}

/**
 * 快速排序
 * 这种递归写法，全程没有发生数据交换。只是不断的递归拆分。
 * 所以，尽量减少【交换】操作，是提高效率的一种方法
 * 快速排序，名称上来看，称得上【快速】！
 * @param array $arr
 * @return array
 * @author: LiuShiFu
 */
function quikSort(array $arr) {
    //特殊情况只有一个或者空数组，直接返回即可
    if(($length = count($arr)) < 2) {
        return $arr;
    }
    //默认第一个为基准数
    $base_num = $arr[0];
    //初始化左范围，右边范围
    $left_array = $right_array = [];

    //遍历开始分发
    $i=1;
    while($i < $length){
        //比基准数小的放入左边
        if ($base_num > $arr[$i]) {
            $left_array[] = $arr[$i];
        } else {
            $right_array[] = $arr[$i];
        }
        $i++;
    }
    //同理，左右范围依次做同样的处理（再取基准数分发）
    $left_array = quikSort($left_array);
    $right_array = quikSort($right_array);
    //最后就是它了
    return array_merge($left_array,[$base_num],$right_array);
}

//=========================测试排序
$arr = range(0,9999);
//$arr =[];
for($i=0;$i<10000;$i++) {
//    $arr[$i] = mt_rand(0,80000);
    $mt = mt_rand(0,80000);
    while(in_array($mt,$arr)) {
        $mt = mt_rand(0,80000);
    }
    $arr[$i] = $mt;
}
echo "排序前:".date("H:i:s").PHP_EOL;
//$arr = BubbleSort($arr);      //差不多30秒
$arr = selectSort($arr);      //平均10秒  交换式【10，10，10，10，11】 重置式【10，10，10，10，10】 位交换式【11，11,10,10,11】
//$arr = insertSort($arr);        //平均10秒
//$arr = quikSort($arr);    //最快，不到1秒
//printArr($arr);
echo "排序后:".date("H:i:s").PHP_EOL;