<?php 



namespace app\index\controller;

use \think\Cache;
use \think\Controller;
use think\Loader;
use think\Db;
use \think\Cookie;
use \think\Session;
// use \think\Verify;

class Common extends Controller
{
	public function index(){

	}

	public function login(){
		// session_start();
		// echo phpinfo();
		// var_dump(Session::get('username'));
		if(!empty($_POST)){
				$data=$_POST['data'];
				$data['password']=encrypt_password($data['password']);
				$user_db=Db::name('index_user')->where($data)->find();
				Session::set('username',$user_db['username']);
				Session::set('user_id',$user_db['user_id']);
				// dump($_SESSION['index']);				
			}
		if(!Session::has('username')){
			
			return $this->fetch();

		}else{
			$this->redirect('index/index');
		}

	}

	public  function regist(){
			// 1.获取提交的数据
			$data=$_POST['data'];
				// 密码加密和时间戳
				$data['password']=encrypt_password($data['password']);
				$data['date']=time();
				// 权限默认为1,普通
				$data['group_id']=1;
			// 2.判断用户是否存在
				$arr=array('username'=>$data['username'],'email'=>$data['email']);
			$is_rep=Db::name('index_user')->where($arr)->find();
			// 3.如果存在则回到注册页面
			if($is_rep){
				$this->error('该用户名已经注册','index/common/login');
				die;
			}
			// 4.如果不存在,存储用户信息
			$user=Db::name('index_user')->data($data)->insert();
			if($user){
				$this->success('用户注册成功','index/common/login');
			}

	}

	public function logout(){
		Session::delete('username');
        Session::delete('user_id');
        if(Session::has('username') or Session::has('user_id')) {
            return $this->error('退出失败');
        } else {
            return $this->redirect('./index/common/login');
        }
	}



}


