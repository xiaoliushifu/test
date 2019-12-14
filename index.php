<?php

require __DIR__.'/vendor/autoload.php';

#打开错误提示
ini_set("display_errors", "On");
#可以报告任何级别的错误，如果上面display_errors关闭，那么这个设置不管用
error_reporting(E_ALL);
//ini_set("max_execution_time",0);


//创建一个新的 DOM文档
$dom = new DOMDocument();
//在根节点创建 departs标签
$request = $dom->createElement('Request');
$dom->appendChild($request);

//在 departs标签下创建 depart子标签
$service = $dom->createElement('depart');
$request->appendChild($service);
//输出 XML数据
echo $dom->saveXML();
