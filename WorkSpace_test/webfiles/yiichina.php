<?php

/**
 * post动作都需要获取页面的token
 * Class yiichina
 */
 class yiichina extends website
 {
	public $patten = array(
		'host'=>'https://www.yiichina.com',
		'loginUrl'=>'https://www.yiichina.com/login',
	);

     /**
      * 签到动作的上一步，需要获得token
      * @var string
      */
    public $signToken="";

	/**
	* login
	*
	* 网站用户登陆
	*
	* @since v0.1
	*
	* @example $result=$this->login(）；
	*
	* @todo 
	*
	*
	* @return TRUE/FALSE
	*/	
	
	Public function login()
	{
		$tmp=$this->patten;
		$host = $tmp['host'];
		$this->webcookies = '' ;

		//先去登录页获取token
        $loginInfo = $this->getLoginInfo();

		$username = $this->site[$this->siteid]['userName'];
		$password = $this->site[$this->siteid]['userKey'];
		$loginForm['_csrf']=$loginInfo[1];
		$loginForm['login-button']="";
		$loginForm['LoginForm']=[
//            'username'=> '872140945@qq.com',
            'username'=> $username,
            'password'=>$password,
            'rememberMe'=> 1
        ];
		$content = http_build_query($loginForm);
		//不能开启location,会丢掉302中的cookie
		$this->methodPost($host.$loginInfo[0] , $content);
        //出现location
        if(!strstr($this->content , 'Location: https://www.yiichina.com/'))
        {
            return array('status'=>1);
        }
		$this->methodGet($host);
		//出现个人主页的链接
		if(strstr($this->content , '<a class="dropdown-item" href="/user">'))
		{
		    //拿到新token，签到使用
            if (preg_match('#<meta name="csrf-token" content="([^"]*)"#isU',$this->content,$match)) {
                $this->signToken = $match[1];
            }
			return array('status'=>9);
		}
		elseif(strstr($this->content , '用户名或密码不正确'))
		{
			$this->errInfo .= '登陆失败,用户名或密码错误。@登录失败,用户名或密码错误';
			return array('status'=>2);
		}
		elseif(strstr($this->content , '您的账号还未激活'))
		{
			$this->errInfo .= '登录失败,此账号未认证成功';
			return array('status'=>8);
		}
		else
		{
			$this->errInfo .= '登陆失败,网站接口繁忙,请稍后再试。@登录失败,网站接口繁忙,请稍后再试。';
			$this->content .= print_r($content , true) ;
			return array('status'=>0);
		}
	}

     /**
      * 首先获取登录的信息
      * 比如token
      * @author: LiuShiFu
      */
	private function getLoginInfo()
    {
        $return =[];
        $content  = $this->methodGet($this->patten['loginUrl'],'utf-8');
        if (preg_match('#id="login-form" action="([^"]*)"#isU',$content,$match)) {
            $return[] = $match[1];
            //拿到token
            if (preg_match('#<meta name="csrf-token" content="([^"]*)"#isU',$content,$match)) {
                $return[] = $match[1];
            }
        }
        return $return;
    }
	
     /**
      * get 方法
      * @param string $url
      * @param $encoding
      * @param string $cmod
      * @param string $hmod
      * @param string $type
      * @param string $jmod
      * @return bool|string
      * @author: LiuShiFu
      */
	public function methodGet($url,$encoding='',$cmod='',$hmod='',$type='',$jmod='')
	{
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_TIMEOUT, 30);
		curl_setopt($ch, CURLOPT_HTTPHEADER,
            array(
                'Accept-Encoding: gzip, deflate',
                'pragma: no-cache',
                'cache-control: no-cache',
                'upgrade-insecure-requests: 1'
            )
        ) ;
		curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_14_0) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/81.0.4044.122 Safari/537.36');
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

		//cookie模式
		switch ($cmod){
			case 'new':
				curl_setopt($ch, CURLOPT_COOKIESESSION, 1);				
				break;
			default:
				curl_setopt($ch, CURLOPT_COOKIE,$this->webcookies);				
		}
		//header模式
		switch($hmod){
			case 'noheader':
				curl_setopt($ch, CURLOPT_HEADER,0);
				break;
			default:
				curl_setopt($ch, CURLOPT_HEADER,1);
		}
		//执行请求，接收响应
		$this->content = curl_exec($ch);
		curl_close($ch);
		if ($this->content=='')
			return FALSE;
		if($type!='bin')
		{	
			//对结果进行字符集转换
			if ($encoding) {
                $this->content = mb_convert_encoding($this->content ,"UTF-8",$encoding);
            }
		}
		//下面整理cookie，旧cookie+新cookie
		//原始的cookie
		$tmp = explode(';',$this->webcookies) ;
		if(empty($this->webcookies))
			$tmp = array() ;
		$tmp2=array() ;
		foreach($tmp as $key)
		{
			$tmp1 = explode('=',$key,2) ;
			$tmp2[$tmp1[0]]=$tmp1[1] ;
		}
		//本次请求响应的cookie捕获并处理
		preg_match_all("/Set-Cookie: (.*)[;\r\n]/isU", $this->content, $results);
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
		//重置cookie到webcookies中
		$tmpcookies = substr($tmpcookies , 0 , -1) ;
		$this->webcookies = $tmpcookies ;
		return $this->content;
	}

     /**
      *
      * post 方法
      * @param string $url
      * @param $postfield
      * @param string $encoding
      * @param string $cmod
      * @param string $hmod
      * @param string $jmod
      * @return bool|string
      * @author: LiuShiFu
      */
  	public function methodPost($url,$postfield,$encoding="",$cmod='',$hmod='',$jmod='')
	{
		//初始化配置
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	//	curl_setopt($ch, CURLOPT_HTTPHEADER, array('x-requested-with: XMLHttpRequest')); 
		curl_setopt($ch, CURLOPT_TIMEOUT, 30);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array(   
		'content-type: application/x-www-form-urlencoded',
        'origin: https://www.yiichina.com',
  		 ));
        curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_14_0) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/81.0.4044.122 Safari/537.36');
		curl_setopt($ch, CURLOPT_POST,1);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
		curl_setopt($ch, CURLOPT_URL,$url);
		//是否可以location跳转
		if (strcmp($jmod , 'jump')==0) {
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION,1);
            //如果有302，自动设置header
            curl_setopt($ch, CURLOPT_AUTOREFERER,1);
            //针对302继续location
            curl_setopt($ch, CURLOPT_POSTREDIR,2);
        }
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
		if ($this->content=='')
		{
			$this->content = curl_exec($ch);
		}
		curl_close($ch);
		if ($this->content=='')
		{
			return FALSE;
		}
		//对结果进行字符集转换
		if ($encoding)
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

     public function methodAjax($url,$postfield,$token='')
     {
         //初始化配置
         $ch = curl_init();
         curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
         //ajax特有的
         curl_setopt($ch, CURLOPT_HTTPHEADER, array('x-requested-with: XMLHttpRequest'));
         curl_setopt($ch, CURLOPT_TIMEOUT, 30);
         curl_setopt($ch, CURLOPT_HTTPHEADER, array(
             'content-type: application/x-www-form-urlencoded; charset=UTF-8',
             'accept: application/json, text/javascript, */*; q=0.01',
             'origin: https://www.yiichina.com',
             'cache-control: no-cache',
             'x-csrf-token: '.$token,
         ));
         curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_14_0) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/81.0.4044.122 Safari/537.36');
         curl_setopt($ch, CURLOPT_POST, 1);
         curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
         curl_setopt($ch, CURLOPT_URL, $url);
         $refer = parse_url($url);
         if (empty($this->refer))
             curl_setopt($ch, CURLOPT_REFERER, $refer['host']);
         else
             curl_setopt($ch, CURLOPT_REFERER, $this->refer);
         curl_setopt($ch, CURLOPT_POSTFIELDS, $postfield);
         curl_setopt($ch, CURLOPT_COOKIE, $this->webcookies);
         curl_setopt($ch, CURLOPT_HEADER, 1);
         $this->content = curl_exec($ch);
         if ($this->content == '') {
             $this->content = curl_exec($ch);
         }
         curl_close($ch);
         if ($this->content == '') {
             return FALSE;
         }
         //对结果进行字符集转换
         //if (0)
           //  $this->content = mb_convert_encoding($this->content, "UTF-8", $encoding);
         //获取头部的cookie并保存到本对象的cookies字段。
         $tmp = explode(';', $this->webcookies);
         if (empty($this->webcookies))
             $tmp = array();
         $tmp2 = array();
         foreach ($tmp as $key) {
             $tmp1 = explode('=', $key, 2);
             $tmp2[$tmp1[0]] = $tmp1[1];
         }
         preg_match_all("/Set-Cookie: (.*)[;\r\n]{1,1}/isU", $this->content, $results);
         foreach ($results[1] as $key) {
             $tmpnow1 = explode('=', $key, 2);
             $tmp2[$tmpnow1[0]] = $tmpnow1[1];
         }
         $tmpcookies = '';
         foreach ($tmp2 as $i => $key) {
             if ($i != '')
                 $tmpcookies = $tmpcookies . $i . '=' . $key . ';';
         }
         $tmpcookies = substr($tmpcookies, 0, -1);
         $this->webcookies = $tmpcookies;
         return $this->content;
     }

     /**
      * 签到
      * @author: LiuShiFu
      */
	public function registration()
    {
        $url = "https://www.yiichina.com/ajax/registration";
        $content="_csrf=".urlencode($this->signToken);
        $return = $this->methodAjax($url, $content, $this->signToken);
        if (strstr($this->content,'"status":1')) {
            return ['status'=>9];
        }
        return ['status'=>1];
    }
}

?>