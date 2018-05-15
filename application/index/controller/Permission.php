<?php 
namespace app\index\controller;
use think\Controller;
use think\Db;
use think\Loader;
use think\Session;


class Permission  extends Controller
{

	protected function _initialize(){


		//1. 验证是否登录，没有则跳到登录页面
		
			if(!Session::has('username')){

				$this->redirect('/index.php/index/Common/login');
			}

		
	}


}