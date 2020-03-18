<?php


/**
 * 二保本
 * @param int $p1
 * @param int $p2
 * @param int $total
 * @author: LiuShiFu
 */
function two($p1=0,$p2=0,$total=100) {
    $assign = [];
    $num = [];
    for ($x = 0; $x <= $total; $x++) {
            $y = $total - $x;
            //开始判断
            if ($p1 * $x >= $total  && $p2 * $y >= $total) {
                $assign[]=[$p1*$x,$p2*$y];
                $num[]=[$x,$y];
                // 打印结果
                //echo sprintf("第  $count 种方案：<BR>   $p1 购买：%s注,奖金为： %s<br> $p2 购买：%s注,奖金为： %s<br>$p3 购买：%s注,奖金为： %s<br><br><br>", $x,$p1 * $x,$y, $p2 * $y,$z,$p3*$z);
            }
    }
    if (count($num) == 0) {
        die("2没有方案！");
    }
    echo "共 ".count($num)." 种方案<BR>";
    //接下来整理这些方案，通过计算方差，按照波动从小到达依次输出

    foreach($assign as $k => $f) {
        $var[] = variance($f);
    }
    //保持索引关系排序
    asort($var);

    //按照波动从小到达输出
    foreach($var as $k=>$v) {
        echo sprintf("第  $k 种方案：<BR>   $p1 购买：%s注，奖金为： %s<br> $p2 购买：%s注，奖金为： %s<br><br><br>", $num[$k][0],$assign[$k][0],$num[$k][1],$assign[$k][1]);
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
    function third($p1=0,$p2=0,$p3=0,$total=100) {
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
                if ($p1 * $x >= $total  && $p2 * $y >= $total && $p3 * $z >= $total) {
                    $assign[]=[$p1*$x,$p2*$y,$p3 * $z];
                    $num[]=[$x,$y,$z];
                    // 打印结果
    //                echo sprintf("第  $count 种方案：<BR>   $p1 购买：%s注,奖金为： %s<br> $p2 购买：%s注,奖金为： %s<br>$p3 购买：%s注,奖金为： %s<br><br><br>", $x,$p1 * $x,$y, $p2 * $y,$z,$p3*$z);
                }
            }
        }
        if (count($num) == 0) {
            die("3没有方案！");
        }
        echo "共 ".count($num)." 种方案<BR>";
        //接下来整理这些方案，通过计算方差，按照波动从小到达依次输出

        foreach($assign as $k => $f) {
            $var[] = variance($f);
        }
        //保持索引关系排序
        asort($var);

        //按照波动从小到达输出
        foreach($var as $k=>$v) {
            echo sprintf("第  $k 种方案：<BR>   $p1 购买：%s注，奖金为： %s<br> $p2 购买：%s注，奖金为： %s<br>$p3 购买：%s注，奖金为： %s<br><br><br>", $num[$k][0],$assign[$k][0],$num[$k][1],$assign[$k][1],$num[$k][2],$assign[$k][2]);
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
    function four($p1=0,$p2=0,$p3=0,$p4=0,$total=100) {
        $assign = [];
        $num = [];
        for ($x = 0; $x <= $total; $x++) {
            for ($y = 0; $y <= $total; $y++) {
                for ($z = 0; $z <= $total; $z++) {
                    $n = $total - $x - $y - $z;
                    //开始判断
                    if ($p1 * $x >= $total  && $p2 * $y >= $total && $p3 * $z >= $total && $p4 * $n >= $total) {
                        $assign[]=[$p1*$x,$p2*$y,$p3*$z,$p4*$n];
                        $num[]=[$x,$y,$z,$n];
                    }
                }
            }
        }
        if (count($num) == 0) {
            die("4没有方案！");
        }
        echo "共 ".count($num)." 种方案<BR>";
        //接下来整理这些方案，通过计算方差，按照波动从小到达依次输出

        foreach($assign as $k => $f) {
            $var[] = variance($f);
        }
        //保持索引关系排序
        asort($var);

        //按照波动从小到达输出
        foreach($var as $k=>$v) {
            echo sprintf("第  $k 种方案：<BR>   $p1 购买：%s注,奖金为： %s<br> $p2 购买：%s注,奖金为： %s<br>$p3 购买：%s注,奖金为： %s<br><br><br>", $num[$k][0],$assign[$k][0],$num[$k][1],$assign[$k][1],$num[$k][2],$assign[$k][2],$num[$k][3],$assign[$k][3]);
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
    function variance($arr) {

        $length = count($arr);
        if ($length == 0) {
            return array(0,0);
        }

        //取平均值
        $average = array_sum($arr)/$length;
        $count = 0;

        //方差计算（各个元素与平均值的差据，为防止出现负数，再加上平方即可）
        foreach ($arr as $v) {
            $count += pow($average-$v, 2);
        }
        //再求平均值
        $variance = $count/$length;
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
    function printArr($arr) {
        $str = "";
        if (count($arr)) {
            $str  =  "[";
            foreach ($arr as $item) {
                $str .= $item.",";
            }
            $str = substr($str,0,-1);
            $str .="]";
        }
        echo  $str;
    }