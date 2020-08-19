<?php
/**
 * Class BaseController  后台数据操作基类
 * @package app\admin\controller
 */
namespace App\Http\Controllers\admin;

use Illuminate\Support\Facades\Validator;

class BaseController extends AuthController
{
    /**
     * 模型名称
     * @var $model_name
     */
    protected $model_name;
    /**
     * 控制器名称
     * @var $contr_name
     */
    protected $contr_name;
    /**
     * 模块名称
     * @var $module_name
     */
    protected $module_name;
    /**
     * 字段数组
     * @var $filed_arr
     */
    protected $filed_arr;
    /**
     * BaseController constructor.
     */
    public function __construct()
    {
        parent::__construct();
    }
    /**
     * 获取列表数据
     * @param $request
     * @param string $list_html 列表查询html
     * @param array $where 查询条件
     * @param array $with 关联模型
     * @param array $data 模板数据
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Foundation\Application|\Illuminate\View\View
     */
    public function get_lists($request,$list_html,$where=[],$with=[],$data=[]){
        if(request()->ajax()) {
            /* ********** 排序 ********** */
            $ordername = $request->input('ordername');
            $ordername = $ordername ? $ordername : 'id';
            $infos['ordername'] = $ordername;
            $orderby = $request->input('orderby');
            $orderby = $orderby ? $orderby : 'asc';
            $infos['orderby'] = $orderby;
            /* ********** 每页条数 ********** */
            $limit = $request->input('pageSize');
            $limit = $limit ? $limit : 10;/* 条数 */
            $infos['limit'] = $limit;
            $offset = ($request->input('pageNumber') - 1) * $limit;
            $offset = $offset ? $offset : 0;/* 起始位 */
            $infos['offset'] = $offset;
            $selectResult = $this->model_name->get_data_page_list($where, $this->filed_arr, [$ordername, $orderby], [$offset, $limit],true,$with);
            $infos['rows'] = $selectResult;
            $count = $this->model_name->get_data_count($where);
            $infos['total'] = $count;
            return $infos;
        }
        $infos['module_name'] = $this->module_name;
        $infos['list_url'] = $this->get_route_url('lists');
        $infos['add_url'] = $this->get_route_url('add');
        $infos['del_url'] = $this->get_route_url('del');
        $infos['filed_list'] = $list_html;
        foreach ($data as $key=>$val){$infos[$key] = $val;}
        return view('admin.'.$this->contr_name.'.lists')->with($infos);
    }
    /**
     * 添加数据
     * @param array $param 数据数组
     * @param array $rules 验证规则
     * @param array $messages 验证规则错误信息
     * @param array $data 模板数据
     * @return  \Illuminate\Http\JsonResponse|\Illuminate\Contracts\View\Factory|\Illuminate\Foundation\Application|\Illuminate\View\View
     */
    public function get_add($param,$rules=[],$messages=[],$data=[]){
        if(request()->isMethod('post')) {
            /* ********** 数据验证 ********** */
            $validator = Validator::make($param,$rules,$messages);
            if($validator->fails()){return j_error($validator->errors()->first());}
            $ret = $this->model_name->add_data($param);
            if(!$ret) return j_error($this->get_msg('添加',1));
            return j_success($this->get_msg('添加',0),'',$this->get_route_url('lists'));
        }
        $infos['list_url'] = $this->get_route_url('lists');
        $infos['module_name'] = $this->module_name;
        $infos['add_url'] = $this->get_route_url('add');
        foreach ($data as $key=>$val){$infos[$key] = $val;}
        return view('admin.'.$this->contr_name.'.add')->with($infos);
    }
    /**
     * 编辑数据
     * @param array $param 数据数组
     * @param array $rules 验证规则
     * @param array $messages 验证规则错误信息
     * @param array $data 模板数据
     * @return  \Illuminate\Http\JsonResponse|\Illuminate\Contracts\View\Factory|\Illuminate\Foundation\Application|\Illuminate\View\View
     */
    public function get_edit($param,$rules=[],$messages=[],$data=[]){
        if(!$param['id']) return j_error('请选择一条数据');
        if(request()->isMethod('post')) {
            /* ********** 数据验证 ********** */
            $validator = Validator::make($param,$rules,$messages);
            if($validator->fails()){return j_error($validator->errors()->first());}
            $ret = $this->model_name->update_data($param['id'],$param);
            if(!$ret) return j_error($this->get_msg('编辑',1));
            return j_success($this->get_msg('编辑',0),'',$this->get_route_url('lists'));
        }
        $infos['infos'] = $this->model_name->get_one_data($param['id'],$this->filed_arr,false);
        $infos['list_url'] = $this->get_route_url('lists');
        $infos['module_name'] = $this->module_name;
        $infos['edit_url'] = $this->get_route_url('edit');
        foreach ($data as $key=>$val){$infos[$key] = $val;}
        return view('admin.'.$this->contr_name.'.edit')->with($infos);
    }
    /**
     * 删除数据
     * @param array $param 数据数组
     * @return \Illuminate\Http\JsonResponse
     */
    public function get_del($param){
        if(!$param['id']) return j_error('请选择一条数据');
        if(request()->isMethod('get')) {
            $ret = $this->model_name->del_data($param['id']);
            if(!$ret) return j_error($this->get_msg('删除',1));
            return j_success($this->get_msg('删除',0),'',$this->get_route_url('lists'));
        }else return j_error('网络异常,请稍后再试！');
    }

    /**
     * 获取提示
     * @param string $name
     * @param string|int $code
     * @return string
     */
    public function get_msg($name,$code=1){
        if($code==1) $msg = '失败';else $msg = '成功';
        return $name.$this->module_name.$msg;
    }

    /**
     * 获取路由地址
     * @param string $name
     * @return string
     */
    public function get_route_url($name){
        return route('g_'.$this->contr_name.'_'.$name);
    }
}
