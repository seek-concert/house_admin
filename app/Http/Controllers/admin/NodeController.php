<?php
/**
 * Class NodeController  节点
 * @package App\Http\Controllers\admin
 */
namespace App\Http\Controllers\admin;
use App\Model\NodeModel;
use App\Model\RoleModel;
use Illuminate\Http\Request;

class NodeController extends BaseController
{
    /**
     * NodeController constructor.
     */
    public function __construct()
    {
        parent::__construct();
        $this->contr_name = get_method(1);
        $this->module_name = '节点';
        $this->filed_arr = ['id','node_name','control_name','action_name','route_name','is_menu','pid','style','sort','created_at'];
        $this->model_name = new NodeModel();
    }


    /**
     * 定义验证规则
     * @param $request_name
     * @return array
     */
    private function rules($request_name='add')
    {
        $data['add'] = [
            'pid' => 'required',
            'node_name' => 'required',
            'control_name' => 'required',
            'action_name' => 'required',
            'is_menu' => 'required',
        ];
        $data['edit'] = [
            'node_name' => 'required',
            'control_name' => 'required',
            'action_name' => 'required',
            'is_menu' => 'required',
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
            'pid.required' => '请选择父级节点',
            'node_name.required' => '请填写节点名称',
            'control_name.required' => '请填写控制器名称',
            'action_name.required' => '请填写方法名称',
            'is_menu.required' => '请选择是否菜单项',
        ];
        $data['edit'] = [
            'node_name.required' => '请填写节点名称',
            'control_name.required' => '请填写控制器名称',
            'action_name.required' => '请填写方法名称',
            'is_menu.required' => '请选择是否菜单项',
        ];
        return $data[$request_name];
    }
    /**
     * 列表
     * @return mixed
     */
    public function lists(Request $request)
    {
        if(request()->ajax()){
            $nodes = $this->model_name->get_all_data([],['id','node_name as name','control_name','action_name','route_name','is_menu','pid','style','sort','created_at'],['sort','asc']);
            $nodes = getTree($nodes, true);
            return api_json(0,'success',$nodes);
        }
        return view('admin.node.lists');
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
     * 拼装操作按钮
     * @param $id
     * @return array
     */
    private function makeButton($id)
    {
        return ['编辑' => ['href' => route('g_'.$this->contr_name.'_edit', ['id' => $id]),
            'btnStyle' => 'primary','icon' => 'fa fa-paste'],
            '删除' => ['href' => 'javascript:del(' .$id .')',
                'btnStyle' => 'danger','icon' => 'fa fa-paste']];
    }
}
