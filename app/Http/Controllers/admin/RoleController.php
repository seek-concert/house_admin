<?php
/**
 * Class RoleController  后台角色
 * @package App\Http\Controllers\admin
 */
namespace App\Http\Controllers\admin;
use App\Model\NodeModel;
use App\Model\RoleModel;
use Illuminate\Http\Request;

class RoleController extends BaseController
{
    /**
     * RoleController constructor.
     */
    public function __construct()
    {
        parent::__construct();
        $this->contr_name = get_method(1);
        $this->module_name = '角色';
        $this->filed_arr = ['id','role_name','rule','created_at'];
        $this->model_name = new RoleModel();
    }
    /**
     * 定义列表查询数据。
     * @return string
     */
    private function list_html()
    {
        return '<th data-field="id">角色ID</th><th data-field="role_name">角色名称</th><th data-field="operate">操作</th>';
    }

    /**
     * 定义验证规则
     * @param $request_name
     * @return array
     */
    private function rules($request_name='add')
    {
        $data['add'] = [
            'role_name' => 'required',
        ];
        $data['edit'] = [
            'role_name' => 'required',
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
            'role_name.required' => '请填写角色名称',
        ];
        $data['edit'] = [
            'role_name.required' => '请填写角色名称',
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
            $role_name = $request->input('name');
            if ($role_name) $where[] = ['role_name', 'like', '%'.$role_name.'%'];$infos['name'] = $role_name;
            $ret = $this->get_lists($request,$this->list_html(),$where);
            foreach($ret['rows'] as $key=>$vo){
                // 不允许操作超级管理员
                if(isset($vo['id'])&&$vo['id']==1) $ret['rows'][$key]['operate']='';
                else $ret['rows'][$key]['operate'] = showOperate($this->makeButton($vo['id']));
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
            return $this->get_add($param,$this->rules('add'),$this->messages('add'));
        }
        return $this->get_add($param,$this->rules('add'),$this->messages('add'));
    }
    /**
     * 编辑数据
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Contracts\View\Factory|\Illuminate\Foundation\Application|\Illuminate\View\View
     */
    public function edit(Request $request){
        $param = $request->all();
        if($param['id']==1) return j_error('非法操作');
        return $this->get_edit($param,$this->rules('edit'),$this->messages('edit'));
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
     * 分配权限
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function give_access(Request $request)
    {
        $param = $request->all('');
        // 获取现在的权限
        if('get' == $param['type']){
            $nodeStr = (new NodeModel())->getNodeInfo($param['id']);
            return j_success('获取成功',$nodeStr);
        }
        // 分配新权限
        if('give' == $param['type']){
            $give_data = ['rule' => $param['rule']];
            $flag = $this->model_name->update_data($param['id'],$give_data);
            if(!$flag) return j_error('分配失败');
            return  j_success('分配成功');
        }
        return j_error('请求出错');
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
                'btnStyle' => 'danger','icon' => 'fa fa-trash-o'],
            '分配权限' => ['href' => "javascript:giveQx(" .$id .")",
                'btnStyle' => 'info',
                'icon' => 'fa fa-institution']];
    }
}
