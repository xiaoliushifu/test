<?php
class flashwebbasic{
 	function __construct($city , $company)
 	{
 		$dbInfo = getDbInfo($city , $company);
 		$this->db = new mydb() ;
 		foreach($dbInfo as $key=>$value)
		{
			$this->$key = $value;
		}
 	}
 	/*闪推房源抓取数据入表操作*/
	public function HouseAdd($wid,$sid,$uid,$data = array()) 
	{
		$conn = $this->db->connect($this->newfeedbackDB);
		if($conn == false)
			return false;
		if(empty($data))
			return false;
		/*删除原始数据*/
		$sql = "delete from `flash_houselist` WHERE `SID`='{$sid}' AND `UID`='{$uid}' AND `WID`='{$wid}' and `Falt`='Live'";
		$this->db->exe($sql);
		/*end*/
		/*插入新数据*/
		$tmpTime = date("Y-m-d H:i:s",time());
		foreach ($data as $k=>$v)
		{
			/*判断该房源是否正在进行推送*/
			$sql = "SELECT count(*) as count FROM `flash_houselist` WHERE `SID`='{$sid}' AND `UID`='{$uid}' and `WID`='{$wid}' and `RID`='{$v["RID"]}' and `Falt`='Lock' limit 1";
			$tmpdata = $this->db->row($sql);
			if(!empty($tmpdata['count']))
				continue;
			/*插入新房源*/
			$sql = "INSERT INTO `flash_houselist` (`RID`,`SID`,`UID`,`WID`,`Type`,`Title`,`Tags`,`Com`,`House_room`,`Square`,`Price`,`House_URL`,`Retime`,`Potime`,`SendTime`,`Time`,`Falt`) VALUES 
			('{$v["RID"]}','{$sid}','{$uid}','{$wid}','{$v["Type"]}','{$v["Title"]}','{$v["Tags"]}','{$v["Com"]}','{$v["House_room"]}','{$v["Square"]}','{$v["Price"]}','{$v["House_URL"]}','{$v["Retime"]}','{$v["Potime"]}','{$tmpTime}','{$tmpTime}','Live')"; 
			$this->db->exe($sql);
			/*end*/
		}
		/*end*/
		$this->db->close();
		return true;
	}
	/*进程 控制机制 只允许一个用户在操作一个网站时进行单独处理*/
	public function CheckProcess($uid ,$wid,$sid, $Action) 
	{
		$conn = $this->db->connect($this->cookieDB);
		if($conn == false)
			return false;
		/*删除 超时房源 以600秒为基准*/
		$timeout = time() - 600 ;
		$sql = "SELECT `ID` FROM push_process_v2 WHERE Time < '{$timeout}'";
		$tmpdata = $this->db->rows($sql);
		if(!empty($tmpdata['ID']))
			$this->db->exe(sprintf("delete from `push_process_v2` where ID in (%s) ",implode(",",$tmpdata['ID'])));
		/*end*/
		if($Action == 'isset')
		{
			$sql = "SELECT count(*) as count FROM push_process_v2 WHERE `UID`='{$uid}' and `WID`='{$wid}' limit 1";
			/*有进程在执行 */
			$tmpcount = $this->db->row($sql);
			if(!empty($tmpcount['count']))
			{
				$this->db->close();
				return false;
			}
			$time = time();
			$sql = "INSERT INTO `push_process_v2` (`Time`, `DID`, `UID`, `WID`, `Radom`, `Action`) VALUES ('{$time}', '', '{$uid}', '{$wid}', '', 'post')";
			$this->db->exe($sql);
		}elseif($Action == 'delete')
		{
			$this->db->exe("delete from `push_process_v2` where `UID`='{$uid}' and `WID`='{$wid}'");
		}
		$this->db->close();
		return true;
	}
	/*将该进程对应的房源数据 改变其状态*/
	public function TaskOver($uid,$wid,$sid,$data,$Action,$ret=array(),$errInfo='')
	{
		/*对多条房源的状态值进行更改*/
		if(empty($errInfo) && $Action != 'over')
		{
			if($Action == 'timeout')
				$errInfo = "发布失败，网络超时。"; 
			else
				$errInfo = "发布失败，进程错误。"; 
			foreach ($data as $key=>$value)
			{
				if($this->UpdateTaskStatus($uid,$wid,$sid,$value,$ret,$errInfo) == false)
					return false;
			}
		}else
		{
			if($this->UpdateTaskStatus($uid,$wid,$sid,$data,$ret,$errInfo) == false)
				return false;
		}
		return true;
	}
	/*房源状态更新操作--执行操作*/
	public function UpdateTaskStatus($uid,$wid,$sid,$data=array(),$ret=array(),$errInfo='')
	{
		$conn = $this->db->connect($this->newfeedbackDB);
		if($conn == false)
			return false;
		/*对应推送日志表*/
		$id = $data['ID']; 
		if(!empty($ret['id']) && !empty($ret['url']))
		{
			/*更新日志*/
			$this->db->exe("UPDATE `flash_houselog` SET `NRID`='{$ret['id']}',`Falt`='Success',`More`='{$errInfo}' WHERE `ID`='{$id}'");
			/*插入新数据 到 房源存储表*/
			$v = $data;
			$tmpTime = date("Y-m-d H:i:s",time());
			$sql = "INSERT INTO `flash_houselist` (`RID`,`SID`,`UID`,`WID`,`Type`,`Title`,`Tags`,`Com`,`House_room`,`Square`,`Price`,`House_URL`,`Retime`,`Potime`,`SendTime`,`Time`,`Falt`) VALUES 
			('{$ret['id']}','{$sid}','{$uid}','{$wid}','{$v["Type"]}','{$v["Title"]}','{$v["Tags"]}','{$v["Com"]}','{$v["House_room"]}','{$v["Square"]}','{$v["Price"]}','{$ret["url"]}','{$tmpTime}','{$tmpTime}','0000-00-00 00:00:00','{$tmpTime}','Live')"; 
			$this->db->exe($sql);
			/*end*/
			$Falt = 'Updel';
			/*将用户剩余点数对应表 减 1*/
			$this->db->exe("UPDATE `flash_housecount` SET `Count`=`Count`-1,`Time`='{$tmpTime}' WHERE `SID`='{$sid}' and `UID`='{$uid}' and `WID`='{$wid}' limit 1");
			/*end*/
		}else
		{
			/*更新日志*/
			$this->db->exe("UPDATE `flash_houselog` SET `Falt`='Error',`More`='{$errInfo}' WHERE `ID`='{$id}'");
			$Falt = 'Live';
		}
		/*更新老房源数据 --删除操作*/
		$this->db->exe("UPDATE `flash_houselist` SET `Falt`='{$Falt}' WHERE `ID`='{$data['LID']}' limit 1");
		$this->db->close();
		return true;
	}
	/*检查该房源是否是重复推送 暂时不用*/
	public function TaskCheck($uid,$wid,$sid,$data=array())
	{
		$conn = $this->db->connect($this->newfeedbackDB);
		if($conn == false)
			return false;
		/*判断该房源是否正在进行推送*/
		$sql = "SELECT count(*) as count FROM `flash_houselist` WHERE `UID`='{$uid}' and `WID`='{$wid}' and `RID`='{$data["RID"]}' and `Falt`='Lock' limit 1";
		$tmpdata = $this->db->row($sql);
		$this->db->close();
		if(!empty($tmpdata['count']))
			return false;
		return true;
	}
	/*检查该房源是否是重复推送 暂时不用*/
	public function HouseCountAdd($wid,$sid,$uid,$data=array(),$errInfo='',$act='')
	{
		$conn = $this->db->connect($this->newfeedbackDB);
		if($conn == false)
			return false;
		$tmpTime = date("Y-m-d H:i:s",time());
		$sql = "SELECT Time FROM `flash_housecount` WHERE `SID`='{$sid}' and `UID`='{$uid}' and `WID`='{$wid}' limit 1";
		$tmpdata = $this->db->row($sql);
		if(!empty($tmpdata['Time']))
		{
			/*更新用户获取点数的时间*/
			$this->db->exe("UPDATE `flash_housecount` SET `Time`='{$tmpTime}' WHERE `SID`='{$sid}' and `UID`='{$uid}' and `WID`='{$wid}' limit 1");
			/*end*/
			/*判断距离上次更新时间是否小于10秒*/
			if($act == 'check')
			{
				$nowtime = time();
				$tmptime = strtotime($tmpdata['Time']);
				$chatime = $nowtime-$tmptime;
				if($chatime <= 10)
				{
					$this->db->close();
					return false;
				}
			}else
			{
				/*更新房源点数*/
				$sql = "UPDATE `flash_housecount` SET `HouseCount`='{$data['all']}',`Count`='{$data['use']}',`HouseRentCount`='{$data['allR']}',`HouseSaleCount`='{$data['allS']}',`Time`='{$tmpTime}' WHERE `SID`='{$sid}' and `UID`='{$uid}' and `WID`='{$wid}' limit 1";
				$this->db->exe($sql);
			}
		}else
		{
			if($act != 'check')
			{
				/*判断该房源是否正在进行推送*/
				$sql = "INSERT INTO `flash_housecount`(`UID`, `SID`, `WID`, `HouseCount`, `Count`, `HouseRentCount`, `HouseSaleCount`, `Time`, `More`) VALUES 
				('{$uid}', '{$sid}', '{$wid}', '{$data['all']}', '{$data['use']}', '{$data['allR']}', '{$data['allS']}', '{$tmpTime}', '{$errInfo}')";
				$this->db->exe($sql);
			}
		}
		$this->db->close();
		return true;
	}
}
?>