<?php

/**
*
* @package webpost
* @descript 网站信息推送的基础模块
* @author xiaodong 
* @copyright Copyright (c) 2009, shenyingchengxun
* @date 2009-10-21 
* @version 0.2
* logs 把上传图片的接口从图片的文件名矩阵改为上传数据对象。2009-11-2 by 晓东
*/


/**
*
* @package webpost
* @descript 网站信息推送接口
* @author xiaodong 
* @copyright Copyright (c) 2009, shenyingchengxun
* @date 2009-10-21
* @version 0.1
*/
interface webInterface {
	/**
	* login
	*
	* 登陆：根据site数组的内容,完成登陆登陆过程
	* @example $ret=$this->login($site); 直接登陆或获取验证码图片
	* 		   if ($ret=0)
	* 				echo 'false';	
	* 		   else if ($ret=1)
	* 				echo 'success';
	* 		   else
	* 				$img=$ret;
	* 
	*		   ret=login($site,'d1234'); 直接登陆或获取验证码图片
	* 		   if ($ret=0)
	* 				echo 'false';	
	* 		   else if ($ret=1)
	* 				echo 'success';
	* 		   else
	* 				$img=$ret;
	* @param array $site 主要是用里面的用户名密码
	* 		 string $code 可选，有验证码时输入的结果 
	*
	* @return 0:错误/1:成功/string codeimg验证码图片
	*/
	Public function login();  
	/**
	* logout
	*
	* 注销用户，需要用到之前的cookies
	* @example $ret=$this->logout(); 直接注销
	* 		   if ($ret=0)
	* 				echo 'false';
	* 		   else	
	* 		   		echo 'success';
	* 
	* @param NULL 
	*
	* @return 0:错误/1:成功
	*/	
	Public function logout();  
	/**
	* setup
	*
	* 设置网站发送过程中用到的基本数据
	* @example 
	* 		   
	* 
	* @param 	$sessionid; //内部调用的会话ID
	*			$data;      //需要发送的数据的array
	*			$site;	    //目标网站上的用户名密码等信息
	*			$user;      //本地网站的用户信息
	*			$stage;     //一条数据发送的会话的状态，------------------------持久化后需要延续使用
	*				        //1：第一次，2：登陆需要验证码，3：登陆成功 ， 4： 发送需要验证码，5：任务失败 6：任务成功
	* @return 0:错误/1:成功
	*/	
	Public function setup($sessionid='',array $data=array(),array $site=array(),array $user=array(),array $history=array());
	/**
	* getform
	*
	* 获得某个数据的发送页面，和其中的验证码图片（）
	* @example  $ret=$this->getform();
	* 		   
	* 
	* @param 	$this->data; //需要发送的数据的array
	*			$this->site; //目标网站上的用户名密码等信息
	*			$this->cookies //当前登陆后的cookies
	* @return 0：失败/array (string 'content'=>''表单内容, string 'codeimg'=>''图片流)：成功
	*/	
	Public function refresh();

	/**
	* postform
	*
	* 发送数据函数，内部包含了格转和上传附件及图片的功能,并返回上传数据的绝对URL或验证码图片 
	* @example  $ret=$this->postform('d234');
	* @param 	$code 可选参数如果有验证码提供验证码
	* 			下面的参数是setup完成的
	* 			$this->sessionid; //内部调用的会话ID
	*			$this->data;      //需要发送的数据的array
	*			$this->site;	  //目标网站上的用户名密码等信息
	*			$this->user;      //本地网站的用户信息
	*			$this->stage;     //一条数据发送的会话的状态，------------------------持久化后需要延续使用
	*				        	  //1：第一次，2：登陆需要验证码，3：登陆成功 ， 4： 发送需要验证码，5：任务失败 6：任务成功
	*			$this->cookies    //当前登陆后的cookies
	* @return 0 失败/ array ( string 'url'=>''发送后数据在目标网站上的URL; 
	* 						 string 'codeimg'=>''中间过程的验证码图片);
	* 						 string 'dataid'=>''删除数据用的id或url;
	*/	
	Public function postform();
	/**
	* updateform
	*
	* 更新数据函数，内部包含了格转和上传附件及图片的功能,并返回上传数据的绝对URL或验证码图片 
	* @example  $ret=$this->updateform('1231515235235');
	* 		   
	* 
	* @param 	$code 可选参数如果有验证码提供验证码
	* 			下面的参数是setup完成的(更新的数据放在$data中
	* 			$this->sessionid; //内部调用的会话ID
	*			$this->data;      //需要更新的数据的array
	*			$this->site;	    //目标网站上的用户名密码等信息
	*			$this->user;      //本地网站的用户信息
	*			$this->stage;     //一条数据发送的会话的状态，------------------------持久化后需要延续使用
	*				        //1：第一次，2：登陆需要验证码，3：登陆成功 ， 4： 发送需要验证码，5：任务失败 6：任务成功
	*			$this->cookies //当前登陆后的cookies
	* @return 0 失败/ array ( string 'url'=>''发送后数据在目标网站上的URL, string 'codeimg'=>''中间过程的验证码图片);
	*/	
	Public function updateform();
	/**
	* refresh
	*
	* 刷新数据函数，模拟用户点击数据手动刷新按钮 
	* @example  $ret=$this->refresh('1231515235235');
	* 		   
	* 
	* @param 	$code 可选参数如果有验证码提供验证码
	* 			下面的参数是setup完成的(更新的数据放在$data中
	* 			$this->sessionid; //内部调用的会话ID
	*			$this->site;	    //目标网站上的用户名密码等信息
	*			$this->stage;     //一条数据发送的会话的状态，------------------------持久化后需要延续使用
	*				        //1：第一次，2：登陆需要验证码，3：登陆成功 ， 4： 发送需要验证码，5：任务失败 6：任务成功
	*			$this->cookies //当前登陆后的cookies
	* @return 0 失败/ 1成功刷新/string codeimg中间过程的验证码图片;
	*/	

	
	/**	
	* register
	*
	* 删除数据函数，模拟用户点击数据删除 
	* @example  $ret=$this->register();
	* 		   
	* 
	* @param	$this->site;	    //目标网站上的用户名密码等信息
	* 			$this->user;	
	*			$this->cookies //当前登陆后的cookies
	*			$this->code;
	* @return 0 失败/ array('username'=>'','passwd'=>'');
	*/	
	
	
	Public function register();
	/**
	* delete
	*
	* 删除数据函数，模拟用户点击数据删除 
	* @example  $ret=$this->delete();
	* 		   
	* 
	* @param	$this->site;	    //目标网站上的用户名密码等信息
	*			$this->cookies //当前登陆后的cookies
	* @return 0 失败/ 1成功刷新/string codeimg中间过程的验证码图片;
	*/		
	Public function delete();
} 


/**
*
* @package webpost
* @descript 网站信息推送的抽象类实现了公共的方法和公共属性
*
*
*/
abstract class website implements webInterface {

	Public $sessionid; //内部调用的会话ID
	Public $data;      //需要发送的数据的array,这个array是2维数组，可以支持多个记录的发送
	Public $cdata;     //小区信息，从小区库中读出的，预留；
	Public $dataid;    //当前正在处理的$data中的id 0-n-------持久化后需要延续使用
	Public $history;   //数组，推送历史信息,history的ID为DataID
	Public $site;	   //数组，目标网站上的用户名密码等信息
	public $siteid;	   //当前正发送到那个网站
	Public $user;      //本地网站的用户信息
	Public $stage;     //数据发送的会话的状态，是一个数组id和siteid关联------------------------持久化后需要延续使用
   //1：还未登陆，2：登陆需要验证码，3：登陆成功 ， 4： 发送需要验证码，5：本条发送成功   6：全部发送完成 
	Public $ret;       //发送过程的返回结果array包括(url,webdataid,codeimg)；
	Public $webcookies;//过程的cookies-----------------------持久化后需要延续使用
	Public $content;   //过程中的临时页面或目标网站的返回内容
	public $code;      //验证码返回值，有数据则说明是有验证码
	Public $tmpfield;  //已准备好待发送的数组，使用前要替换其中的关键字持久化后需要延续使用（等待最后验证码）       什么是持久化？
	Public $patten;	   //网站的模板array，
	public $action;	   //网站当前的行为，post_light，login，register
	Public $log;       //过程的日志记录，---------------------持久化后需要延续使用，过程的记录用于调试
	Public $imgDomain=''; //图片服务器的名字
	Public $checkURL='http://192.168.0.125/cgi-bin/checkcode' ;	//验证码识别程序的路径
	Public $refer='' ;
	Public $picPath = '' ;
	Public $errInfo = '' ;
	Public $webPostError = array() ;
	Public $proxyNumber = 3 ;
	Public $type;
	Public $time;
	Public $excuteTime;
	Public $cookieDB = array() ;
	Public $feedbackDB = array() ;
	Public $remoteDB = array() ;
	
	
	function __construct($city , $company)
	{
		//获取数据库配置
//		$dbInfo = getDbInfo($city , $company);//这个函数在libs中，怎么没有找到引入语句呢？
//		include_once './webfiles/common/mysql.php';
//		$this->db = new mydb() ;//任何继承该类的网站实例都有一个mysql的对象存在与该实例的db属性中
//		//任何继承该类的网站实例，都准备好了四个数据库的信息，$this->cookieDB等四个
//		foreach($dbInfo as $key=>$value)
//		{
//			$this->$key = $value;
//		}
	}
	
	
	
	
	/**
	* setup
	*
	* 作为接口将外部的调用参数写入属性同时根据状态确定属性的值是重载还是新数据。
	*
	* @since v0.1
	*
	* @example 
	*
	* @todo 
	* 
	* @param string $sessionid 详见抽象类的参数说明
	* 		 array $data
	* 		 array $site
	* 		 array $user
	* 		 $stage
	* 
	*
	* @return TRUE/FALSE
	*///初始化一些属性，或者全局变量
	function setup($sessionid='',array $data=array(),array $site=array(),array $user=array(),array $history=array())
	{
		$this->sessionid=$sessionid;			//实参$data['id']是sessionid吗?
		$this->data=$data;		//房源数组，二维的
		$this->cdata='';
		$this->site=$site;		//登录目标网站的用户名，密码等信息，二维数组
		$this->user=$user;		//用户信息，一维数组
		$this->history=$history;	//上次推送的信息，好像也是二维的吧？
		$this->dataid=0;
		$this->siteid=0;
		$this->log="";
		$this->webcookies='';
		$this->content='';
		$this->code='';
		$this->ret=array();			
		$this->stage=1;//这个变量是干啥的
	//	error_log($this->cityTmpUse." | ".$this->site[0]['modname']." | ".posix_getpid()." | {$user['uid']} | ".time()."\r\n" , 3 , 'piderr3.log') ;
		$this->webPostError = array(
					'register' => array(						//定义注册时的提示信息，类似枚举一样的
							0 => "用户名不符合网站要求\r\n" ,

							1 => "手机号码已被占用\r\n" ,

							2 => "密码不符要求\r\n" ,

							3 => "邮箱地址已被占用\r\n" ,

							4 => "账号注册失败，未知错误\r\n" ,

							5 => "验证码错误\r\n" ,

					) ,
					'login' => array(						//登录时的提示信息
							0 => "用户名不符合网站要求\r\n" ,

							1 => "账号已被冻结\r\n" ,

							2 => "账号登陆失败，未知错误\r\n" ,

					) ,
					'post_light' => array(						//推送时的提示信息
							0 => "部分图片发送失败\r\n" ,

							1 => "部分图片格式不符合网站要求\r\n" ,

							2 => "有数据字段不符网站要求\r\n" ,

							3 => "数据推送速度过快\r\n" ,

							4 => "网站数据操作已达上限\r\n" ,

							5 => "网站不允许发送重复数据\r\n" ,

							6 => "此网站无法获取发送后的信息地址\r\n" ,

							7 => "数据推送失败，未知错误\r\n" ,

							8 => "图片上传失败\r\n" ,
  
							9 => "验证码错误\r\n" ,

					) ,
					'update' => array(
							0 => "部分图片发送失败\r\n" ,

							1 => "部分图片格式不符合网站要求\r\n" ,

							2 => "有数据字段不符网站要求\r\n" ,

							3 => "数据修改速度过快\r\n" ,

							4 => "网站数据操作已达上限\r\n" ,

							5 => "网站不允许发送重复数据\r\n" ,

							6 => "网站无数据修改功能\r\n" ,

							7 => "数据修改失败，未知错误\r\n" ,
 
							8 => "图片上传失败\r\n" ,
  
							9 => "验证码错误\r\n" ,

					) ,
					'refresh' => array(
							0 => "网站不支持数据刷新功能\r\n" ,

							1 => "网站数据操作已达上限\r\n" ,

							2 => "还未到网站允许的刷新时间\r\n" ,

							3 => "此条数据刷新次数已用完\r\n" ,

							4 => "数据刷新失败，未知错误\r\n" ,

					) ,
					'delete' => array(
							0 => "此条数据已不存在\r\n" ,

							1 => "网站无数据删除功能\r\n" ,

							2 => "网站数据操作已达上限\r\n" ,

							3 => "数据删除失败，未知错误\r\n" ,

							4 => "验证码错误\r\n" ,

					) ,
					'other' => array(
							0 => "网站结构有调整，请联系客服人员\r\n" ,
							1 => "数据推送失败，未知错误\r\n" ,
					) ,
			) ;
	}

	/**
	* 析构函数
	*
	* @param NULL
	*
	* @return NULL
	*/


	/**
	* save
	*
	* 将当前的web访问状态序列化到硬盘文件中调用tmpfile形成临时文件名，需要在setup之后使用。
	* @example  $ret=$this->save();
	* @param	NULL
	* 
	* @return 0 失败/ 1成功;
	*/		
	function save(){
		$this->content = '' ;
		$this->ret='';
		$s = serialize($this);
		//获得特定文件名，$this->action在哪里赋值的？
		$name = localfile($this->action,$this->sessionid,$this->site[$this->siteid]['id'],$this->data[$this->dataid]['ID'],$this->user['uid']);
		//把序列化的对象，存入文件中
		$ret=file_put_contents($name,$s);
		return $ret;//返回字节数，或者false
	}
	/**
	* load
	*
	* 将之前web访问状态序列化文件恢复为保存前的对象用于恢复之前的属性，需要在setup之后使用。
	* @example  $ret=$this->load();
	* @param	NULL
	* 
	* @return 0 失败/ 保存的web对象;
	*/		
	function load(){
		//tmpfile($action,$sessionid,$siteid,$dataid,$userid)
		$name=localfile($this->action,$this->sessionid,$this->site[$this->siteid]['id'],$this->data[$this->dataid][ID],$this->user['uid']);
		$ret=file_exists($name);
		if ($ret){
			$s = implode("", @file($name));
			$s = stripcslashes($s);
			return (unserialize($s));
		}
		else 
			return FALSE;
	}
	/**
	* dele
	*
	* 将之前web访问状态序列化文件删除，需要在setup之后使用。
	* @example  $ret=$this->dele();
	* @param	NULL
	* 
	* @return 0 失败/ 1成功;
	*/		
	function dele(){
		$name=localfile($this->action,$this->sessionid,$this->site[$this->siteid]['id'],$this->data[$this->dataid]['ID'],$this->user['uid']);
		$ret=file_exists($name);
		if ($ret)
			return (unlink($name));//删除特定文件名的文件
		else 
			return FALSE;
	}

	
	/**
	* methodPost
	*
	* 发送POST信息或文件。
	*
	* @since v0.1
	*				
	* @example 		不带Cookies和http头发送一条信息	
	* 				$result=$this->methodPost(('http://www.sina.com','name=1&data=2','new','noheader');
	* @example 		带http头和上次访问时获得的cookies发送一条信息
	* 				$result=$this->methodPost(('http://www.sina.com','name=1&data=2');
	* @todo 
	*
	* @param string $url,
	* 		 string or array $postfield
	* 		 string	$cmod ’new'是新会话 其它为连续性的
	* 		 string	$hmod ‘noheader’返回结果不带HTTP头，否则带有。
	*/	
	public function methodPost($url,$postfield,$encoding,$cmod='',$hmod='',$jmod=''){
		//初始化配置
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_TIMEOUT, 30);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array(   
		'Expect:',
//		'X-MicrosoftAjax: Delta=true',
  		 ));  
		curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/4.0 (compatible; MSIE 8.0; Windows NT 5.1; Trident/4.0; .NET CLR 2.0.50727; .NET CLR 3.0.4506.2152; .NET CLR 3.5.30729)');
//		curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/4.0 (compatible; MSIE 8.0; Windows NT 5.1; Trident/4.0; baiduds; .NET CLR 2.0.50727; .NET CLR 3.0.4506.2152; .NET CLR 3.5.30729; CIBA)');
		curl_setopt($ch, CURLOPT_ENCODING,"gzip,deflate");
		curl_setopt($ch, CURLOPT_POST,1);	//启用post_light,下面的POSTFIELDS才可以奏效
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
		curl_setopt($ch, CURLOPT_URL,$url);
		if(strcmp($jmod , 'jump')==0)
			curl_setopt($ch, CURLOPT_FOLLOWLOCATION,1);
		$refer = parse_url($url) ;
		if(empty($this->refer))
			curl_setopt($ch, CURLOPT_REFERER,$refer['host']);
		else
			curl_setopt($ch, CURLOPT_REFERER,$this->refer);
		curl_setopt($ch, CURLOPT_POSTFIELDS,$postfield);
		switch ($cmod){
			case 'new':
				curl_setopt($ch, CURLOPT_COOKIESESSION, 1);				
				break;
			default:
				curl_setopt($ch, CURLOPT_COOKIE,  $this->webcookies);//webcookies是请求中的Cookie				
		}
		switch($hmod){
			case 'noheader':
				curl_setopt($ch, CURLOPT_HEADER,0);
				break;
			default:
				curl_setopt($ch, CURLOPT_HEADER,1);
		}
		$this->content = curl_exec($ch);
		curl_close($ch);
		if ($this->content=='')
			return FALSE;
		//对返回的结果进行字符集转换
		if ($encoding!="UTF-8")
			$this->content=mb_convert_encoding($this->content ,"UTF-8",$encoding);
		//获取头部的cookie并保存到本对象的cookies字段。
		$tmp = explode(';',$this->webcookies) ;
		if(empty($this->webcookies))
			$tmp = array() ;
		$tmp2=array() ;
		foreach($tmp as $key)
		{
			$tmp1 = explode('=',$key,2) ;
			$tmp2[$tmp1[0]]=$tmp1[1] ;
		}
		preg_match_all("/Set-Cookie: (.*)[;\r\n]{1,1}/isU", $this->content, $results); 
		foreach($results[1] as $key)
		{
			$tmpnow1=explode('=',$key,2) ;
			$tmp2[$tmpnow1[0]]=$tmpnow1[1] ;
		}
		$tmpcookies = '' ;
		foreach($tmp2 as $i=>$key)
		{
			if($i != '')
				$tmpcookies = $tmpcookies.$i.'='.$key.';';
		}
		$tmpcookies = substr($tmpcookies , 0 , -1) ;
		$this->webcookies = $tmpcookies ;
		return $this->content;
	}	
	
	/**
	* methodGet
	*
	* GET信息或文件。
	*
	* @since v0.1
	*				
	* @example 		不带Cookies和http头发送一条信息	
	* 				$result=$this->methodPost(('http://www.sina.com','name=1&data=2','new','noheader','bin');
	* @example 		带http头和上次访问时获得的cookies发送一条信息
	* 				$result=$this->methodPost(('http://www.sina.com','name=1&data=2');
	* @todo 
	*
	* @param string $url,
	* 		 string or array $postfield
	* 		 string	$cmod ’new'是新会话 其它为连续性的
	* 		 string	$hmod ‘noheader’返回结果不带HTTP头，否则带有。
	* 		 string $type 'bin' 二进制文件，其它为文本
	*/	
	public function methodGet($url,$encoding,$cmod='',$hmod='',$type='',$jmod=''){
		//初始化配置
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);//返回结果保存起来
		curl_setopt($ch, CURLOPT_TIMEOUT, 30);		//代码最长执行时间
		//curl_setopt($ch, CURLOPT_HTTPHEADER, array('Accept: text/html, application/xhtml+xml, */*','Connection: keep-alive')) ; 
		curl_setopt($ch, CURLOPT_HTTPHEADER, array('Accept-Encoding: gzip, deflate')) ;//数组形式编写http头信息 
		curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/4.0 (compatible; MSIE 8.0; Windows NT 5.1; Trident/4.0; .NET CLR 2.0.50727; .NET CLR 3.0.4506.2152; .NET CLR 3.5.30729)');
		curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; SV1;  3.0; Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; SV1)');
		curl_setopt($ch, CURLOPT_ENCODING,"gzip,deflate");
		curl_setopt($ch, CURLOPT_HTTPGET,1);//默认是get方法
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);//不需要验证？
		if(strcmp($jmod , 'jump')==0)  //什么叫   ”二进制安全字符串比较“
			curl_setopt($ch, CURLOPT_FOLLOWLOCATION,1);//http响应将"location"递归地返回给Http Header
		$refer = parse_url($url) ;//以数组形式获得url各个部分，以“/”分割
		if(empty($this->refer))
			curl_setopt($ch, CURLOPT_REFERER,$refer['host']);//第一次时告诉服务器A我从A的主页来
		else
			curl_setopt($ch, CURLOPT_REFERER,$this->refer);
		curl_setopt($ch, CURLOPT_URL,$url);
		switch ($cmod){
			case 'new':
				curl_setopt($ch, CURLOPT_COOKIESESSION, 1);	//启动特殊的cookie			
				break;
			default:
				curl_setopt($ch, CURLOPT_COOKIE,$this->webcookies);		//请求头信息中的普通的cookie		
		}
		switch($hmod){			//要不要返回Http头信息 0,不需要，1 需要
			case 'noheader':
				curl_setopt($ch, CURLOPT_HEADER,0);
				break;
			default:
				curl_setopt($ch, CURLOPT_HEADER,1);
		}
		$this->content = curl_exec($ch);
		curl_close($ch);
		if ($this->content=='')//哪些情况会是这样，？登录超时，或者就是返回为空等情况
			return FALSE;
		if ($type!='bin'){	//不是文本格式的话，比如音乐视频图片等格式的二进制文件,但是$type在什么时候赋值的呢？
			//对结果进行字符集转换
			if ($encoding!="UTF-8")//需要将二进制格式的字符集改为utf-8
				$this->content=mb_convert_encoding($this->content ,"UTF-8",$encoding);
		}
		
		//获取头部的cookie并保存到本对象的cookies字段。
		$tmp = explode(';',$this->webcookies) ;//按分号拆开cookie字符串，得到一个个键值对
		if(empty($this->webcookies))
			$tmp = array() ;
		$tmp2=array() ;
		foreach($tmp as $key)
		{
			$tmp1 = explode('=',$key,2) ;		//对每一个键值对再拆分
			$tmp2[$tmp1[0]]=$tmp1[1] ;
		}
		//匹配出多条服务器端发回的Cookie
		preg_match_all("/Set-Cookie: (.*)[;\r\n]{1,1}/isU", $this->content, $results); 
		foreach($results[1] as $key)
		{
			$tmpnow1=explode('=',$key,2) ;
			$tmp2[$tmpnow1[0]]=$tmpnow1[1] ;
		}
		$tmpcookies = '' ;
		foreach($tmp2 as $i=>$key)
		{
			if($i != '')
				$tmpcookies = $tmpcookies.$i.'='.$key.';';
		}
		$tmpcookies = substr($tmpcookies , 0 , -1) ;
		$this->webcookies = $tmpcookies ;
		return $this->content;
	}		
	
	
	public function methodPostMutil($url,$postfield,$encoding,$cmod='',$hmod='',$jmod=''){
		//初始化配置
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_TIMEOUT, 30);
		curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; SV1;  3.0; Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; SV1)');
		curl_setopt($ch, CURLOPT_POST,1);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
		curl_setopt($ch, CURLOPT_URL,$url);
		if(strcmp($jmod , 'jump')==0)
			curl_setopt($ch, CURLOPT_FOLLOWLOCATION,1);
		$refer = parse_url($url) ;
		if(empty($this->refer))
			curl_setopt($ch, CURLOPT_REFERER,$refer['host']);
		else
			curl_setopt($ch, CURLOPT_REFERER,$this->refer);
		curl_setopt_custom_postfields($ch, $postfield) ;
		switch ($cmod){
			case 'new':
				curl_setopt($ch, CURLOPT_COOKIESESSION, 1);				
				break;
			default:
				curl_setopt($ch, CURLOPT_COOKIE,  $this->webcookies);				
		}
		switch($hmod){
			case 'noheader':
				curl_setopt($ch, CURLOPT_HEADER,0);
				break;
			default:
				curl_setopt($ch, CURLOPT_HEADER,1);
		}
		$this->content = curl_exec($ch);
		curl_close($ch);
		if ($this->content=='')
			return FALSE;
		//对结果进行字符集转换
		if ($encoding!="UTF-8")
			$this->content=mb_convert_encoding($this->content ,"UTF-8",$encoding);
		//获取头部的cookie并保存到本对象的cookies字段。
		$tmp = explode(';',$this->webcookies) ;
		if(empty($this->webcookies))
			$tmp = array() ;
		$tmp2=array() ;
		foreach($tmp as $key)
		{
			$tmp1 = explode('=',$key,2) ;
			$tmp2[$tmp1[0]]=$tmp1[1] ;
		}
		preg_match_all("/Set-Cookie: (.*)[;\r\n]{1,1}/isU", $this->content, $results); 
		foreach($results[1] as $key)
		{
			$tmpnow1=explode('=',$key,2) ;
			$tmp2[$tmpnow1[0]]=$tmpnow1[1] ;
		}
		$tmpcookies = '' ;
		foreach($tmp2 as $i=>$key)
		{
			if($i != '')
				$tmpcookies = $tmpcookies.$i.'='.$key.';';
		}
		$tmpcookies = substr($tmpcookies , 0 , -1) ;
		$this->webcookies = $tmpcookies ;
		return $this->content;
	}
	
	Public function getHistoryID(){
	return 0;
	$i=0; 
	if(count($this->data , COUNT_RECURSIVE)>10)//递归得返回数组元素的个数,注意是如何计算数组元素个数的
	{
		foreach ($this->history as $postinfo)
		{
			if ($postinfo['WID']==$this->site[$this->siteid]['id'] && $postinfo['PID']==$this->data[$this->dataid]['ID'])
			{
				return $i;				
			}
			$i++;
		}
	}
	else
	{
		foreach ($this->history as $t => $postinfo){
		if ($postinfo['WID']==$this->site[$this->siteid]['id'])
		{
			if($i == $this->dataid)//不明白？
				return $t ;	
			$i ++ ;
		}
		
		}
	}
}
	Public function checkcode($url , $name)
	{
		if($this->methodGet($url,'UTF-8','','noheader','bin') == FALSE)
			return FALSE ;
		$path = $name.time().rand(0,10000).'.jpg' ;
		$picPath = '../checkcode/'.$path ;
		file_put_contents($picPath , $this->content) ;
		$this->methodGet($this->checkURL."?path={$path}&web={$name}",'gb2312') ;
		unlink($picPath) ;
		if(preg_match("/success&&([^&]*)&&/isU",$this->content , $view) > 0)
		{
			return $view[1] ;
		}
		else
			return FALSE ;
	}


	
	
	Public	function getOldCookie($WID , $timeTmp = 864000)		//获取数据库中缓存的cookie,默认24小时
	{ 
		if($WID <= 350)//这是什么，350以下的账户是什么来头
			return FALSE ;
		$tableName = "ff_cookies" ;//表名是固定的
		//$conn = ConDB($this->cookieDB) ;//专门存储Cookie的数据库
		if($conn == FALSE)
			return FALSE ;
		$query="SELECT Cookie , Time FROM {$tableName} WHERE Wid = '{$WID}'"; 
		$result=mysql_query($query , $conn);
		$row=array(); 
		$row=mysql_fetch_array($result, MYSQL_ASSOC) ;//索引数组
		if(isset($row['Cookie']))
		{
			if((time() - $row['Time']) > $timeTmp)//cookie是否过时
			{
				mysql_close($conn) ;
				return FALSE ;
			}
			else
			{
				mysql_close($conn) ;
				return $row['Cookie'] ;
			}
		}
		else
		{
			mysql_close($conn) ;
			return FALSE ;
		}
	}
	
	Public function deleteOldCookie($WID)	//删除旧的cookie
	{
		if($WID <= 350)
			return FALSE ;
		$tableName = "ff_cookies" ;
		$conn = ConDB($this->cookieDB) ;
		if($conn == FALSE)
			return FALSE ;
		$query= "Delete From {$tableName}  WHERE Wid = '{$WID}' LIMIT 1" ;
		$result=mysql_query($query , $conn);
		mysql_close($conn) ;
		return TRUE ;	
	}
	
	Public	function storeOldCookie($WID , $Cookie)		//保存缓存的cookie
	{ 
		if($WID <= 350)//350是谁？
			return FALSE ;
		$tableName = "ff_cookies" ;
		$conn = ConDB($this->cookieDB) ;
		if($conn == FALSE)
			return FALSE ;
		$tmpTime = time() ; 
		$query= "Update {$tableName} set `Cookie` = '{$Cookie}' , `Time` = '{$tmpTime}' WHERE Wid = '{$WID}'" ;
		$result=mysql_query($query , $conn);
		if(mysql_affected_rows($conn) <= 0)//更新失败，意味着该wid是新来的。需要插入新wid
		{
			$query="Insert {$tableName} (Wid , Cookie , Time) VALUES ('{$WID}' , '{$Cookie}' , '{$tmpTime}')";
			$result=mysql_query($query , $conn);
		}
		mysql_close($conn) ;
		return TRUE ;
	}
	/**
	* getCcommunityex
	*
	* 发布之前转换小区
	* @param	wid  		网站id
	* 			community  小区名字
	* 			type 房		源类型
	* 
	* @return string $community 如果有就是转换后的，如果没有返回原来的.
	* 2012-8-10 by gpf
	*/		
	function getCcommunityex($wid , $community , $type = '1')
	{
		$conn = ConDB($this->feedbackDB) ;//192.168.0.160
		if($conn == FALSE)
			return FALSE ;
		if($type != 4 && $type != 11 && $type != 12)//哦，在这里做了统一。没有了2，3 4别墅
			$type = 1 ;//住宅
		//这是要干啥
		$sql = "select community2 from `ff_community_ex` where `WID` = '{$wid}' and `community` = '{$community}' and `Type` = '{$type}'";
		$result = mysql_query($sql , $conn);
		if($result)
		{
			$community2 = mysql_fetch_array($result,MYSQL_ASSOC);
			if($community2['community2'])
				$community = $community2['community2'];
		}
		//if(strstr($community,'瞰都'))
		//	error_log($community.$sql.mysql_error($conn)."\r\n" , 3 , 'cccom.log');
		mysql_close($conn);
		return $community;//返回小区（可能是community2也可能没变）
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
		$conn = ConDB($this->newfeedbackDB) ;
		if($conn == FALSE)
			return FALSE ;
		$sql = "insert into `ff_bat4f5_timer_status` (`SID`,`WID`,`Time`,`Rent`,`Sale`,`Failure`,`Live`,`Points`,`Error`) values ('{$SID}','{$WID}',now(),'{$Rent}','{$Sale}','{$Failure}','{$Live}','{$Points}','{$Error}')";
		$result = mysql_query($sql , $conn);
		mysql_close($conn);
		return true;
	}
	/**
	* savepic
	*
	*  远程图片存本地
	* 
	* 
	* 2013-03-22
	*/
	function savepic($value)
	{
		return TRUE ;
		if(empty($value) || file_exists($this->picPath.$value)) //文件名为空，或文件在本地已存在，直接返回
		{
			//if(!empty($value))
			//	error_log($this->picPath.$value."\r\n" , 3 , 'savepiclog.log');
			return TRUE;
		}
		else
		{
			if(!file_exists($this->picPath2.$value))//文件在远程也不存在，直接返回
			{
				return TRUE;
			}
			$this->createFolder(dirname($this->picPath.$value)) ; //本地创建文件夹路径
			file_put_contents($this->picPath.$value , file_get_contents($this->picPath2.$value)) ;
		}
		return TRUE ;
	}
	function createFolder($path)
	{
	   if(!file_exists($path))
	   {
	    	$this->createFolder(dirname($path));
	    	mkdir($path, 0777);
	   }
	}
	/**
	* refreshAdd2
	*
	*  新版刷新任务意外失败后重试入库,默认全放到北京
	* 
	* 
	* 2012-11-20
	*/		
	function refreshAdd2($wid,$msg='')
	{
		$conn  = ConDB($this->cookieDB) ;
		if($conn  == FALSE)
			return FALSE ;
		$tmpTime = time() + 60 ;
		$query="INSERT INTO test.RefreshTable2 (`WID` , `time` , `GET` , `POST`) VALUES ({$wid} , '{$tmpTime}' , '".serialize($_GET)."' , '".serialize($_POST)."')"; 
		$result = mysql_query($query , $conn);
		mysql_close($conn) ;
		error_log("{$wid} error ".date('Y-m-d H:i:s')."\r\n" , 3 , 'bat4f5v2Err.log') ;
	}
	
	/**
	* getRemoteIPArray
	*
	*  获取远程IP
	* $num  获取多少ip
	* $table  从哪个数据库表获取
	* $speed  数据库表中speed字段的条件
	* $admin  数据库表中admin字段的条件
	* 2012-11-23
	*/	
	
	public function getRemoteIPArray($table , $num , $speed = "" , $admin = '')
	{
		return;
		$RemoteIPArray = array() ;
		$conn = ConDB($this->remoteDB) ;//192.168.0.105
		if($conn == FALSE)
			return FALSE ;
		$sqlCmd = "SELECT `ip` FROM  `{$table}` WHERE 1 " ;
		$condition = "" ;
		if(!empty($speed))
		{
			$sqlCmd .= "AND `speed` {$speed} " ;
		}
		if(!empty($admin))
		{
			$sqlCmd .= "AND {$admin} " ;
		}
		$sqlCmd .= "order by rand() limit {$num}" ;//随机排列order by rand()，网上评价效率不高
		$result = mysql_query($sqlCmd , $conn) ;
		while($row = mysql_fetch_array($result, MYSQL_ASSOC))
		{
			if(!empty($row['ip']))
				$RemoteIPArray[] = $row['ip'] ;
		}
		mysql_close($conn) ;
		return $RemoteIPArray ;
	}
	
	
	/**
	* getCommunityInfo
	*
	* 这个内部函数用于根据小区ID获得小区的相关信息并返回一个小区信息的array。
	*
	* @since v0.1
	*
	* @example $result= getPhone($input,$select)；
	*
	* @todo 
	* 
	* @param $input，一个放了2个string的数组，[0]是固定电话号码，[1]是手机号码。
	* @param string $select, 返回值选择开关 1=手机优先,2=固定电话优先
	*
	* @return string 电话号码/FALSE
	*/
    Public function getCommunityInfo($CID)
    { 
				$conn = ConDB($this->feedbackDB) ;
				if($conn == FALSE)
					return FALSE ;
				$query="SELECT
						Metro,
						School,
						Shopping,
						Dining,
						Government,
						Finance,
						Leisure,
						Medical,
						Memo,
						Supporting,
						Bus,
						ID
						FROM
						ff_community
						WHERE
						Community_ID = '$CID'"; 
				$result=mysql_query($query , $conn);
				$row = array(); 
				$row = mysql_fetch_array($result, MYSQL_ASSOC) ;
				mysql_close($conn);
				//print_r($row);
				return $row;
		}

		/**
		* getPushProcess
		*
		* 这个内部函数用于判断是否允许发送。
		*
		* @since v0.1
		*
		* @example 
		*
		* @todo 
		* 
		* 
		* 
		*
		* 
		*/
	
	public function getPushProcess($DID , $UID , $WID , $Radom , $Action = 'unknown')
	{ 
		$conn = ConDB($this->cookieDB) ;//cookieDB是localhost
		if($conn == FALSE)
			return FALSE ;
		//查看推送进程库中是否有超时的进程，有的话，由推送进程库转移到超时进程库
		//一旦转移到超时进程，就没有办法了，只能后台帮着重推吧
		$timeout = time() - 600 ;//10分钟之前的
		$result = mysql_query("SELECT * FROM push_process WHERE Time < '{$timeout}'" , $conn) ;
		while($row = mysql_fetch_array($result, MYSQL_ASSOC)) 
		{
			mysql_query("INSERT INTO timeout_process (DID , UID , WID , Radom , Reason , Action) VALUES ('{$row['DID']}' , '{$row['UID']}' , '{$row['WID']}' , '{$row['Radom']}' , '执行超时' , '{$row['Action']}')"  , $conn) ;
			mysql_query("DELETE FROM push_process WHERE ID = '{$row['ID']}'" , $conn) ;
		}
		//查看是否有同一用户的同一个网站的推送进程在运行，有的话，让后来者直接进入等待进程库
		//发现数据库表里有同一用户的同一个网站在运行的话，这种情况，应该是用户点击了‘推送’，然后马上就点击了‘推送’,即，连续推送多次造成的吧？
		//一般第一次进入时没有推送进程，
		if(strcmp($Action , 'Post') == 0) 
		{
			$result = mysql_query("SELECT count(*) FROM push_process WHERE UID = '{$UID}' AND WID = '{$WID}' AND Action = '{$Action}'" , $conn) ;
			if($row = mysql_fetch_array($result, MYSQL_NUM)) //只要有返回就 是true,而不关心返回的是啥
			{
				if($row[0] > 0)	//有同一用户同一网站进程在跑，当前进程等待,并入库sleep_process，稍后再唤醒它
				{
					mysql_query("INSERT INTO sleep_process (DID , UID , WID , Radom , Action) VALUES ('{$DID}' , '{$UID}' , '{$WID}' , '{$Radom}' , '{$Action}')"  , $conn) ;
					mysql_close($conn) ;
					return FALSE ;
				}
			}
		}
		//查看推送进程库里的进程数是否超过了600。超过600的，本次进程也进入睡眠
		$result = mysql_query("SELECT count(*) FROM push_process WHERE 1" , $conn) ;
		if($row = mysql_fetch_array($result, MYSQL_NUM)) 
		{
			if($row[0] >= 600) //如果大于600个进程，刷新进程除外
			{
				if(strcmp($Action , 'Refresh') == 0)
				{
					exit ;
				}//再次等待
				mysql_query("INSERT INTO sleep_process (DID , UID , WID , Radom , Action) VALUES ('{$DID}' , '{$UID}' , '{$WID}' , '{$Radom}' , '{$Action}')"  , $conn) ;
				mysql_close($conn) ;
				return FALSE ;
			}//没有超过600。则插入到推送进程库
			else
			{
				$nowTime = time() ;
				//插入进程表
				mysql_query("INSERT INTO push_process (Time , DID , UID , WID , Radom , Action) VALUES ('{$nowTime}' , '{$DID}' , '{$UID}' , '{$WID}' , '{$Radom}' , '{$Action}')"  , $conn) ;
				//如果他曾经等待过，删除它
				mysql_query("DELETE FROM sleep_process WHERE DID = '{$DID}' AND UID = '{$UID}' AND WID='{$WID}' AND Radom='{$Radom}'" , $conn) ;
				mysql_close($conn) ;
				return TRUE ;
			}
		}
		mysql_close($conn) ;
		return FALSE ;
	}
	
	
	/**
		* completeProcess
		*
		* 这个内部函数用于完成进程。
		*
		* @since v0.1
		*
		* @example 
		*
		* @todo 
		* 
		* 
		* 
		*
		* 
		*/
	
	public function completeProcess($DID , $UID , $WID , $Radom)
	{ 
		$conn = ConDB($this->cookieDB) ;//localhost
		if($conn == FALSE)
			return FALSE ;
		mysql_query("DELETE FROM push_process WHERE DID = '$DID' AND UID = '{$UID}' AND WID='{$WID}' AND Radom='{$Radom}'" , $conn) ;
		mysql_query("DELETE FROM timeout_process WHERE DID = '$DID' AND UID = '{$UID}' AND WID='{$WID}' AND Radom='{$Radom}'" , $conn) ;
		mysql_close($conn) ;
		return TRUE ;
	}
	
	/**
		* timeoutProcess
		*
		* 这个内部函数用于进程超时。
		*
		* @since v0.1
		*
		* @example 
		*
		* @todo 
		* 
		* 
		* 
		*
		* 
		*/
	
	public function timeoutProcess($DID , $UID , $WID , $Radom , $Action = 'unknown')
	{ 
		$conn = ConDB($this->cookieDB) ;
		if($conn == FALSE)
			return FALSE ;
		//由等待进程库转移到超时进程库
		mysql_query("DELETE FROM sleep_process WHERE DID = '$DID' AND UID = '{$UID}' AND WID='{$WID}' AND Radom='{$Radom}'" , $conn) ;
		mysql_query("INSERT INTO timeout_process (DID , UID , WID , Radom , Reason , Action) VALUES ('{$DID}' , '{$UID}' , '{$WID}' , '{$Radom}' , '等待超时' , '{$Action}')"  , $conn) ;
		mysql_close($conn) ;
		return TRUE ;
	}
	
	/**
		* getCookieValue
		*
		* 获取webcookies中某个cookie值。
		*
		* @since v0.1
		*
		* @example 
		*
		* @todo 
		* 
		* 
		* 
		*
		* 
		*/
	Public function getCookieValue($name)			
	{
		if(empty($this->webcookies))
			return FALSE ;
		$tmp1 = explode(';',$this->webcookies) ;
		foreach($tmp1 as $key)
		{
			$tmp2 = explode('=',$key,2) ;
			if(strcmp($tmp2[0] , $name) == 0)
				return $tmp2[1] ;
		}
		return FALSE ;
	}
	
	Public function delCookieValue($a = array())			
	{
		if(empty($this->webcookies))
			return FALSE ;
		$tmp1 = explode(';',$this->webcookies) ;
		foreach($tmp1 as $i => $key)
		{
			$tmp2 = explode('=',$key,2) ;
			if(in_array($tmp2[0] , $a))
				unset($tmp1[$i]) ;
		}
		$this->webcookies = implode(';' , $tmp1) ;
		return TRUE ;
	}
	
	
	/**
		* setCookieValue
		*
		* 设置cookie值到webcookies，$value是个array。
		*
		* @since v0.1
		*
		* @example 
		*
		* @todo 
		* 
		* 
		* 
		*
		* 
		*/
	Public function setCookieValue($value)		
	{
		$cookie = array() ;
		if(!empty($this->webcookies))
			$tmp1 = explode(';',$this->webcookies) ;
		foreach($tmp1 as $key)
		{
			$tmp2 = explode('=',$key,2) ;
			if($tmp2[0] != '')
			{
				$cookie[$tmp2[0]]= $tmp2[1] ;
			}
		}
		$cookie = array_merge($cookie , $value) ;//把四个cookie值加上
		$tmpcookies = '' ;
		foreach($cookie as $i => $key)
		{
			if($i != '')
				$tmpcookies = $tmpcookies.$i.'='.$key.';';
		}
		$this->webcookies = $tmpcookies ;
		return  TRUE;
	}
	
	
	/**
		* methodPostAjkx
		*
		* 带特殊http头的post_light方法，头的内容在$ajkx中定义。
		*
		* @since v0.1
		*
		* @example 
		*
		* @todo 
		* 
		* 
		* 
		*
		* 
		*/
	public function methodPostAjkx($url,$postfield,$encoding,$ajax = array() ,$cmod='',$hmod='',$jmod='')
	{
		//初始化配置
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_TIMEOUT, 30);
/*		curl_setopt($ch, CURLOPT_HTTPHEADER, array(   
		'Expect:',
		'X-MicrosoftAjax: Delta=true',
  		 ));  */
		if(!empty($ajax))
			curl_setopt($ch, CURLOPT_HTTPHEADER,$ajax) ;
		curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/4.0 (compatible; MSIE 8.0; Windows NT 5.1; Trident/4.0; .NET CLR 2.0.50727; .NET CLR 3.0.4506.2152; .NET CLR 3.5.30729)');
//		curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/4.0 (compatible; MSIE 8.0; Windows NT 5.1; Trident/4.0; baiduds; .NET CLR 2.0.50727; .NET CLR 3.0.4506.2152; .NET CLR 3.5.30729; CIBA)');
		curl_setopt($ch, CURLOPT_POST,1);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
		curl_setopt($ch, CURLOPT_URL,$url);
		if(strcmp($jmod , 'jump')==0)
			curl_setopt($ch, CURLOPT_FOLLOWLOCATION,1);
		$refer = parse_url($url) ;
		if(empty($this->refer))
			curl_setopt($ch, CURLOPT_REFERER,$refer['host']);
		else
			curl_setopt($ch, CURLOPT_REFERER,$this->refer);
		curl_setopt($ch, CURLOPT_POSTFIELDS,$postfield);
		switch ($cmod){
			case 'new':
				curl_setopt($ch, CURLOPT_COOKIESESSION, 1);				
				break;
			default:
				curl_setopt($ch, CURLOPT_COOKIE,  $this->webcookies);				
		}
		switch($hmod){
			case 'noheader':
				curl_setopt($ch, CURLOPT_HEADER,0);
				break;
			default:
				curl_setopt($ch, CURLOPT_HEADER,1);
		}
		$this->content = curl_exec($ch);
		curl_close($ch);
		if ($this->content=='')
			return FALSE;
		//对结果进行字符集转换
		if ($encoding!="UTF-8")
			$this->content=mb_convert_encoding($this->content ,"UTF-8",$encoding);
		//获取头部的cookie并保存到本对象的cookies字段。
		$tmp = explode(';',$this->webcookies) ;
		if(empty($this->webcookies))
			$tmp = array() ;
		$tmp2=array() ;
		foreach($tmp as $key)
		{
			$tmp1 = explode('=',$key,2) ;
			$tmp2[$tmp1[0]]=$tmp1[1] ;
		}
		preg_match_all("/Set-Cookie: (.*)[;\r\n]{1,1}/isU", $this->content, $results); 
		foreach($results[1] as $key)
		{
			$tmpnow1=explode('=',$key,2) ;
			$tmp2[$tmpnow1[0]]=$tmpnow1[1] ;
		}
		$tmpcookies = '' ;
		foreach($tmp2 as $i=>$key)
		{
			if($i != '')
				$tmpcookies = $tmpcookies.$i.'='.$key.';';
		}
		$tmpcookies = substr($tmpcookies , 0 , -1) ;
		$this->webcookies = $tmpcookies ;
		return $this->content;
	}		
	public function deleteAccount($value , $sta = FALSE)
	{
		//if($sta == FALSE)
				return FALSE ;
		$conn = ConDB($this->feedbackDB) ;
		if($conn == FALSE)
			return FALSE ;
		$sqlCmd = "Update ff_websites_account set `hot` = {$value} where `id` = {$this->site[$this->siteid]['id']} limit 1" ;
		$result = mysql_query($sqlCmd , $conn) ;
		mysql_close($conn) ;
	}
//安居客  
	public function AnjukedeleteAccount($value , $sta = FALSE)
	{
		$conn = ConDB($this->feedbackDB) ;
		if($conn == FALSE)
			return FALSE ;
		$sqlCmd = "Update ff_websites_account set `hot` = {$value} where `id` = {$this->site[$this->siteid]['id']} limit 1" ;
		$result = mysql_query($sqlCmd , $conn) ;
		mysql_close($conn) ;
	}	
	public function updateAccount($value)
	{
//		//if($sta == FALSE)
//				return FALSE ;
		$conn = ConDB($this->feedbackDB) ;
		if($conn == FALSE)
			return FALSE ;
		$sqlCmd = "Update ff_websites_account set `wid` = {$value} where `id` = {$this->site[$this->siteid]['id']} limit 1" ;
		//error_log($sqlCmd."\r\n" , 3 , 'zUpdateAccount.log') ;
		$result = mysql_query($sqlCmd , $conn) ;
		mysql_close($conn) ;
	}
	public function methodPostlocal($ip,$url,$postfield,$encoding,$cmod='',$hmod='',$jmod=''){
		//初始化配置
		$ch = curl_init();
		/*if($ip)
			curl_setopt ($ch, CURLOPT_PROXY, "{$ip}") ;*/
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_TIMEOUT, 30);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array(   
		'Expect:',
//		'X-MicrosoftAjax: Delta=true',
  		 ));  
		curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; SV1;  3.0; Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; SV1)');
//		curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/4.0 (compatible; MSIE 8.0; Windows NT 5.1; Trident/4.0; baiduds; .NET CLR 2.0.50727; .NET CLR 3.0.4506.2152; .NET CLR 3.5.30729; CIBA)');
		curl_setopt($ch, CURLOPT_POST,1);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
		curl_setopt($ch, CURLOPT_URL,$url);
		if(strcmp($jmod , 'jump')==0)
			curl_setopt($ch, CURLOPT_FOLLOWLOCATION,1);
		$refer = parse_url($url) ;
		if(empty($this->refer))
			curl_setopt($ch, CURLOPT_REFERER,$refer['host']);
		else
			curl_setopt($ch, CURLOPT_REFERER,$this->refer);
		curl_setopt($ch, CURLOPT_POSTFIELDS,$postfield);
		switch ($cmod){
			case 'new':
				curl_setopt($ch, CURLOPT_COOKIESESSION, 1);				
				break;
			default:
				curl_setopt($ch, CURLOPT_COOKIE,  $this->webcookies);				
		}
		switch($hmod){
			case 'noheader':
				curl_setopt($ch, CURLOPT_HEADER,0);
				break;
			default:
				curl_setopt($ch, CURLOPT_HEADER,1);
		}
		$this->content = curl_exec($ch);
		curl_close($ch);
		if ($this->content=='')
			return FALSE;
		//对结果进行字符集转换
		if ($encoding!="UTF-8")
			$this->content=mb_convert_encoding($this->content ,"UTF-8",$encoding);
		//获取头部的cookie并保存到本对象的cookies字段。
		$tmp = explode(';',$this->webcookies) ;
		if(empty($this->webcookies))
			$tmp = array() ;
		$tmp2=array() ;
		foreach($tmp as $key)
		{
			$tmp1 = explode('=',$key,2) ;
			$tmp2[$tmp1[0]]=$tmp1[1] ;
		}
		preg_match_all("/Set-Cookie: (.*)[;\r\n]{1,1}/isU", $this->content, $results); 
		foreach($results[1] as $key)
		{
			$tmpnow1=explode('=',$key,2) ;
			$tmp2[$tmpnow1[0]]=$tmpnow1[1] ;
		}
		$tmpcookies = '' ;
		foreach($tmp2 as $i=>$key)
		{
			if($i != '')
				$tmpcookies = $tmpcookies.$i.'='.$key.';';
		}
		$tmpcookies = substr($tmpcookies , 0 , -1) ;
		$this->webcookies = $tmpcookies ;
		return $this->content;
	}
	//与methodGet()的不同，当第一个参数$ip存在时，可以使用代理IP
	public function methodGetlocal($ip,$url,$encoding,$cmod='',$hmod='',$type='',$jmod='')
	{
		//初始化配置
		$ch = curl_init();
		if($ip)
			curl_setopt ($ch, CURLOPT_PROXY, "{$ip}") ;//代理IP,由它来转接
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_TIMEOUT, 30);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array('Accept-Encoding: gzip, deflate')) ; 
		curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/4.0 (compatible; MSIE 7.0; Windows NT 5.1; Trident/4.0; GTB7.4)');
		curl_setopt($ch, CURLOPT_ENCODING,"gzip,deflate");
		curl_setopt($ch, CURLOPT_HTTPGET,1);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
		if(strcmp($jmod , 'jump')==0)
			curl_setopt($ch, CURLOPT_FOLLOWLOCATION,1);
		$refer = parse_url($url) ;
		if(empty($this->refer))
			curl_setopt($ch, CURLOPT_REFERER,$refer['host']);
		else
			curl_setopt($ch, CURLOPT_REFERER,$this->refer);
		curl_setopt($ch, CURLOPT_URL,$url);
		switch ($cmod){
			case 'new':
				curl_setopt($ch, CURLOPT_COOKIESESSION, 1);				
				break;
			default:
				curl_setopt($ch, CURLOPT_COOKIE,$this->webcookies);				
		}
		switch($hmod){
			case 'noheader':
				curl_setopt($ch, CURLOPT_HEADER,0);
				break;
			default:
				curl_setopt($ch, CURLOPT_HEADER,1);
		}
		$this->content = curl_exec($ch);
		curl_close($ch);
		if ($this->content=='')
			return FALSE;
		if ($type!='bin'){	//是否是文本
			//对结果进行字符集转换
			if ($encoding!="UTF-8")
				$this->content=mb_convert_encoding($this->content ,"UTF-8",$encoding);
		}
		$tmp = explode(';',$this->webcookies) ;
		if(empty($this->webcookies))
			$tmp = array() ;
		$tmp2=array() ;
		foreach($tmp as $key)
		{
			$tmp1 = explode('=',$key,2) ;
			$tmp2[$tmp1[0]]=$tmp1[1] ;
		}
		preg_match_all("/Set-Cookie: (.*)[;\r\n]{1,1}/isU", $this->content, $results); 
		foreach($results[1] as $key)
		{
			$tmpnow1=explode('=',$key,2) ;
			$tmp2[$tmpnow1[0]]=$tmpnow1[1] ;
		}
		$tmpcookies = '' ;
		foreach($tmp2 as $i=>$key)
		{
			if($i != '')
				$tmpcookies = $tmpcookies.$i.'='.$key.';';
		}
		$tmpcookies = substr($tmpcookies , 0 , -1) ;
		$this->webcookies = $tmpcookies ;
		return $this->content;
	}
	//在libs.php中,方法processLoginRet()里登录失败时调用
	//在libs.php中,方法processPostRet()里推送失败时调用
	function nurse4sms($uid , $sid , $wid , $errInfo )
	{
		if(strstr($errInfo,'密码错误') || strstr($errInfo,'未认证') || strstr($errInfo,'未付费') || strstr($errInfo,'个人账号'))
		{
			$ErrType = 1;//单纯的登录错误
		}
		elseif(strstr($errInfo,'没有找到对应小区'))
		{
			$ErrType = 2;//匹配小区街道时的错误
		}
		elseif(strstr($errInfo,'重复'))
		{
			$ErrType = 3;//标题，房源描述等各种重复问题时
		}
		elseif(strstr($errInfo,'已满'))
		{
			$ErrType = 4;//房源条数已满的错误
		}
		else
			return TRUE;
		//没有  '234'=>搜房门店端口
		$webarray = array('119'=>'搜房帮','220'=>'新浪乐居','206'=>'赶集付费','218'=>'58付费','236'=>'搜狐焦点','336'=>'安居客');
		$webarray2 = array_flip($webarray);//键值转换
		if(!in_array($sid,$webarray2))//不是这些网站的，退出
		{
			return TRUE;
		}
		$this->db->connect($this->feedbackDB);//192.168.0.160
		//ff_assistant_option 这个表里有啥信息呢？
		$sql = "select * from ff_assistant_option where UID = '{$uid}' and PushError = 1";
		$tag = $this->db->row($sql);//row返回结果集中的第一条记录，rows返回结果集中的所有记录
		$sql = "select * from ff_realtors where UID = '{$uid}' and `group` not in (0,1,8)";
		$tag = $this->db->row($sql);
		if($tag == FALSE)//没在上面的两个表里面，就退出
			return ;
		$this->db->close();
		$this->db->connect($this->newfeedbackDB);//192.168.0.161
		$sql = "select * from ff_websites_account_stat where WID = '{$wid}'";
		$re = $this->db->row($sql);
		$date = date('Y-m-d');
		$date2 = date('Y-m-d H:i:s' , time() + 1800);//半小时后
		if($re)//ff_websites_account_stat中有相关记录的情况
		{
			//再看看是不是今天的记录
			$sql = "select * from ff_websites_account_stat where `WID` = '{$wid}'  and `Date` = '{$date}'";
			$re = $this->db->row($sql);
			if(!$re)//有记录，但不是今天的记录，更新
			{
				$sql = "update ff_websites_account_stat set   `Date` = '{$date}' , `ErrInfo` = '{$errInfo}' , `Time` = '{$date2}'  where `WID` = '{$wid}'";
			}
			else//有今天的记录，不再更新
			{
				return TRUE;
			}
		}// ff_websites_account_stat中没有相关记录的情况，入库
		else
		{
			$sql = "insert into ff_websites_account_stat (`UID`,`SID`,`WID`,`ErrType` ,`ErrInfo`,`Date`,`Time`) values ('{$uid}' , '{$sid}', '{$wid}', '{$ErrType}', '{$errInfo}', '{$date}', '{$date2}')";
		}
		$this->db->exe($sql);
		$this->db->close();
		return TRUE;
	}
	public function sms($content , $tel)
	{
		if(!$content || !$tel)
		{
			return FALSE;
		}
		$content = '【推推99提示】'.$content;
		$path="http://192.168.0.121/zf2010/pub/sms.local.php" ;
		$content = mb_substr($content , 0 , 70 , 'utf-8');
		$postfield = "x=sys_alarm&m={$tel}&s={$content}";
		$this->methodPostlocal('',$path, $postfield);
		return TRUE;;
	}
	/**
	 * 推送时更新一键刷新的房源库
	 * **/
	public function upHouse4one ($id , $type , $data='')
	{
		$this->db->connect($this->newfeedbackDB);
		$time = time();
		$time2 = date('Y-m-d H:i:s');
		if($type == 'del')
		{
			$sql = "delete from ff_bat4f5_house where `WID` = '{$this->WID}' and `RemoteID` = '{$id}' limit 1";
		}
		else
		{
			$sql = "insert into ff_bat4f5_house (`WID`,`Type`,`Community`,`RemoteID`,`Price`,`Square`,`Room`,`RefTime`,`PofTime`,`Time`) values
			('{$this->WID}' , '{$data['Type']}' , '{$data['Community']}', '{$data['RemoteID']}', '{$data['Price']}', '{$data['Square']}', '{$data['Room']}',
			'{$time}','{$time}','{$time2}')";
		}
		$this->db->exe($sql);
	//	error_log($sql."\r\n" , 3, 'uphouse4one1.log');
		$this->db->close();
	}
	public function addHouse4one ($id , $com)
	{
		$ret = array();
		$ret['Community'] = $com;
		$ret['RemoteID'] = $id;
		if($this->data[$this->dataid]['dataType'] =='rent')
			$ret['Type'] = '3';
		else
			$ret['Type'] = '6';
		$ret['RemoteID'] = $id;	
		$ret['Price'] = $this->data[$this->dataid]['Total'];
		$ret['Square'] = $this->data[$this->dataid]['Square'];
		$ret['Room'] = getRoom( $this->data[$this->dataid]['Room'],'shi');
		$this->upHouse4one($id,'add',$ret);//再调用底层方法
	}
	function free4delete($wid,$userName,$userKey)
	{
		return true;
	}
	function free4refresh($wid,$userName,$userKey,$num,$use)
	{
		return array('num'=>0);
	}
	Public function login(){}
	Public function logout(){}
	Public function postform(){}
	Public function updateform(){}
	Public function delete(){}
	Public function register(){}
	Public function refresh (){}

}



?>

