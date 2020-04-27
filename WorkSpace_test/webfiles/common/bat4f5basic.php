<?php


 class bat4f5basic {
 	function __construct($city , $company)
 	{
 		//初始化几个数据库的连接cookieDB，feedbackDB,remoteDB,newfeedbackDB
 		$dbInfo = getDbInfo($city , $company);
 		$this->db = new mydb() ;//实例化mysql对象
 		foreach($dbInfo as $key=>$value)
		{
			//$this->cookieDB,$this->feedbackDB,$this->remoteDB,$this->newfeedbackDB
			$this->$key = $value;//
		}
 	}

 	//老版一键刷新 回填 重新调用
 	public function batTaskRetry()
	{
		$this->db->connect($this->feedbackDB);
		$query="UPDATE `ff_bat4f5_timer` SET `Today`='', `Type4RentSale`='0' WHERE `ID`={$this->refreshID} AND `UID`={$this->refreshUID} LIMIT 1"; 
		$this->db->exe($query);//这种，exe返回的信息到哪里去了？
		error_log('refreshID:'.$this->refreshID.',uid:'.$this->refreshUID.date('Y-m-d H:i:s')."\r\n" , 3 , 'ffee3.log') ;
		$this->db->close();
	}
 //老版一键刷新 回填
	public function refreshAdd($wid , $userName , $userKey , $sort , $para = array())  //如果打开网站刷新失败，回填一条，延时一分钟
	{
		$this->db->connect($this->cookieDB);
		$tmpTime = time() + 300 ;
		$query="INSERT INTO RefreshTable (`WID` , `MOD` , `userName` , `userKey` , `num` ,`time` , `sort`) VALUES ({$wid} , 'focus2' , '{$userName}' , '{$userKey}' , '{$para['limit']}' , '{$tmpTime}' , '{$sort}')"; 
		$this->db->exe($query);
		$this->db->close();
		error_log('wid:'.$wid.',username:'.$userName.',key:'.$userKey.','.date('Y-m-d H:i:s').',add'."\r\n" , 3 , 'ffee2.log') ;
	}
 	/**
	* refreshAdd2
	*
	*  新版刷新任务意外失败后重试入库,默认全放到北京
	*  该函数只接收两个参数
	*  $wid，是哪个账号的
	*  $msg  记录了回踹的原因
	* 
	* 
	* 2012-11-20
	*/		
	function refreshAdd2($wid,$msg='')
	{
		$this->db->connect($this->cookieDB);//('localhost','root','sycx_2009abc','test')(搜房192.168.0.110)（58 0.123）
		$t = rand(60,120);//注意，time字段，至少加上一分钟后的时间
		$tmpTime = time() + $t ;//$_GET,$_POST为序列化的数据
		$query="INSERT INTO test.RefreshTable2 (`WID` , `time` , `GET` , `POST` , `msg`) VALUES ({$wid} , '{$tmpTime}' , '".serialize($_GET)."' , '".serialize($_POST)."' , '{$msg}')"; 
		$this->db->exe($query);
		$this->db->close();
		error_log("{$wid} error ".date('Y-m-d H:i:s')."\r\n" , 3 , 'bat4f5v2Err.log') ;//还要打入log
	}
 	/**
	* batf5into
	*
	*  一键刷新记录入库
	* 
	* 
	* 2012-11-15
	*/		
	function batf5into($SID , $WID , $Rent , $Sale , $Failure ,$Live , $Points , $Error)
	{
		if($this->newfeedbackDB['dbname'] == 'yufeng_db')//在libs.php中，dbname初始化为test
		{
			$this->newfeedbackDB['host'] = '192.168.0.160' ;//裕丰的表，在160服务器上
		}
		//连接数据库服务器并选择数据库
		$this->db->connect($this->newfeedbackDB);//(192.168.0.161,sycxuser,tuitui99_2009abc,test)
		//可以去0.161服务器上查看一下 ff_bat4f5_timer_status这个表的各个字段
		//当剩余点数为0时，Error=citylive 0；Rent,Sale，Failure都为null
		$sql = "insert into `ff_bat4f5_timer_status` (`SID`,`WID`,`Time`,`Rent`,`Sale`,`Failure`,`Live`,`Points`,`Error`) values ('{$SID}','{$WID}',now(),'{$Rent}','{$Sale}','{$Failure}','{$Live}','{$Points}','{$Error}')";
		$this->db->exe($sql);
		$this->db->close();
		return true;
	}
/**
	 * 刷新过的房源修改刷新时间(实际还没刷新呢，即将要刷新了)
	 * 我们自己增加刷新时间这个字段，实际58并不能看出哪一条房源什么时候刷新过，或者知道指定房源的最近刷新时间
	 * **/
 	public function uptime($wid,$data)
	{
		if($data)
		{
			$this->db->connect($this->newfeedbackDB);//192.168.0.161
			$t = time();
			foreach($data as $value2)
			{
				$sql = "update ff_bat4f5_house set RefTime = '{$t}' where WID = {$wid}  and RemoteID = '{$value2}'";
				$this->db->exe($sql);
			}
			$this->db->close();
		}
		return TRUE;
	}
	/**
	 * 刷新一次 加一，所以这里其实记录着刷新次数。count字段
	 * 但是，这个表中的数据，如何参考呢？
	 * **/
	public function addcount($wid,$use=0,$num=0)
	{
		$this->db->connect($this->newfeedbackDB);
		$sql = "select count from ff_bat4f5_count  where WID = {$wid}";
		$res = $this->db->row($sql);
		if($res['count'])
			$sql = "update ff_bat4f5_count set `count` = count+1 , `use` = '{$use}' , `num` = '{$num}' where WID = {$wid}";
		else
			$sql = "insert into ff_bat4f5_count (`WID` , `count` ,`use`,`num`) values ('{$wid}' , 1 ,'{$use}','{$num}')";
		$this->db->exe($sql);
		$this->db->close();
		return TRUE;
	}
	/**
	 * 
	 * 获取刷新次数
	 * 获取某个账户的刷新次数，count
	 * **/
	public function getcount($wid,$num=30)
	{
		$this->db->connect($this->newfeedbackDB);
		$sql = "select count from ff_bat4f5_count  where WID = {$wid}";
		$res = $this->db->row($sql);
		$count = $res['count'];
		if($count >= $num)//大于30次就置为1，为什么？？？
		{
			$sql = "update ff_bat4f5_count set `count` = 1 where WID = {$wid}";
			$this->db->exe($sql);
		}
		$this->db->close();
		return $count;//最大值为30呗
	}
 /**
	 * 
	 * 获取刷新次数和点数
	 * **/
	public function getcount2($wid,$num=30)
	{
		$this->db->connect($this->newfeedbackDB);
		$sql = "select * from ff_bat4f5_count  where WID = {$wid}";
		$res = $this->db->row($sql);
		$ret = array();
		$ret['count'] = $res['count'];
		$ret['num'] = $res['num'];
		$ret['use'] = $res['use'];
		if($ret['count'] >= $num)
		{
			$sql = "update ff_bat4f5_count set `count` = 1 where WID = {$wid}";
			$this->db->exe($sql);
		}
		$this->db->close();
		return $ret;
	}
	/**
	 * 房源入库   
	 * 其实是我们自己的缓存机制，把获得的房源信息存到数据库中，
	 * 为接下来的30次刷新，使用同样的房源去刷新
	 * 当然，在这30次之内，如若在58的房源有变化，这样数据表里的房源，也就不同步了，那就失败了呗。
	 * **/
	public function posthouse($wid,$data,$sort)
	{
		$this->db->connect($this->newfeedbackDB);//192.168.0.161
		if($sort=='rent')
			$sort = 3;
		else
			$sort = 6;
		//删除上一次存储的房源
		$sql = "delete from ff_bat4f5_house where WID = {$wid} and Type = {$sort}";
		$this->db->exe($sql);
		if(empty($data[0]))//没有房源，那就别入库了，直接走人,但是上一步已经delete了所有的租或者售了
			return FALSE;
		$t = date('Y-m-d H:i:s');//time字段，标记存入表中的时间，将来用他来了解这次存入的房源已经在库中多久了
		foreach($data as $key=>$value)
		{
			if(is_array($value))
			{
				$sql = "insert into ff_bat4f5_house (`WID`,`Type`,`RemoteID`,`Time`,`Community`,`Room`,`Square`,`Price`,`PofTime`,`RefTime`) values 
				                                    ('{$wid}','{$sort}','{$value['id']}','{$t}','{$value['com']}','{$value['room']}','{$value['square']}','{$value['price']}','{$value['potime']}','{$value['retime']}')";
				$this->db->exe($sql);
			}
		}
		$this->db->close();
		return TRUE;
	}
	
	/**
	 * 特殊情况下删除该id房源
	 * **/
	public function deletehouse($wid)
	{
	 	$this->db->connect($this->newfeedbackDB);
	 	$sql = "delete from ff_bat4f5_house where WID = {$wid}";
	 	$this->db->exe($sql);
	 	$this->db->close();
		return TRUE;
	}
	
	/**
	 * 获取库里的房源
	 * **/
	public function gethouse($wid , $data ,$sort)
	{
		$this->db->connect($this->newfeedbackDB);//192.168.0.161
		if($sort=='rent')
		{
			$sql2 = 'and Type = 3';
		}
		else
		{
			$sql2 = 'and Type = 6';
		}
		$renum = array();
		$t = time();
		$sql2 .= " order by RefTime";
		$sql = "select * from ff_bat4f5_house where WID = '{$wid}'  {$sql2}";
		$res = $this->db->rows($sql);
		foreach($res as $key=>$value)
		{
			if($value)
			{
				$time = strtotime($value['Time']);
				if(($t - $time) > 7200)//距离现在大于两小时的话？？？，为什么这样的呢？？？
				{
					//这是和count=30是一个双保险，如果没有大于30，但在这30次之间里，有一次距离库中存储的房源时间超过了
					//2小时，我们就认为，数据表里的房源失效了。需要重新去58获得房源列表。
					return array();
				}//比原始记录少了id,wid，time三个字段的信息，封装给$renum
				$renum[] = array('com'=>$value['Community'],'room'=>$value['Room'],'square'=>$value['Square'],'price'=>$value['Price'],'id'=>$value['RemoteID'],'retime'=>$value['RefTime'],'potime'=>$value['PofTime'],'type'=>$value['Type']);
			}
		}
		$this->db->close();
		return $renum;
	}
 	/**
	 * 过滤房源
	 * $value  即将要过滤的房源，每条房源都含有小区，面积，价格，发布时间，上次刷新时间等属性，过滤条件根据这些属性，过滤房源
	 * $sort 决定过滤的最小价格和最大价格的房源类型，租的最小最大，或者售的最小最大
	 * $data 封装了过滤条件，比如指定小区，指定房型，租金范围，如何排序（价格大小排序，发布时间排序）等
	 * **/
 	function filterre($value, $data='' ,$sort='')
	{
		
		if(empty($value))
			return array();
		/*新浪乐居的条件选房中，指定楼盘Start*/
		if(is_array($data['Community']) && !empty($data['Community']))
		{
			foreach($value as $key2=>$value2)
			{
				$rs = 1;
				if(is_array($value2))
				{
					foreach($data['Community'] as $key3=>$value3)//data中的小区是否在房源$value2中
					{
						if(preg_match("/{$value3}/isU",$value2['com'],$v))
						{
							$rs = 2;//属于指定小区的，就做个标记，留下来
							break;
						}
					}
				}
				if($rs == 1)//说明不是这次要刷新计划里的指定小区，那就过滤掉这条房源
					unset($value[$key2]);//在房源中找不到$data[community]中的值，就删除该条房源记录
			}
		}
		/*新浪乐居的条件选房中，指定楼盘End*/
		if(!empty($value))//过滤指定小区之外的房源后，如果还有房源，就进行下一个过滤条件
		{
			/*新浪乐居的条件选房中，指定房型Start*/
			if($data['RoomMin'] && $data['RoomMax'])
			{
				foreach($value as $key2=>$value2)
				{
					if($value2['room'] > $data['RoomMax'] || $value2['room'] < $data['RoomMin'])
						unset($value[$key2]);//不在指定房型的，就过滤掉
				}
			}
			/*新浪乐居的条件选房中，指定房型End*/
			/*新浪乐居的条件选房中，指定价格Start*/
			foreach($value as $key2=>$value2)
			{
				if(($value2['type'] == 3) && $data['RentMax'] && $data['RentMin'])//出租     指定价格
				{
					if($value2['price'] > $data['RentMax'] || $value2['price'] < $data['RentMin'])
						unset($value[$key2]);
				}
				elseif(($value2['type'] == 6) && $data['SaleMax'] && $data['SaleMin'])	//出售  指定价格
				{
					if($value2['price'] > $data['SaleMax'] || $value2['price'] < $data['SaleMin'])
						unset($value[$key2]);
				}
			}
			/*新浪乐居的条件选房中，指定价格End*/
			/*这里是不是和上一步指定价格的筛选重复了呢？？？Start*/
			if($sort == 'rent')
			{
				$totalMin = $data['RentMin'];//租金的下限
				$totalMax = $data['RentMax'];//租金的上限
				if($totalMin && $totalMax)
				{
					if(!empty($value))
					{
						foreach($value as $key2=>$value2)
						{
							if($value2['price'] > $totalMax || $value2['price'] < $totalMin)
								unset($value[$key2]);
						}
					}
				}
			}
			elseif($sort == 'sale')
			{
				$totalMin = $data['SaleMin'];
				$totalMax = $data['SaleMax'];
				if($totalMin && $totalMax)
				{
					if(!empty($value))
					{
						foreach($value as $key2=>$value2)
						{
							if($value2['price'] > $totalMax || $value2['price'] < $totalMin)
								unset($value[$key2]);
						}
					}
				}
			}
			/*这里是不是和上一步指定价格的筛选重复了呢？？？End*/
			if($data['Order'] == 'Post')//按发布时间排序
			//usort使用自定义的函数，对$value数组进行排序
				usort($value , "focus2post") ;//使用focus2post函数排序，用推送时间对数组中的值进行排序
			else
				usort($value , "focus2price") ;//使用focus2price函数来决定每条房源的排序
			if($data['Max'])//只刷新前面30条房源
			{//排序后，这里截取前X条房源
				$value = array_slice($value,0,$data['Max']);//返回所有房源的前30条     array_slice()数组
			}
		}//无论哪一次，都有进行排序（记住，retime字段的值是什么，第一次是刷新次数，第2,3，xxxx30次则是时间了。）
		usort($value , "focus2refresh") ;////使用focus2refresh函数来决定每条房源的排序，刷新时间从早到晚（把价格排序覆盖掉了）
		return $value;
	}
 	/**
	 * 获取认证房源列表
	 * **/
	public function gethouseIdentity($wid,$all_num)
	{
		$this->db->connect($this->newfeedbackDB);
		$sql = "select RemoteID,topic,Time from ff_bat4f5_house_identity where WID = {$wid} and Is_refresh = 0 order by ID asc limit {$all_num}";
		$res = $this->db->rows($sql);
		$data = array();
		foreach ($res as $key=>$value)
		{
			$data['id'][] = $value['RemoteID'];
			$data['topic'][$value['RemoteID']] = $value['topic'];
			$data['time'][] = $value['Time'];
			$this->db->exe("update ff_bat4f5_house_identity set Is_refresh=1 where WID = {$wid} and RemoteID in ({$value['RemoteID']}) limit 1");
		}
		if(empty($data))
			return FALSE;
		/*$RemoteID_arr = implode(',',$data['id']);
		$sql = "update ff_bat4f5_house_identity set Is_refresh=1 where WID = {$wid} and RemoteID in ({$RemoteID_arr}) limit {$all_num}";
		$this->db->exe($sql);*/
		$this->db->close();
		return $data;
	}
 	/**
	 * 认证房源入库
	 * **/
	public function posthouseIdentity($wid,$data)
	{
		$this->db->connect($this->newfeedbackDB);
		$sql = "delete from ff_bat4f5_house_identity where WID = {$wid}";
		$this->db->exe($sql);
		$t = date('Y-m-d H:i:s');
		foreach($data['id'] as $key=>$value)
		{
			$sql = "insert into ff_bat4f5_house_identity (`WID`,`RemoteID`,`topic`,`Time`) values ('{$wid}','{$value}','{$data['topic'][$key]}','{$t}')";
			$this->db->exe($sql);
		}
		$this->db->close();
		return TRUE;
	}
	/*认证房一键刷新房源入库*/
 	function batf5intoIdentity($UID,$SID , $WID , $Rent , $Sale , $Failure ,$Live , $Points , $Error, $topic,$PID)
	{
		if($this->newfeedbackDB['dbname'] == 'yufeng_db')
		{
			$this->newfeedbackDB['host'] = '192.168.0.160' ;
		}
		$this->db->connect($this->newfeedbackDB);
		$sql = "insert into `ff_bat4f5_timer_status_identity` (`UID`,`SID`,`WID`,`Time`,`Rent`,`Sale`,`Failure`,`Live`,`Points`,`Error`,`topic`,`PID`) values ('{$UID}','{$SID}','{$WID}',now(),'{$Rent}','{$Sale}','{$Failure}','{$Live}','{$Points}','{$Error}','{$topic}','{$PID}')";
		$this->db->exe($sql);
		$this->db->close();
		return true;
	}
}
function focus2post($a, $b)//推送时间排序，从最新（最早发布）到最旧
{
    if($a["potime"] < $b["potime"])
		return 1;
	if($a["potime"] > $b["potime"])
		return -1;
	return 0;
}
function focus2price($a, $b)//价格，从低到高排序
{
    if($a["price"] < $b["price"])
		return -1;
	if($a["price"] > $b["price"])
		return 1;
	return 0;
}
function focus2refresh($a, $b)//刷新时间，从早到晚
{
   if($a["retime"] < $b["retime"])//如果刷新时间远，就排前。
		return -1;//时间小的排前
	if($a["retime"] > $b["retime"])
		return 1;//排后
	return 0;
}
?>