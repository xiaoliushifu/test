<?php


class AsyMysql
{
    private $asyDB=null;
    private $dbConfig= [];
    public function __construct()
    {
        //在4.3.+已经移除，该用协程了。这里就不能演示了
        //这里重点说下异步mysql客户端的应用场景：独立于PDO的mysql异步连接对象
        //适合做一些非同步的不影响正常业务的小操作：比如
        //  查看一篇文章后更新文章阅读数量；
        //  审批通过后增加操作日志；
        //  发货后通知DB发送短信等
        //可以配合PDO而不完全代替PDO
        $this->asyDB = new Swoole\Mysql();
        $this->dbConfig = [
            'host' => '192.168.56.102',
            'port' => 3306,
            'user' => 'root',
            'password' => 'mysqlis3306',
            'database' => 'mydb',
            'charset' => 'utf8', //指定字符集
            'timeout' => 2,  // 可选：连接超时时间（非查询超时时间），默认为SW_MYSQL_CONNECT_TIMEOUT（1.0）
        ];
    }

    public function execute() {
        //连接
        echo "connect method begin ".PHP_EOL;
        //在一个连接的回调函数中完成sql执行，回调嵌套可能很深
        $this->asyDB->connect($this->dbConfig,function ($db,$r) {
            echo "connect method work ".PHP_EOL;
            if ($r === false) {
                var_dump($db->connect_errno, $db->connect_error);
                die;
            }
            $sql = 'show tables';
            $db->query($sql, function(swoole_mysql $db, $r) {
                if ($r === false)
                {
                    var_dump($db->error, $db->errno);
                }
                elseif ($r === true )
                {
                    var_dump($db->affected_rows, $db->insert_id);
                }
                var_dump($r);
                //如果不关闭，异步mysql客户端对象不销毁，常驻内存
                //所以swoole的worker进程一直工作
                //后续异步redis也是
                $db->close();
            });
        });
        echo "connect method end".PHP_EOL;
        return true;
    }
}

$asyMysql = new AsyMysql();
$ret = $asyMysql->execute();
var_dump("return: ",$ret);