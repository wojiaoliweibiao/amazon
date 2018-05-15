<?php
namespace app\index\model;
use think\Model;

class login extends Model{

/*通过CURL模拟登录并获取数据
一些网站需要权限认证，必须登录网站后，才能有效地抓取网页并采集内容，
这就需要curl来设置cookie完成模拟登录网页，php的curl在抓取网页内容方
面效率是比较高的，而且支持多线程，而file_get_contents()效率就要稍低
些。模拟登录的代码如下所示：
*/
public function loginPost($login_url,$cookie_file){
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $login_url);
		curl_setopt($ch, CURLOPT_HEADER, 0);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
		curl_setopt($ch, CURLOPT_COOKIEJAR, $cookie_file);
		curl_exec($ch);
		curl_close($ch);
		// return $rs;
}
public function verify($verify_url){
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $verify_url);
		// curl_setopt($ch, CURLOPT_COOKIEFILE, $cookie_file);
		curl_setopt($ch, CURLOPT_HEADER, 0);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		$verify_img = curl_exec($ch);
		curl_close($ch); 
		$fp = fopen("verifyCode.jpg",'w');   //把抓取到的图片文件写入本地图片文件保存
		fwrite($fp, $verify_img);
		fclose($fp);
		sleep(15);
}
public function login($post_url,$post,$cookie_file)
{
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $post_url);
		curl_setopt($ch, CURLOPT_HEADER, false);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $post);         //提交方式为post
		curl_setopt($ch, CURLOPT_COOKIEFILE, $cookie_file);
		curl_exec($ch);
		curl_close($ch);
}
public function getContent($data_url, $cookie_file){
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $data_url);
		curl_setopt($ch, CURLOPT_HEADER, false);
		curl_setopt($ch, CURLOPT_HEADER, 0);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER,0);      
		curl_setopt($ch, CURLOPT_COOKIEFILE, $cookie_file);
		$data = curl_exec($ch);
		curl_close($ch);
		return $data;
}
/*
get_content()中用curlopt_cookiefile可以读取到登录保存的cookie信
息 最后讲页面内容返回.我们的目的是获取到模拟登录后的信息，也就是
只有正常登录成功后菜能获取的有用的信息，下面举例代码
*/


	
}