<?php
/**
 * Class IndexController 后台入口
 * @package App\Http\Controllers\admin
 */
namespace App\Http\Controllers\admin;
use App\Http\Controllers\Controller;
use App\Model\AdminModel;
use App\Model\RoleModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class IndexController extends Controller
{
    /**
     * 后台登陆页面
     * @param Request $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(Request $request){
        return view('admin.login');
    }

    /**
     * 登录处理
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(Request $request){
        $param = $request->all();
        /* ********** 数据验证 ********** */
//        $validator = Validator::make($param,[
//            'username'=>'required',
//            'password'=>'required',
//            'code' => 'required|captcha',
//        ],[
//            'username.required' => '账号不能为空',
//            'password.required' => '密码不能为空',
//            'code.required' => '验证码不能为空',
//            'code.captcha' => '验证码错误',
//        ]);
//
//        if($validator->fails()){
//            return j_error($validator->errors()->first());
//        }
        /* ********** 查询用户 ********** */
        $user=AdminModel::get_one_data(['username'=>$param['username']],['id','username','password','role_id','realname']);
//        if(blank($user)){
//            return j_error('用户不存在或已被禁用');
//        }
//
//        /* ********** 验证密码 ********** */
//        if(md5($param['password']) != $user['password']){
//            return j_error('密码错误');
//        }
        // 获取该管理员的角色信息
        $roleModel = new RoleModel();
        $info = $roleModel->getRoleInfo($user['role_id']);
        /* ********** 更新登录 ********** */
        AdminModel::update_data($user['id'],['last_login_time'=>time(),'last_login_ip'=>$request->ip(),'login_num'=>DB::raw('login_num + 1')]);
        /* ********** 生成session ********** */
        session(['admin_user'=>[
            'role_id'=>$user['role_id'], 'user_id'=>$user['id'], 'username'=>$user['username'], 'realname'=>$user['realname'],
            'role'=>$info['role_name'], 'rule'=>$info['rule'], 'action'=>$info['action']
        ]]);
        return j_success('登录成功',session('admin_user'),route('g_home'));
    }

    /**
     * 退出登录
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function logout(Request $request){
        $request->session()->forget('admin_user');
        return redirect()->route('g_index');
    }
}
