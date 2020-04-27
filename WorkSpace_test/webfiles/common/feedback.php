<?php
ignore_user_abort(1);ob_start();ob_flush();flush();
/**
*
* @package feedback
* @descript 数据推送
*
* @example
*
*  e.g. 1
*
*	$o=new feedback();
*	$o	-> setAction('post_light')
*		-> setPushID(1256806764)
* 		-> setUserID(43)
* 		-> setSiteID(80)
* 		-> setDataID(508393)
* 		-> setDataType('new_sale')
*		-> setRemoteHref('http://www.zaobao.com/zg/zg091030_001.shtml')
*		-> setRemoteID(2)
*		-> setStatus(9)
* 		-> setPic(file_get_contents('../zf2000/upload/f.bmp'))
* 		-> exe();
*
*  e.g. 2
*
* 	$o=new feedback();
* 	$o->exe(array(
* 				'action'=>'post_light',
* 				'pushID'=>1256806764,
* 				'userID'=>4,
* 				'siteID'=>80,
* 				'dataID'=>508393,
* 				'dataType'=>'new_sale',
*				'remoteHref'=>'http://www.zaobao.com/zg/zg091030_001.shtml',
*				'remoteID'=>'091030541',
*				'status'=>9,
* 				'pic'=>file_get_contents('../zf2000/upload/f.bmp')
* 			));
*
*  e.g. 3
*
* 	$o=new feedback();
* 	$o->exe(array(
* 				'action'=>'post_light',
* 				'pushID'=>1256806764,
* 				'userID'=>4,
* 				'siteID'=>80,
* 				'dataID'=>508393,
* 				'dataType'=>'new_sale',
*				'status'=>6
*				'error'=>'null data'
* 			));
*
*
* @author Zeeeyooo
* @date 2009-06-10
* @version 2.0
*
*/







class feedback
{
	private static $connect=false;
	private static $connect2=false;
	private $sql=false;
	private $action='post_light';
	private $pushID=0;
	private $userID=0;
	private $siteID=0;
	private $dataID=0;
	private $dataType='';
	private $status;
	private $remoteHref='';
	private $remoteID='';
	private $error=array();
	private $pic='';
	private $registerUsername='';
	private $registerPassword='';
	private $conDB ;

	/**
	* 构造函数
	*
	* @param NULL
	*
	* @return NULL
	*/

	function __construct($db)
	{
		
		$this->error=array(
			'level'	=>0,
			'type'	=>'default',
			'msg'	=>'',
			'source'=>''
		);
		$this->conDB = $db ;
	}

	/**
	* 析构函数
	*
	* @param NULL
	*
	* @return NULL
	*/

	function __destruct()
	{
		if($this->error)
		{
			//error_log(time().' : '.print_r($this->error,1)."\n\n",3,'err'.date('Ymd').'.log');
		}
		$this->close();
	}
	/**
	*
	* setPushID
	*
	* 设置发送类型
	*
	* @param string $s
	*
	* @return this
	*/
	function setAction($s)
	{
		$this->action=$s;return $this;
	}
	/**
	*
	* setPushID
	*
	* 设置发送编号
	*
	* @param int $n
	*
	* @return this
	*/
	function setPushID($n)
	{
		$this->pushID=$n;return $this;
	}
	/**
	*
	* setPushID
	*
	* 设置用户编号
	*
	* @param int $n
	*
	* @return this
	*/
	function setUserID($n)
	{
		$this->userID=$n;return $this;
	}
	/**
	*
	* setSiteID
	*
	* 设置网站编号
	*
	* @param int $n
	*
	* @return this
	*/
	function setSiteID($n)
	{
		$this->siteID=$n;return $this;
	}
	/**
	*
	* setDataID
	*
	* 设置数据编号
	*
	* @param int $n
	*
	* @return this
	*/
	function setDataID($n)
	{
		$this->dataID=$n;return $this;
	}
	/**
	*
	* setDataType
	*
	* 设置数据类型
	*
	* @param string $s
	*
	* @return this
	*/
	function setDataType($s)
	{
		$this->dataType=$s;return $this;
	}
	/**
	*
	* setRemoteHref
	*
	* 设置提交成功后的地址
	*
	* @param string $n
	*
	* @return this
	*/
	function setRemoteHref($n)
	{
		$this->remoteHref=$n;return $this;
	}
	/**
	*
	* setRemoteID
	*
	* 设置提交成功后的编号
	*
	* @param string $n
	*
	* @return this
	*/
	function setRemoteID($n)
	{
		$this->remoteID=$n;return $this;
	}
	/**
	*
	* setPic
	*
	* 设置验证码数据
	*
	* @param binary $s
	*
	* @return this
	*/
	function setPic($s)
	{
		$this->pic=$s;return $this;
	}
	/**
	*
	* setError
	*
	* 设置错误数据
	*
	* @param array $rs
	*
	* @return this
	*/
	function setError($rs,$tp='default',$msg='',$source='')
	{
		if(is_array($rs))
		{
			$this->error=$rs;
		}else
		{
			$this->error=array(
			'level'	=>$rs,
			'type'	=>$tp,
			'msg'	=>$msg,
			'source'=>$source
			);
		}
		return $this;
	}
	/**
	*
	* setPic
	*
	* 设置状态
	*
	* @param int $n
	*
	*			1	待发送
	*			2	待验证
	*			3	验证中
	*			4	验证失败,再验证
	*			5	验证超过允许的次数
	*			6	超时
	*			9	成功
	*			0	失败
	*
	* @return this
	*/
	function setStatus($n)
	{
		$this->status=$n;return $this;
	}
	
	
	/**
	*
	* escape
	*
	* 过滤
	*
	* @param string $s
	*
	* @return string
	*/
	private function escape($s)
	{
		return $s?mysql_real_escape_string($s):'';
	}
	/**
	*
	* report
	*
	* 网站使用情况报告
	*
	* @param array $rs
	*
	* @return bool
	*/
	function trans(array $rs=array())
	{
		$this->testUID = $rs['userID'] ;
		if(!isset($rs['pushID']) || !isset($rs['userID']) || !isset($rs['dataType']))
		{
				throw new Exception('pushID/userID is null!');return false;
		}
		$this->conn2();
		$f=intval($rs['status']);
		$pid=intval($rs['pushID']);
		$uid=intval($rs['userID']);
		if($rs['dataType']>2)
		{
			if($f==9 && isset($rs['dataID']))
			{
				$tbl=$rs['dataType']==4?'ff_tmp_rent':'ff_tmp_second_sale';
				$tb2=$rs['dataType']==4?'mm_tmp_rent':'mm_tmp_second_sale';
				$id=$rs['dataID'];
				$this->query("UPDATE `ff_push_move` SET `IDs`=CONCAT(`IDs`,'$id,') WHERE `ID`=$pid AND `UID`=$uid" , 1);
				$set='';$set2='';
				foreach($rs['source'] as $k=>$v)
				{
					$set.=",`$k`='".$this->escape($v)."'";
					if($k == 'Payment' || $k == 'Type4Property2' || $k == 'Years' || $k == 'Content' ||$k == 'RentFlag' ||$k == 'MapX' ||$k == 'MapY' ||$k == 'Address' ||$k == 'ContactMail' ||$k == 'ContactQQ' ||$k == 'ContactMSN' ||$k == 'Floorplans' ||$k == 'PhotoInterior' ||$k == 'PhotoOutdoor')
						continue;
					$set2.=",`$k`='".$this->escape($v)."'";
				}
				//error_log("INSERT INTO `$tbl` SET `Tdate`=6 , `UID`={$rs['userID']}$set" , 3, 'ttranlog.log');
				$this->query("INSERT INTO `$tbl` SET `Tdate`=6 , `UID`={$rs['userID']}$set" , 1);
				$id = mysql_insert_id();
				$this->query("INSERT INTO `$tb2` SET `ID`={$id} , `Tdate`=6 , `UID`={$rs['userID']}$set2" , 1);
			}
		}
		//房源搬家会走这个分支
		else
		{
			//序列化，并转义
			$s=empty($rs['source'])?'':$this->escape(serialize($rs['source']));
			$this->query("UPDATE `ff_push_move` SET `Status`=$f,`Msg`='$s' WHERE `ID`=$pid AND `UID`=$uid" , 1);
		}
		$this->close2();
	}
	/**
	*
	* report
	*
	* 网站使用情况报告
	*
	* @param array $rs
	*
	* @return bool
	*/
	function report(array $rs=array())
	{
		$this->testUID = $rs['userID'] ;
		if(!isset($rs['Params']) || !isset($rs['Suggest']))
		{
				throw new Exception('Params is null!');return false;
		}
		$this->conn2();
		$q="INSERT INTO `ff_websites_report` SET `Date`=CURDATE(),`SID`=0,`Type`=%d,`UID`=%d,`WID`=%d,`Params`='%s',`Suggest`='%s'";
		$q=sprintf($q,intval($rs['Type']),intval($rs['userID']),intval($rs['siteID']),$this->escape($rs['Params']),$this->escape($rs['Suggest']));
		$this->query($q , 1);
		$this->close2();
		return true;
	}
	/**
	*
	* exe
	*
	* 改变状态
	*
	* @param array $rs
	*
	* @return bool
	*/
	public $testUID = "" ;
	function exe(array $rs=array())
	{
		try
		{
			$this->testUID = $rs['userID'] ;
			//刷新动作，直接返回true
			if($rs['action']=='refresh')
				return true;
			//两个连接，conn,conn2
			if($rs['action']=='click')//click代表点击量
			{
				$this->conn();//$connect
				$this->conn2();//$connect2
				//几个数据库表的操作
				//ff_notice，ff_websites_account，ff_click_anjuke,
				//ff_websites_stat,ff_push_logs
				$this->terminate($rs);
				return true;
			}
			//推送成功时，添加$rs['actionID']=1,$rs['dataTypeID']=3（租）或6（售）
			//创建$web对象失败时 为$rs添加 几个字段  registerUsername    registerPassword   error actionID
			//登录成功时添加registerUsername，registerPassword这两个属性，dataID=1，dataType=login,dataTypeID=9
			//登录失败时（账户密码错误等情况）添加registerUsername，registerPassword这两个属性，重置的字段   dataID=1，dataType=login,dataTypeID=9
			//推送失败时  添加remoteHref，remoteID，pic，registerUsername，registerPassword这几个属性
			$this->dataInit($rs);
			if(!$rs)//何种情况下执行这个分支呢？现在还没发现$rs为空的情况
			{
				throw new Exception('action || pushID || userID || siteID || dataType is null!');
			}

			$err='';
			//$web创建失败时和登录失败时，status=0.登录成功时为9      $rs['status']为空时，intval后返回0
			//登录失败（账户密码错误的情况)status=0
			$f=intval($rs['status']);
			$this->conn();//准备好数据库服务器的连接,$connect，不是裕丰时，host为192.168.0.161
			/*推送成功时，$f=9*/
			$post_result = $f;
			
			//删除操作，且为真实房源的情况
			if(($rs['action']=='delete') && isset($rs['real']) && ($rs['real'] == 1))// 
			{
				if(intval($rs['status']) == 0)
				{
					$this->sql="insert into ff_push_del_failure (`Action` , `Time` , `UID` , `SID` , `WID` , `Type` , `PID` , `Status` , `RemoteHref` , `RemoteID` , `Error`) Values (4 , {$rs['pushID']} , {$rs['userID']} , {$rs['wid']} , {$rs['siteID']} , {$rs['dataTypeID']} , {$rs['dataID']} , 0 , '".mysql_real_escape_string($rs['oldInfo']['RemoteHref'])."' , '".mysql_real_escape_string($rs['oldInfo']['RemoteID'])."' , '".mysql_real_escape_string($rs['error']['msg'])."')" ;
					
					$this->query() ;
				}
			}
			//删除操作，不为真实房源的情况
			if(($rs['action']=='delete') && isset($rs['real']) && ($rs['real'] == 0))// 
			{
				if(intval($rs['status']) == 0)
				{
					if(date(H) >=11 && date(H) <=12)
					{
						$this->sql="insert into ff_delete (`Action` , `Time` , `UID` , `SID` , `WID` , `Type` , `PID` , `Status` , `RemoteHref` , `RemoteID` , `Error`) Values (4 , {$rs['pushID']} , {$rs['userID']} , {$rs['wid']} , {$rs['siteID']} , {$rs['dataTypeID']} , {$rs['dataID']} , 0 , '".mysql_real_escape_string($rs['oldInfo']['RemoteHref'])."' , '".mysql_real_escape_string($rs['oldInfo']['RemoteID'])."' , '".mysql_real_escape_string($rs['error']['msg'])."')" ;
						$this->query() ;
					}
				}
			}
			
			/**************************************/
			//真实房源套餐，额外写数据库
			if(isset($rs['real']) && ($rs['real'] == 1) && ($f==9))
			{
				$this->keepEstate($rs);
			}
			/**************************************/
			//登录成功时：本次推送id_某网站账号id_1
			//登录失败时：本次推送id_某网站账号id_1
			//推送成功时：本次推送id_某网站账号id_房源id
			//创建$web失败时   本次推送id_某网站账号id_''
			$mapath="{$rs['pushID']}_{$rs['siteID']}_{$rs['dataID']}";//推送_账号_房源
			$set2='';//set2，准备封装数据库表ff_push_tmps中status和error两个字段的信息
			if($f==2)//验证码的情况       这是以前的程序，现在，推送时出现验证码的情况，已经直接在网站文件中处理了比如city58.php 
			{
				/**验证码**/
				if($rs['pic'])
				{
					//$set2 .=",`Pic`='1'";
					//$this->tempFile("$mapath.png",$rs['pic']);//在带有固定前缀的路径下，创建指定的文件名，把pic保存其中
				}
			}
			else
			{
				/**如果msg中有内容提示，在插入数据库表ff_push_tmps的字段Error前，进行某些字符的转义Start**/
				//创建$web失败时，error的msg中的内容为空，在本对象构造函数中可知
				//登录失败时，$rs['error']['msg'])是$this->errInfo的信息
				if(isset($rs['error']['msg']) && $rs['error']['msg'] )//'error'=> array('level' => 10 , 'type' => '登陆' , 'msg' => $web->errInfo , 'source' => $web->content) ,
				{
					$err=mysql_real_escape_string($rs['error']['msg']);//转义某些字符，如单引号等
				}
				/**如果msg中有内容提示，在插入数据库前，进行某些字符的转义End**/
				if($f!=9)
				{
					if($rs['action']=='delete')
					{
						//if(date(H) >=13 && date(H) <=14)
						//	$this->tempFile("$mapath.txt",print_r($rs,1));
					}else
					{
						if($rs['wid'] == 999)//这是啥？
							$this->tempFile("$mapath.txt",print_r($rs,1));
					}
				}
				//更新ff_push_logs表，
				//推送成功时，表的两个字段，send++，failure为空
				//登录失败时，什么也不做
				//登录成功时，什么也不做
				//创建$web对象失败时，更新160服务器上ff_push_logs表，failure++
				$this->terminate($rs);
				//推送成功时，会有remoteHref和remoteID字段
				//创建$web失败时，有这个字段，但该字段是空的
				//登录失败时，有此字段，但是为空
				if(isset($rs['remoteHref']) && $rs['remoteHref'])
				{
					$set2 .=',`RemoteHref`="'.mysql_real_escape_string($rs['remoteHref']).'",`RemoteID`="'.mysql_real_escape_string($rs['remoteID']).'"';
				}
			}
			//$testTime4 = time() ;
			//创建网站的类失败时，$set2="`Status`=0,`Error`=''"
			//登录成功时 ,很简单    $set2="`Status`=9,`Error`=''"
			//登录失败(账户密码错误的情况）时     $set2="`Status`=0,`Error`='$rs['error']['msg']'"
			//推送成功时，$set2="Status=9,Error=$rs['error']['msg'],RemoteHref=xxx,$remoteID=xxx"
			//有验证码时 $set2="Status=2,Error='',`Pic`='1'"
			$set2="`Status`=$f,`Error`='$err'$set2";

			$mapsID=0;
			if(isset($_POST['maps']))//maps是什么信息？？暂时没有用到，为空
			{
				$maps=unserialize($_POST['maps']);
				if(isset($maps[$mapath]))
					$mapsID=intval($maps[$mapath]);
			}
			/*准备sql语句Start*/
			if($mapsID)//有，则更新   Status和Error，只用ID字段限制
			{
				$this->sql="UPDATE `ff_push_tmps` SET $set2 WHERE `ID`=$mapsID";
			}
			else//无，还是更新，但使用Time,PID,UID,WID,Type做限制条件
			{
				//既然是登录成功，为什么要更新呢，什么时候insert过呢？？在前台，宋亚军负责的？？，而且，是不是不容易看到记录，因为，登录接下来就是推送，马上就更新了。
				//果然，宋亚军负责insert,status的默认值是1
		//"insert into ff_push_tmps (`time`,`UID`,`Type`,`Action`,`PID`,`SID`,`WID`,`RemoteHref`) values
		//($timeStamp,{$this->UID},9,7,1,{$v['wid']},{$v['id']},'0')"
				//登录成功时，$rs['dataID']=1,$rs['dataTypeID']=9
				//推送验证码的情况时
				//$创建$web对象失败时，
				$this->sql='UPDATE `ff_push_tmps` SET '.$set2.' WHERE `Time`='.intval($rs['pushID']).' AND `PID` ='.intval($rs['dataID']).' AND `UID` ='.intval($rs['userID']).' AND `WID` ='.intval($rs['siteID']).' AND `Type`='.intval($rs['dataTypeID']);
			}
			/*准备sql语句End*/
			if($rs['userID'] == 132936)//可以在这里做测试，单独测试该用户
			{
				//error_log($this->sql."\r\n".print_r($rs , true)."\r\n***********\r\n" , 3 , 'newsoufu.log') ;
			}
			//开始更新表
			$i=60;
			while($i--)//60次机会
			{
				if($f=$this->query())//执行sql语句
					break;
				sleep(2);//失败后休息2秒
				$this->conn();//保持连接
			}
			/*
			 * 统计各网站推送时间
			 * 只对新浪、搜房、赶集、安居客、58、焦点进行时间统计
			*/
			//推送成功时，插入表ff_websites_time_statistics中一条记录
			if(($rs['action']=='post_light') && ($post_result==9))
			{
				if($rs['wid'] == '220' || $rs['wid'] == '119')//搜房和？？？
				{
					if($this->conDB['dbname'] == 'test')//192.168.0.161的test数据库时
					{
						$time_now = date("Y-m-d H:i:s");
						$pushID = date("Y-m-d H:i:s",$rs['pushID']);
						$TimeT = time()-$rs['pushID'];//pushID源头在哪？？？
						$this->sql="insert into ff_websites_time_statistics (`Uid` , `Wid` , `Sid` , `TimeT` , `TimeT1` , `TimeHouse` , `TimeStart` , `TimeEnd` , `HouseID`) Values ('{$rs['userID']}' , '{$rs['siteID']}' , '{$rs['wid']}' , '{$TimeT}' , '0' , '{$pushID}' , '0' , '{$time_now}' , '{$rs['dataID']}')" ;
						$this->query();
					}
					//error_log(print_r($this->conDB,true).print_r($this->sql,true)."\r\n" , 3 , 'ceshifeedback.log') ;
				}
			}
			$this->close();//断开连接$connect
			return $f;//$f在$this->query()中返回bool
		}
		catch(Exception $e)
		{
			$this->err($e);//有异常，记入log
		}
		$this->close();//再次断开连接$connect
		return false;
	}
	
	/**
	*
	* keepEstate
	*
	* 真实房源套餐，记录状态
	*
	* @param array $rs
	*
	* @return null
	*/
	private function keepEstate(array &$rs)
	{
		if('post_light'==$rs['action'])	//推送
		{
			$this->sql = "UPDATE `ff_push_real_estate` SET `RemoteHref` =  '".mysql_real_escape_string($rs['remoteHref'])."' , `RemoteID` = '".mysql_real_escape_string($rs['remoteID'])."' , `nInsert` = `nInsert`+1 , `Time` = '".time()."' WHERE `UID` = {$rs['userID']} AND `SID` = {$rs['wid']} AND `WID` = {$rs['siteID']} AND `Type` = {$rs['dataTypeID']} AND `PID` = {$rs['dataID']} LIMIT 1" ;
			$this->query() ;
			if(mysql_affected_rows(self::$connect) <= 0)
			{
				$this->sql="insert into ff_push_real_estate (`UID` , `SID` , `WID` , `Type` , `PID` , `RemoteHref` , `RemoteID` , `Time`) Values ({$rs['userID']} , {$rs['wid']} , {$rs['siteID']} , {$rs['dataTypeID']} , {$rs['dataID']} , '".mysql_real_escape_string($rs['remoteHref'])."' , '".mysql_real_escape_string($rs['remoteID'])."' , '".time()."')" ;
				$this->query() ;
			}
		}
		elseif('update'==$rs['action'])	//更新
		{
			$this->sql = "UPDATE `ff_push_real_estate` SET `RemoteHref` =  '".mysql_real_escape_string($rs['remoteHref'])."' , `RemoteID` = '".mysql_real_escape_string($rs['remoteID'])."' , `nUpdate` = `nUpdate`+1 , `nToday` = `nToday`+1 , `Time` = '".time()."' WHERE `UID` = {$rs['userID']} AND `SID` = {$rs['wid']} AND `WID` = {$rs['siteID']} AND `Type` = {$rs['dataTypeID']} AND `PID` = {$rs['dataID']} LIMIT 1" ;
			$this->query() ;
		}
		elseif('delete'==$rs['action'])	//删除
		{
			$this->sql = "UPDATE `ff_push_real_estate` SET `RemoteHref` =  '' , `RemoteID` = '' , `nDelete` = `nDelete`+1 , `Time` = '".time()."' WHERE `UID` = {$rs['userID']} AND `SID` = {$rs['wid']} AND `WID` = {$rs['siteID']} AND `Type` = {$rs['dataTypeID']} AND `PID` = {$rs['dataID']} LIMIT 1" ;
			$this->query() ;
		}
	}
	
	
	/**
	*
	* terminate
	*
	* 完成一次操作,记录成功/失败
	*
	* @param array $rs
	*
	* @return null
	*/
	private function terminate(array &$rs)
	{
		$f	=intval($rs['status']);//失败： 0
		$tm	=intval($rs['pushID']);
		$uid=intval($rs['userID']);
		$wid=intval($rs['siteID']);
		if('login'==$rs['action'])//登录的情况
		{
			if($rs['excute'] == 'loginTest')
			{
				$hot = intval($rs['hot']) ;
				$this->conn2();
				$this->sql="UPDATE `ff_websites_account` SET `hot`='$hot' WHERE `time`=$tm AND `uid`=$uid AND `wid`=$wid";
				$this->query(false , 1);
//				$this->close2();
				return ;
			}
			else
			{
				return ;
			}
			
		}
		elseif('click'==$rs['action'])//点击的情况
		{
			$hot = intval($rs['hot']) ;
			if($hot == 9)
			{
				$tt = date('Y-m-d',strtotime('yesterday')); 
				foreach ($rs['ret'] as $v)
				{
					if(is_array($v))
					{
						if($rs['siteID'] == 3366)
						{
							$this->sql = "select click from ff_click_anjuke where WID = '{$rs['ret']['wid']}' and RemoteID = '{$v['RemoteID']}'";
							$t = $this->row();
							if($t)//存在 点击量减去昨天的，并把点击量改成最新的  否则入库
							{
								$click = $v['ClickToday'];
								$v['ClickToday'] -= $t['click'];
								if($v['ClickToday'] <= 0)
									continue;
								$this->sql = "update ff_click_anjuke set click = '{$click}' where WID = '{$rs['ret']['wid']}' and RemoteID = '{$v['RemoteID']}'";
								$this->query();
							}
							else
							{
								$this->sql = "insert into ff_click_anjuke (WID,RemoteID,click)values('{$rs['ret']['wid']}','{$v['RemoteID']}','{$v['ClickToday']}')";
								$this->query();
							}
						}
						$this->sql="insert into  `ff_websites_click2` (`UID` ,`UID2` , `SID` , `WID` ,`WID2` , `Community`, `Price` , `Square` ,`Room` ,`RemoteID` ,`ClickToday` , `ClickWeek` , `ClickMonth` ,`Type` ,`Date` , `Companyid`) values ('{$rs['userID']}' , '{$rs['userID2']}' , '{$rs['siteID']}', '{$rs['ret']['wid']}', '{$rs['ret']['wid2']}', '{$v['Community']}','{$v['Price']}', '{$v['Square']}','{$v['Room']}', '{$v['RemoteID']}', '{$v['ClickToday']}',  '{$v['ClickWeek']}', '{$v['ClickMonth']}', '{$v['Type']}' ,'{$tt}' , '{$rs['ret']['Companyid']}')";
						//error_log($this->sql."\r\n" , 3 , 'ffccc.log');
						$this->query();
					}
				}
				if($rs['ret']['ClickSum'] >= 0)
				{
					$this->sql="insert into  `ff_websites_stat` ( `UID` ,`UID2` , `SID` , `WID` ,`WID2` ,`Date` ,`F5` ,`ClickSum`) values ('{$rs['userID']}' ,'{$rs['userID2']}' , '{$rs['siteID']}', '{$rs['ret']['wid']}', '{$rs['ret']['wid2']}', '{$tt}' , '{$rs['ret']['isF5']}' , '{$rs['ret']['ClickSum']}')";
					$this->query();
				}
				/*暂时停止该功能的使用*/
				if($rs['userID'] && ($rs['ret']['notF5'] || $rs['ret']['notNum']) && 1==2)
				{
					$webarray = array('119'=>'搜房帮','220'=>'新浪乐居','206'=>'赶集付费','218'=>'58付费','236'=>'搜狐焦点','336'=>'安居客');
					$name = $webarray[$rs['siteID']];
					$data0 = date('Y-m-d').' 00:00:00';
					$data2 = date('Y-m-d').' 23:59:59';
					$time = date('Y-m-d H:i:s');
					if($rs['ret']['notF5'])
					{
						$content = '【推推99温馨提示】您的'.$name.'昨天使用了'.$rs['ret']['isF5'].'点,没有用完,推荐使用一键刷新的定时刷光功能。';
						$this->sql="insert into  `ff_notice` ( `UID2` ,`Type` , `Method` , `Close` ,`Case` ,`Topic` ,`Memo` ,`Date0`,`Date2`,`Time0`,`Time2`,`Time`,`Flag`) values ('{$rs['userID']}' ,'0' , '2', '0', '2', '{$content}' , '' , '{$data0}', '{$data2}','00:00:00','00:00:00','{$time}','live')";
						//error_log($this->conDB['dbname'].$this->sql."\r\n" , 3 , 'ffccc.log');
						$this->query('',1);
					}
					if($rs['ret']['notNum'])
					{
						$content = '【推推99温馨提示】您的'.$name.'上架房源只有'.$rs['ret']['notNum'].'条,请尽快登录后台上架哦。';
						$this->sql="insert into  `ff_notice` ( `UID2` ,`Type` , `Method` , `Close` ,`Case` ,`Topic` ,`Memo` ,`Date0`,`Date2`,`Time0`,`Time2`,`Time`,`Flag`) values ('{$rs['userID']}' ,'0' , '2', '0', '2', '{$content}' , '' , '{$data0}', '{$data2}','00:00:00','00:00:00','{$time}','live')";
						//error_log($this->conDB['dbname'].$this->sql."\r\n" , 3 , 'ffccc.log');
						$this->query('',1);
					}
				}
				return true;
			}
			else
			{
				return true;
			}
			//	$this->sql="UPDATE `ff_websites_account` SET `hot`='$hot' WHERE `id`={$rs['ret']['wid']} AND `uid`=$uid AND `wid`=$wid";
		}elseif('register'==$rs['action'])//注册的情况
		{
			$pwd=mysql_real_escape_string($rs['registerPassword']);
			$user=mysql_real_escape_string($rs['registerUsername']);
			if($f == 9)	//成功
			{
				$hot = intval($rs['hot']) ;	//判断状态
				if($hot == '')
					$hot = 9 ;
				$this->sql="UPDATE `ff_websites_account` SET `userName`='$user',`userKey`='$pwd',`hot`='$hot' WHERE `time`=$tm AND `uid`=$uid AND `wid`=$wid AND `userName`=''";
			}
			else
			{
				$this->sql="UPDATE `ff_websites_account` SET `userName`='$user',`userKey`='$pwd',`hot`='0' WHERE `time`=$tm AND `uid`=$uid AND `wid`=$wid AND `userName`=''";
			}
			//error_log($this->sql."\r\n" , 3 , 'register1.log') ;
			$this->conn2();
			$this->query(false , 1);
			return true ;
			//$this->close2();
		}
		else/** post_light,insert,update,upd,refresh,delete **///其他的情况
		{
			//只有等于9的情况，$failure才为''，其他情况Failure++
			$failure=9==$f?'':',`Failure`=`Failure`+1';
			$this->sql="UPDATE `ff_push_logs` SET `Send`=`Send`+1$failure WHERE `UID`=$uid AND `PID`=$tm";
			//if('delete'==$rs['action'])
			//	error_log($this->sql."\r\n" , 3 , '/124/debugLog/testQuery3.log') ;
		}
		$this->query();
	}
	/**
	*
	* dataInit
	*
	* 整理数据
	*
	* @param array $rs
	*
	* @return array
	*/
	private function dataInit(array &$rs)
	{
		$fs=array('action','pushID','userID','siteID','dataID','dataType','status','remoteHref','remoteID','pic','registerUsername','registerPassword','error');
		foreach($fs as $v)
		{
			if(!isset($rs[$v]))
			//没有相关属性的，就把属性值填入到$rs数组中，
			//action默认是post_light,pushID，userID,siteID，dataID默认都是0
			//dataType，status,remoteHref,remoteID，pic都是空
				$rs[$v]=$this->{$v};//添加某些字段,其中error是个数组的属性，一般会添加registerUsername,及registerPassword这两个字段
		}

		$tbl=array(
			//'new_buy'	=>1,
			'new'	=>1,
			'new_sale'	=>2,
			'rent'		=>3,//租为3
			'renter'	=>4,
			'second_buy'=>5,
			'second_sale'=>6,//二手房6
			'register'	=>8,
			'login'		=>9);
		$act=array(
			'register'	=>12,
			'loginTest'	=>11,
			'login'		=>10,
			'post_light'		=>1,
			'insert'	=>1,
			'update'	=>2,
			'upd'		=>2,
			'refresh'	=>3,
			'refersh'	=>3,
			'delete'	=>4,
			'del'		=>4);

		//是否有$act中的动作，通过这里可以控制哪些动作可以进行
		if(	empty($act[$rs['action']])	||
			//没有推送id
			empty($rs['pushID'])		||
			//没有用户id
			empty($rs['userID'])		||
			//没有网站账号
			empty($rs['siteID']))
		{
			return array();//有一个是空值或者0，就返回
		}
		//添加一个字段
		$rs['actionID']=$act[$rs['action']];//(login,10)(post_light,1)

		if('login'==$rs['action'])//登录时，重置某些的字段的值
		{
			$rs['dataID']=1;//房源的id
			$rs['dataType']='login';//rent,second_sale要变换
			$rs['dataTypeID']=9;//新添加的字段
		}
		elseif('register'==$rs['action'])//注册时，重置某些字段的值
		{
			$rs['dataID']=1;
			$rs['dataType']='register';
			$rs['dataTypeID']=8;
		}
		else//推送成功时，添加dataTypeID字段，租3售6
		{
			if(empty($tbl[$rs['dataType']]))//第一次时，应该是空的情况，所以返回空数组
				return array();//创建$web对象失败时，在此返回
			$rs['dataTypeID']=$tbl[$rs['dataType']];//否则，添加新的字段，并赋值(rent,3)(second_sale,6)
		}
		return $rs;
	}
	/**
	*
	* conn
	*
	* 连接数据库
	*
	* @param null
	*
	* @return bool
	*/
	private function conn()
	{
		if(self::$connect)
		{	//Ping 一个服务器连接，如果没有连接则重新连接,常用于空闲很久的脚本
			//self:代表当前类的指针，一般使用在类属性中，即所有该类的对象共享这一个属性
			if(mysql_ping(self::$connect))
			{
				return;
			}
			else
			{
				mysql_close(self::$connect);
			}
		}
		$rs=$this->conDB ;//执行构造函数时确定，为feedbackDB
		if($rs['dbname'] != 'yufeng_db')
			$rs['host'] = '192.168.0.161' ;//由160改为161
		if(!is_array($rs) || empty($rs['host']))
			die('can not find database config vars!');

		try
		{
			if(!self::$connect=mysql_connect('localhost','root','root'))//尝试连接161服务器（不是裕丰的情况）
			{
				throw new Exception('unable to connect database!'.mysql_error());
			}
			if(!mysql_select_db('test',self::$connect))//尝试选择数据库
			{
				throw new Exception('unable to select database!');
			}
		}
		catch(Exception $e)
		{
			$this->err($e);//把异常写入日志
		}
	}
	
	
	private function conn2()
	{
		if(self::$connect2)
		{
			if(mysql_ping(self::$connect2))
			{
				return;
			}
			else
			{
				mysql_close(self::$connect2);
			}
		}
		$rs=$this->conDB ;//160
		if(!is_array($rs)||empty($rs['host']))
			die('can not find database config vars!');

		try
		{
			//if(!self::$connect2=mysql_connect($rs['host'],$rs['user'],$rs['password']))
			if(!self::$connect2=mysql_connect('localhost','root','root'))
			{
				throw new Exception('unable to connect database!'.mysql_error());
			}
			//if(!mysql_select_db($rs['dbname'],self::$connect2))
			if(!mysql_select_db('test',self::$connect2))
			{
				throw new Exception('unable to select database2!');
			}
		}
		catch(Exception $e)
		{
			$this->err($e);
		}
	}
	/**
	*
	* conn
	*
	* 连接数据库
	*
	* @param null
	*
	* @return bool
	*/
	private function close()
	{
		if(self::$connect)
		{
			mysql_close(self::$connect);
			self::$connect=false;
		}
	}
	
	private function close2()
	{
		if(self::$connect2)
		{
			mysql_close(self::$connect2);self::$connect2=false;
		}
	}

	/**
	*
	* query
	*
	* 查询
	*
	* @param null
	*
	* @return resource
	*/
	private function query($sql=false , $conPara = 0)
	{
		try
		{
			if($conPara == 0)
			{
				$this->rs=mysql_query($sql?$sql:$this->sql,self::$connect) ;//优先使用$connect
			}
			else
			{
				$this->rs=mysql_query($sql?$sql:$this->sql,self::$connect2) ;
			}
			if($this->rs)
			{
				return true;
			}
			else
			{
			//	error_log($this->sql."\r\n" , 3 , '/124/debugLog/testQuery.log') ;
				throw new Exception(mysql_error());
			}
		}
		catch(Exception $e)
		{
			//throw $e;
			return false ;
		}
	}

	/**
	*
	* rows
	*
	* 获取查询结果集
	*
	* @param null
	*
	* @return array
	*/
	private function rows()
	{
		$re=array();$this->query();
		if($this->rs)
		{
			while($re[]=mysql_fetch_array($this->rs,MYSQL_ASSOC));
		}
		return $re;
	}

	/**
	*
	* rows
	*
	* 获取查询结果集的第一条记录
	*
	* @param null
	*
	* @return array
	*/
	private function row()
	{
		$rs=$this->rows();return $rs?$rs[0]:$rs;
	}

	/**
	*
	* err
	*
	* 错误处理
	*
	* @param Exception $e
	*
	* @return null
	*/
	private function err(Exception $e)
	{
		$msg="\n\n".date("H:i:s").$e->getMessage()."\n\n".$e->getTraceAsString()."\n\n".$this->sql;
		$path='/124/debugLog/'.date('Ym/d\e/Hi').'.log';if(!file_exists($path))$this->mkFolder(dirname($path));;error_log($msg,3,$path);
	}
	/**
	*
	* tempFile
	* @param string $path
	* @param string $memo
	* @describe 保存临时文件
	* @update 2009-06-15
	* @return null
	*
	*/
	private function tempFile($path,$memo)
	{		
		$dir=date("Ym/d/");//   201406/06/
		if(!file_exists("/124/debugLog"))//如果这个前缀不存在，就算了吧
		{
			return ;
		}
		$dir2="/124/debugLog/".$dir;
		if(!file_exists($dir2))
		$this->mkFolder($dir);
		file_put_contents($dir2.$path,$memo);
	}
	/**
	*
	* @param string $s
	* @describe 新建文件夹
	* @update 2009-06-15
	* @return null
	*
	*/
	private function mkFolder($s)
	{
		$root='/124/debugLog/';
		$s=str_replace('\\','/',$s);//    201406/06/
		try
		{
			if(strpos($s,'/')!==false)
			{
				$tmp=explode('/',$s.'/');//有可能拆分成  201406， 06，  空， 空；四个元素的数组
				$path=$tmp[0];//把先把第一个元素201406添加到path中
				$len=count($tmp);
				
				for($i=1;$i<$len;$i++)
				{
					if(!file_exists($root.$path))//不存在这个路径的话
					{
						if(!@mkdir($root.$path,0777))// 以 0777的权限创建文件夹   一层一层的创建
							throw new Exception("$path conn't create !");
					}
					if(!$tmp[$i])
						continue;
					$path .='/'.$tmp[$i];//再依次添加到该path中
				}
			}
			else
			{
				if(!@mkdir($root.$s,0777))throw new Exception("$s conn't create !");
			}
		}
		catch(Exception $e)
		{
			$this->err($e);
		}
	}

}

function stop($o){xmp($o);exit;}function xmp($s)
{
	if(is_bool($s)){$s='bool('.($s?'true':'false').')';}else{$s=print_r($s,1);}echo "<'\"'><xmp>\n\n$s</xmp>";
}