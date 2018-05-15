<?php

namespace app\admin\controller;
use  app\admin\controller\Permissions;
use \think\Db;
use \think\Cookie;
use \think\Session;
use \think\Cache;
class Index  extends Permissions
{	

    public function index()
    {	
    	//后台版本号 	
        $info['admin_v'] = '3.0';
        //tp版本号
        $info['tp'] = THINK_VERSION;
        //php版本
        $info['php'] = PHP_VERSION;
        //操作系统
        $info['win'] = PHP_OS;
        //最大上传限制
        $info['upload_size'] = ini_get('upload_max_filesize');
        //脚本执行时间限制
        $info['execution_time'] = ini_get('max_execution_time').'S';
        //环境
        $sapi = php_sapi_name();
        // dump( $sapi);
        if($sapi == 'apache2handler') {
        	$info['environment'] = 'apache';
        } elseif($sapi == 'cgi-fcgi') {
        	$info['environment'] = 'nginx';
        } else {
        	$info['environment'] = 'cli';
        }

        //剩余空间大小
        //$info['disk'] = round(disk_free_space("/")/1024/1024,1).'M';
        $this->assign('info',$info);
        /**
         *网站信息
         */

        $web['user_num'] = Db::name('admin')->count();
        $web['admin_cate'] = Db::name('admin_cate')->count();
        $ip_ban = Db::name('webconfig')->value('black_ip');
        $web['ip_ban'] = empty($ip_ban) ? 0 : count(explode(',',$ip_ban));
        $web['article_num'] = Db::name('article')->count();
        $web['status_article'] = Db::name('article')->where('status',0)->count();
        $web['top_article'] = Db::name('article')->where('is_top',1)->count();
        $web['file_num'] = Db::name('attachment')->count();
        $web['status_file'] = Db::name('attachment')->where('status',0)->count();
        $web['ref_file'] = Db::name('attachment')->where('status',-1)->count();
        $web['message_num'] = Db::name('messages')->count();
        $web['look_message'] = Db::name('messages')->where('is_look',0)->count();
        //登陆次数和下载次数
        $today = date('Y-m-d');
        //取当前时间的前十四天
        $date = [];
        $date_string = '';
        for ($i=9; $i >0 ; $i--) {
            $date[] = date("Y-m-d",strtotime("-{$i} day"));
            $date_string.= date("Y-m-d",strtotime("-{$i} day")) . ',';
        }
        $date[] = $today;
        $date_string.= $today;
        $web['date_string'] = $date_string;
        $login_sum = '';
        foreach ($date as $k => $val) {
            $min_time = strtotime($val);
            $max_time = $min_time + 60*60*24;
            $where['create_time'] = [['>=',$min_time],['<=',$max_time]];
            $login_sum.= Db::name('admin_log')->where(['admin_menu_id'=>50])->where($where)->count() . ',';
        }
        $web['login_sum'] = $login_sum;
        $this->assign('web',$web);

        //左侧菜单信息
        $menu = Db::name('admin_menu')->where(['is_display'=>1])->order('orders asc')->select();
        // dump($menu);die;
        // dump($_SESSION);die;
        //删除不在当前角色权限里的菜单，实现隐藏
        $admin_cate = Session::get('admin_cate_id');
        // 获取用户的权限
        $permissions = Db::name('admin_cate')->where(['id'=>$admin_cate])->value('permissions');

        $permissions = explode(',',$permissions);

        foreach ($menu as $k => $val) {

        	if($val['type'] == 1 &&  !in_array($val['id'],$permissions)) {
        		unset($menu[$k]);

        	}

        }
        // 添加url
        foreach ($menu as $key => $value) {
        	if(empty($value['parameter'])) {
        	echo 	$url = url($value['module'].'/'.$value['controller'].'/'.$value['function']);
            echo '<br>';
        	} else {
                $url = url($value['module'].'/'.$value['controller'].'/'.$value['function'],$value['parameter']);
        	}
        	$menu[$key]['url'] = $url; 
        }
// die;
        $menus = $this->menulist($menu);

        $this->assign('menus',$menus);
        $cookie = Db::name('admin')->where('id',Session::get('admin'))->find();
        $this->assign('cookie',$cookie);

        // dump( $menus);
        return $this->fetch();
    }


    protected function menulist($menu){
		$menus = array();
		//先找出顶级菜单
		foreach ($menu as $k => $val) {
			if($val['pid'] == 0) {
				$menus[$k] = $val;
			}
		}
		//通过顶级菜单找到下属的子菜单
		foreach ($menus as $k => $val) {
			foreach ($menu as $key => $value) {
				if($value['pid'] == $val['id']) {
					$menus[$k]['list'][] = $value;
				}
			}
		}
		//三级菜单
		foreach ($menus as $k => $val) {
			if(isset($val['list'])) {
				foreach ($val['list'] as $ks => $vals) {
					foreach ($menu as $key => $value) {
						if($value['pid'] == $vals['id']) {
							$menus[$k]['list'][$ks]['list'][] = $value;
						}
					}
				}
			}
		}
		// dump($menus);die;
		return $menus;
	}
}

