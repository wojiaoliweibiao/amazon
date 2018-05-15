<?php
namespace app\admin\model;
use think\Model;

class login extends Model{

/*通过CURL模拟登录并获取数据
一些网站需要权限认证，必须登录网站后，才能有效地抓取网页并采集内容，
这就需要curl来设置cookie完成模拟登录网页，php的curl在抓取网页内容方
面效率是比较高的，而且支持多线程，而file_get_contents()效率就要稍低
些。模拟登录的代码如下所示：
*/
public function login_post($url, $cookie, $post){
	$ch = curl_init(); //初始化curl模块
	curl_setopt($ch, CURLOPT_URL, $url); //登录提交的地址
	curl_setopt($ch, CURLOPT_HEADER, 0); //是否显示头信息
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 0); //是否自动显示返回的信息
	curl_setopt($ch, CURLOPT_COOKIEJAR, $cookie); //设置cookie信息保存在指定的文件夹中
	curl_setopt($ch, CURLOPT_POST, 1); //以POST方式提交
	curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($post));//要执行的信息
	curl_exec($ch); //执行CURL
	curl_close($ch);
}
/*
上例中声明的函数login_post()，需要提供一个url地址，一个保存cookie文
件，以及post的数据（用户名和密码等信息），注意php自带的http_build_query()
函数可以将数组转换成相连接的字符串，如果通过该函数登录成功后，我们要获取
登录成功个页面信息。声明函数的代码如下所示：
*/
public function get_content($url, $cookie){
	$ch = curl_init(); //初始化curl模块
	curl_setopt($ch, CURLOPT_URL, $url); //登录提交的地址
	curl_setopt($ch, CURLOPT_HEADER, 0); //是否显示头信息
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); //是否自动显示返回的信息
	curl_setopt($ch, CURLOPT_COOKIEJAR, $cookie);//设置cookie信息保存在指定的文件夹中
	$rs = curl_exec($ch); //执行curl转去页面内容
	curl_close($ch);
	return $rs; //返回字符串
}
/*
get_content()中用curlopt_cookiefile可以读取到登录保存的cookie信
息 最后讲页面内容返回.我们的目的是获取到模拟登录后的信息，也就是
只有正常登录成功后菜能获取的有用的信息，下面举例代码
*/


	
}