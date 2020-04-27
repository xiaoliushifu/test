<?php

/*
 * 数据库操作类
 */
class mydb
{
	/*
	 * 日志
	 */	
	public $log = './' ;
	/*
	 * 重试限次
	 */
	public $try_num = 2 ;
	/*
	 *重试停留时间
	 */
	public $sleep_time = 3 ;
	/*
	 *数据库对象 
	 */
	private $dbh = NULL ;
	/*
	 * 链接参数
	 */
	private $conn_bak = array() ;
	/*
	 * 数据库
	 */
	private $db_bak = '' ;
	/*
	 * 记录集
	 */
	private $db_result = '' ;
	function __construct()
	{
	}

	function __destruct()
	{
		$this->close() ;
	}
	/*
	 * 错误日志写入
	 */
	function mylog($str)
	{
		//注意sqlerr.log是日志的名字
		error_log( date('Y-m-d H:i:s').$str."\r\n", 3, 'sqlerr.log') ;//sqlerr.log
	}
	/*
	 *连接数据库 
	 */
	function connect($d)
	{
		$this->dbh = mysql_connect( $d['host'], $d['user'], $d['password'] ) ;
		if ( !$this->dbh )
		{
			$this->mylog( "Can not connect db: {$d['host']}\n" ) ;//写入日志
			return False ;
		}
		$this->conn_bak = $d ;//数据库的连接信息做个备份
		$this->select_db($d['dbname']);//选择数据库
		return True ;
	}
	/*
	 * 选择数据库
	 */
	function select_db($db)
	{
		if ( !mysql_select_db( $db, $this->dbh ) )
		{
			$this->mylog( "Can not select db: {$db}: ".mysql_error()."\n" ) ;
			return False ;
		}
		$this->db_bak = $db ;//数据库名备份
		if($this->conn_bak['charset'])//如果有字符集，还可以设置字符集
			$this->set_charset($this->conn_bak['charset']);//有字符集就设置字符集
		return True ;
	}
	/*
	 *关闭链接
	 */
	function close()
	{
		//if ($this->dbh)mysql_close( $this->dbh );
		$this->dbh = NULL ;
	}
	/*
	 * 执行SQL
	 */
	function exe($sql)
	{
		for ( $i = 1; $i <= $this->try_num; $i++ )//重试限次为2
		{
    		$ret = mysql_query( $sql, $this->dbh ) ;//$this->dbh，当前连接资源
    		if ( !$ret )
    		{
    		    $this->mylog( "query failed: {$sql}: ".$this->db_error()."\n" ) ;//本次失败的sql语句和失败信息入库。
    		    if($this->connect($this->conn_bak))//尝试用备份再连接一次数据库服务器
    		    	continue;				
				sleep(3);//歇三秒，再来
	   		}
    		else
    		    break ;
	    }
	    if (!$ret)
	    	return false ;//在限次的范围（默认2）内，都失败
	    $this->db_result=$ret;//存到这里
	    $this->affected = mysql_affected_rows($this->dbh);//取得刚刚的操作影响的行数
		return True ;
	}
	/*
	 * 设置数据库编码SET NAMES
	 */
	function set_charset($charset){
		if(!empty($charset))
			$this->exe("SET NAMES {$charset}");		
	}
	/*
	 * 读取记录
	 */
	function query($sql,$type=MYSQL_ASSOC) {//$type决定是返回索引还是关联数组
	 	if(!$this->exe($sql))
	 		return false;//每执行一次exe，就把结果集放到db_result
		if(mysql_num_rows($this->db_result)<1)
			return false;
 		if($type!=MYSQL_NUM) {
			$type=MYSQL_ASSOC;
		}
		$rs=array();
		while($rs[]=mysql_fetch_array($this->db_result,$type));//二维数组
		if(sizeof($rs)>1 ) {//sizeof，count的别名
 			unset($rs[count($rs)-1]);//为什么去掉最后一个元素????
 		}
 		return $rs;//返回多条记录的数组形式
	 }
	 
	 function row($sql)
	 {
		$r=$this->query($sql);//返回数组，其中每一个元素为数据库的一条记录
		if($r) 
			return array_shift($r);//返回第一条记录，数组形式
		return false;
	 }
	 function rows($sql)
	 {
		return $this->query($sql);	
	 }
	/*
	 * 得到错误信息
	 */
	function db_error()
	{
		return mysql_error( $this->dbh ) ;//返回刚才操作数据库时的错误信息
	}
	/*
	 * 得到连接信息
	 */
	function get_dbh()
	{
		return $this->dbh ;
	}
	/*
	 *定义报错位置 
	 */
	function set_log($log)
	{
		$this->log = $log ;
	}
}

