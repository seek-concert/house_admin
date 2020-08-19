<?php
/**
 * Class AdminController  后台用户
 * @package App\Http\Controllers\admin
 */
namespace App\Http\Controllers\admin;
use App\Model\AdminModel;
use App\Model\RoleModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminController extends BaseController
{
    /**
     * AdminController constructor.
     */
    public function __construct()
    {
        parent::__construct();
        $this->contr_name = get_method(1);
        $this->module_name = '管理员';
        $this->filed_arr = ['id','username','login_num','last_login_ip','last_login_time','realname','status','role_id','created_at'];
        $this->model_name = new AdminModel();
    }
    /**
     * 定义列表查询数据。
     * @return string
     */
    private function list_html()
    {
        return '<th data-field="id">管理员ID</th><th data-field="realname">真实姓名</th>
                        <th data-field="username">管理员名称</th><th data-field="role_name">管理员角色</th>
                        <th data-field="login_num">登录次数</th><th data-field="last_login_ip">上次登录ip</th>
                        <th data-field="last_login_time">上次登录时间</th><th data-field="status">状态</th>
                        <th data-field="operate">操作</th>';
    }

    /**
     * 定义验证规则
     * @param $request_name
     * @return array
     */
    private function rules($request_name='add')
    {
        $data['add'] = [
            'username' => 'required',
            'password' => 'required',
            'role_id' => 'required',
            'realname' => 'required',
            'status' => 'required'
        ];
        $data['edit'] = [
            'username' => 'required',
            'role_id' => 'required',
            'realname' => 'required',
            'status' => 'required'
        ];
        return $data[$request_name];
    }
    /**
     * 定义验证错误消息。
     * @param $request_name
     * @return array
     */
    private function messages($request_name='add')
    {
        $data['add'] = [
            'username.required' => '请填写管理员名称',
            'password.required' => '请填写管理员密码',
            'role_id.required' => '请选择管理员角色',
            'realname.required' => '请填写管理员姓名',
            'status.required' => '请选择管理员状态',
        ];
        $data['edit'] = [
            'username.required' => '请填写管理员名称',
            'role_id.required' => '请选择管理员角色',
            'realname.required' => '请填写管理员姓名',
            'status.required' => '请选择管理员状态',
        ];
        return $data[$request_name];
    }
    /**
     * 列表
     * @return mixed
     */
    public function lists(Request $request)
    {
        if(Request()->ajax()){
            $where = [];
            /* ********** 名称 ********** */
            $username = $request->input('name');
            if ($username) $where[] = ['username', 'like', '%'.$username.'%'];$infos['username'] = $username;
            $ret = $this->get_lists($request,$this->list_html(),$where,['role']);
            foreach($ret['rows'] as $key=>$vo){
                if(isset($vo['role_id'])&&$vo['role_id']==1) $ret['rows'][$key]['operate']='';
                else $ret['rows'][$key]['operate'] = showOperate($this->makeButton($vo['id']));
                $ret['rows'][$key]['role_name'] = $vo['role']['role_name'];
            }
            return json($ret);
        }
        return $this->get_lists($request,$this->list_html());
    }
    /**
     * 添加数据
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Contracts\View\Factory|\Illuminate\Foundation\Application|\Illuminate\View\View
     */
    public function add(Request $request){
        $param = $request->all();
        if(Request()->isMethod('post')){
            $param['password'] = encrypt($param['password']);
            return $this->get_add($param,$this->rules('add'),$this->messages('add'));
        }
        $data['role_arr'] = RoleModel::get_one_pluck([['id','<>','1']],'role_name','id',true);
        return $this->get_add($param,$this->rules('add'),$this->messages('add'),$data);
    }
    /**
     * 编辑数据
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Contracts\View\Factory|\Illuminate\Foundation\Application|\Illuminate\View\View
     */
    public function edit(Request $request){
        $param = $request->all();
        if($param['id']==1) return j_error('非法操作');
        $data['role_arr'] = RoleModel::get_one_pluck([['id','<>','1']],'role_name','id',true);
        if(isset($param['password'])) $param['password'] = encrypt($param['password']);
        else unset($param['password']);
        return $this->get_edit($param,$this->rules('edit'),$this->messages('edit'),$data);
    }
    /**
     * 删除数据
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function del(Request $request){
        $param = $request->all();
        return $this->get_del($param);
    }
    /**
     * 拼装操作按钮
     * @param $id
     * @return array
     */
    private function makeButton($id)
    {
        return ['编辑' => ['href' => route('g_'.$this->contr_name.'_edit', ['id' => $id]),
            'btnStyle' => 'primary','icon' => 'fa fa-paste'],
            '删除' => ['href' => 'javascript:del(' .$id .')',
                'btnStyle' => 'danger','icon' => 'fa fa-trash-o']];
    }
}
