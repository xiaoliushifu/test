<?php

require __DIR__.'/vendor/autoload.php';

use Monolog\Handler\BrowserConsoleHandler;
use Monolog\Logger;
use Monolog\Handler\StreamHandler;
use Monolog\Handler\FirePHPHandler;
//phpinfo();exit;
#打开错误提示
ini_set("display_errors", "On");
#可以报告任何级别的错误，如果上面display_errors关闭，那么这个设置不管用
error_reporting(E_ALL);
//ini_set("max_execution_time",0);



// 创建日志服务
$logger = new Logger('my_logger');

$handler = new StreamHandler(__DIR__.'/my_app.log', Logger::DEBUG);
//这种添加的加工程序，就是只为当前处理器使用
$handler->pushProcessor(function($record){
    $record['extra']['stream'] = 'StreamHandler handled!';
    $record['extra2'] = 'StreamHandler handled22!';
    return $record;
});
// 添加一些处理器
$logger->pushHandler($handler);
//$logger->pushHandler(new FirePHPHandler());
$logger->pushHandler(new BrowserConsoleHandler());

// 在$logger对象上增加的Processer，所有的handler都会应用
$logger->pushProcessor(function ($record) {
    $record['extra']['dummy'] = 'Hello world!，所有处理器可以应用';

    return $record;
});

// 现在你就可以用日志服务了
$logger->info('My logger is now ready');
