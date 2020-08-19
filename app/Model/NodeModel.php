<?php
/**
 * Class NodeModel
 * @package App\Model
 */
namespace App\Model;

use Illuminate\Support\Facades\DB;

class NodeModel extends BaseModel
{
    protected $table='seek_node';
    protected $fillable=['node_name','control_name','action_name','is_menu','pid','style','sort'];
    protected $casts = [];
    protected $dateFormat = 'U';
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    /**
     * 获取节点数据
     * @param $id
     * @return string
     */
    public function getNodeInfo($id)
    {
        $result = $this->get_all_data([],['id','node_name','pid']);
        $str = '';
        $role = new RoleModel();
        $rule = $role->getRuleById($id);
        if(!empty($rule)) $rule = explode(',', $rule);
        foreach($result as $key=>$vo){
            $str .= '{ "id": "' . $vo['id'] . '", "pId":"' . $vo['pid'] . '", "name":"' . $vo['node_name'].'"';
            if(!empty($rule) && in_array($vo['id'], $rule)) $str .= ' ,"checked":1';
            $str .= '},';
        }
        return '[' . rtrim($str, ',') . ']';
    }

    /**
     * 根据节点数据获取对应的菜单
     * @param string $nodeStr
     * @return array
     */
    public function getMenu($nodeStr = '')
    {
        if(empty($nodeStr)) return [];
        // 超级管理员没有节点数组 * 号表示
        $where = '*' == $nodeStr ? 'is_menu = 2' : 'is_menu = 2 and id in(' . $nodeStr . ')';
        $result = $this->select(['id','node_name','pid','control_name','action_name','route_name','style'])
            ->whereRaw($where)->orderBy('sort','asc')->get();
        return prepareMenu($result);
    }

    /**
     * 根据条件获取访问权限节点数据
     * @param $where
     * @return mixed
     */
    public function getActions($where)
    {
        return $this->get_all_data($where,['control_name','action_name']);
    }
}
