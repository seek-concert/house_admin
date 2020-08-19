<?php
/**
 * Class RoleModel
 * @package App\Model
 */
namespace App\Model;

class RoleModel extends BaseModel
{
    protected $table='seek_role';
    protected $fillable=['role_name','rule'];
    protected $casts = [];
    protected $dateFormat = 'U';
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';
    /**
     * 获取角色的权限节点
     * @param int|string $id 角色ID
     * @return string
     */
    public function getRuleById($id)
    {
        return $this->get_one_value($id,'rule');
    }

    /**
     * 获取角色信息
     * @param $id
     * @return mixed
     */
    public function getRoleInfo($id)
    {
        $result = $this->get_one_data($id,'*');
        // 超级管理员权限是 *
        if(empty($result['rule'])){
            $result['action'] = '';
            return $result;
        }else if('*' == $result['rule']){
            $where = [];
        }else{
            $where[] = ['id','in',$result['rule']];
        }
        // 查询权限节点
        $nodeModel = new NodeModel();
        $res = $nodeModel->getActions($where);
        foreach($res as $key=>$vo){
            if('#' != $vo['action_name']){
                $result['action'][] = $vo['control_name'] . '/' . $vo['action_name'];
            }
        }
        return $result;
    }
}
