<?php
/**
 * Class AdminModel
 * @package App\Model
 */
namespace App\Model;

class AdminModel extends BaseModel
{
    protected $table='seek_admin';
    protected $fillable=['username','password','login_num','last_login_ip','last_login_time','realname','status','role_id'];
    protected $casts = [];
    protected $dateFormat = 'U';
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    /**
     * 获取器-最后登录时间
     * @return string
     */
    public function  getLastLoginTimeAttribute(){
        return date('Y-m-d H:i:s',$this->attributes['last_login_time']);
    }
    /**
     * 获取器-状态
     * @return false|string
     */
    public function  getStatusAttribute(){
        if(in_array($this->attributes['status'],['1','-1'])){
            $data = ['1'=>'<span style="color: #1ab394;">正常</span>','-1'=>'<span style="color: red;">停用</span>'];
            $data =$data[$this->attributes['status']];
        } else {
            $data = $this->attributes['status'];
        }
        return $data;
    }
    /* ++++++++++ 关联角色 ++++++++++ */
    public function role(){
        return $this->hasOne('App\Model\RoleModel','id','role_id')->withDefault();
    }
}
