<?php
/**
 * Class BaseModel 公用模型
 * @package App\Model
 */
namespace App\Model;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class BaseModel extends Model
{
    protected $SuccessMsg;
    protected $ErrorMsg;
    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $this->SuccessMsg = '';
        $this->ErrorMsg = '';
    }

    /**
     * 获取列表分页数据
     * @param array|string $filed 字段[默认全部]
     * @param array $where 查询条件
     * @param array $orderby 排序【默认ID倒序】
     * @param array $limit 分页【默认100条】
     * @param bool $array 是否转换数组【默认转数组】
     * @param array $with 关联模型
     * @return array $data 数据
     */
    public static function get_data_page_list($where=[],$filed='*',$orderby=['id','desc'],$limit=[0,100],$array=true,$with=[]){
        if(empty($with)) $ret = self::query()->select($filed)->where($where)->orderBy($orderby[0],$orderby[1])->offset($limit[0])->limit($limit[1])->get();
        else $ret = self::query()->select($filed)->where($where)->with($with)->orderBy($orderby[0],$orderby[1])->offset($limit[0])->limit($limit[1])->get();
        $data = isset($ret)?$ret:[];
        if($ret&&$array==true) $data = $ret->toArray();
        return $data;
    }

    /**
     * 获取数据总条数
     * @param array $where 查询条件
     * @return int 查询数量
     */
    public static function get_data_count($where=[]){
        return self::query()->where($where)->count();
    }

    /**
     * 获取所有数据
     * @param array $where 查询条件
     * @param array|string $filed 字段[默认全部]
     * @param array $orderby 排序【默认ID倒序】
     * @param bool $array 是否转换数组【默认转数组】
     * @param array $with 关联模型
     * @return mixed
     */
    public static function get_all_data($where=[],$filed='*',$orderby=['id','desc'],$array=true,$with=[]){
        if(empty($with)) $ret =self::query()->select($filed)->where($where)->orderBy($orderby[0],$orderby[1])->get();
        else $ret =self::query()->select($filed)->where($where)->with($with)->orderBy($orderby[0],$orderby[1])->get();
        $data = isset($ret)?$ret:[];
        if($ret&&$array==true) $data = $ret->toArray();
        return $data;
    }

    /**
     * 获取一条数据
     * @param array|string $filed 字段[默认全部]
     * @param array|string|int $where 查询条件
     * @param bool $array 是否转换数组【默认转数组】
     * @param array $with 关联模型
     * @return mixed
     */
    public static function get_one_data($where=[],$filed='*',$array=true,$with=[]){
        $data = [];
        if(is_array($where)) if(empty($with)){$data = self::query()->select($filed)->where($where)->first();}else{$data = self::query()->select($filed)->where($where)->with($with)->first();}
        else if(is_string($where)||is_int($where)) if(empty($with)){$data = self::query()->select($filed)->find($where);}else{$data = self::query()->select($filed)->with($with)->find($where);}
        if($data&&$array==true) $data = $data->toArray();
        return $data;
    }

    /**
     * 获取某个值
     * @param array $where 查询条件
     * @param string $filed 字段
     * @return string
     */
    public static function get_one_value($where=[],$filed='id'){
        if(is_array($where)) $data=self::query()->where($where)->value($filed);
        else $data=self::query()->where('id',$where)->value($filed);
        return $data;
    }

    /**
     * 获取一列的值
     * @param array|int $where 查询条件
     * @param string $filed 字段值[默认ID]
     * @param string $filed_key 字段键名[默认ID]
     * @param bool $has_key 是否含有键名[默认不包含]
     * @return array
     */
    public static function get_one_pluck($where=[],$filed='id',$filed_key='id',$has_key=false){
        if(is_string($where)||is_int($where)) $where[] = ['id',$where];
        if(true===$has_key) $data=self::query()->where($where)->pluck($filed,$filed_key);
        else $data=self::query()->where($where)->pluck($filed);
        if($data) $data = $data->toArray();
        return $data;
    }

    /**
     * 添加数据
     * @param array $data 数据
     * @return bool
     */
    public function add_data($data)
    {
        $this->fill($data);
        $this->save();
        return true;
    }

    /**
     * 修改数据
     * @param string|array $where 条件
     * @param array $data 数据
     * @return bool
     */
    public static function update_data($where,$data)
    {
        $model = self::get_one_data($where,['id'],false);
        if(!$model) return false;
        $model->fill($data);
        $model->save();
        if(blank($model)) return false;
        return true;
    }

    /**
     * 删除数据
     * @param string|array $where 条件
     * @param bool $is_all 是否批量删除
     * @return bool
     */
    public static function del_data($where,$is_all=false)
    {
        $model = self::get_one_data($where,['id'],false);
        if(!$model) return false;
        $model->delete();
        if(blank($model)) return false;
        return true;
    }

    /**
     * 获取器-创建时间
     * @return false|string
     */
    public function  getCreatedAtAttribute(){
        return date('Y-m-d H:i:s',$this->attributes['created_at']);
    }

    /**
     * 获取器-更新时间
     * @return false|string
     */
    public function  getUpdatedAtAttribute(){
        return date('Y-m-d H:i:s',$this->attributes['updated_at']);
    }

    /**
     * 开启sql调试
     * @return mixed
     */
    public static function start_sql_log(){
        return DB::connection()->enableQueryLog();
    }

    /**
     * 打印sql信息
     * @return mixed
     */
    public static function print_sql_log(){
        return DB::getQueryLog();
    }

    /**
     * 获取成功返回信息
     * @return string
     */
    public function GetSuccessMsg(){
        return $this->SuccessMsg;
    }

    /**
     * 获取失败返回信息
     * @return string
     */
    public function GetErrorMsg(){
        return $this->ErrorMsg;
    }
}
