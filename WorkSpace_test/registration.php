<?php
date_default_timezone_set('Asia/Hong_Kong');

//include './webfiles/common/config.inc';
//include './webfiles/common/feedback.php';
//include './webfiles/common/libs.php';
include './webfiles/common/webbasic.php';
//include './webfiles/common/bat4f5basic.php';

$classname = 'yiichina';

include_once "./webfiles/$classname.php";
$company = 'liugeShifu';
$city = 1;
$web = new $classname($city , $company) ;
$web->siteid = 0 ;
//将要投送的目标网站的登录数据，用户名，密码，
//$webUser[]=array(
//    'userName'=>'高三学生',
//    'userKey'=>'test123'
//);
//$webUser[]=array(
//    'userName'=>'高三学生',
//    'userKey'=>'test123'
//);
$web->site[0] = array(
//    'userName'=>'高三学生',
//    'userKey'=>'test123',
    'userName'=>'872140945@qq.com',
    'userKey'=>'yiichina2444',
);
//foreach($webUser as $user) {
//
//}

$ret=$web->login();
if($ret['status']==9) {
    //签到
    $ret=$web->registration();
    echo "registration:".PHP_EOL;
    print_r($ret);
} else {
    echo "login:".PHP_EOL;
    print_r($ret);
}