<?php
/**
 * Class AuthController  后台验证
 * @package app\admin\controller
 */
namespace App\Http\Controllers\admin;
use App\Http\Controllers\Controller;

class AuthController extends Controller
{
    public $user_info;
    public function __construct()
    {
        $this->middleware(function ($request,$next){
//            if(empty(session('admin_user.username'))){
//                $loginUrl = route('g_index');
//                if(request()->isMethod('post')){
//                    return j_error('登陆超时',$loginUrl);
//                }
//                redirect($loginUrl);
//            }
//            // 检测权限
//            $control = get_method(1);
//            $action = get_method(2);
//            if(empty(authCheck($control . '/' . $action))){
//                return j_error('您没有权限');
//            }

         $this->user_info=['username' => session('admin_user.username'), 'rolename' => session('admin_user.role')];
            return $next($request);
        });
    }
}
