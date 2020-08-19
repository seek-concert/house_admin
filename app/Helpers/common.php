<?php
/*
|--------------------------------------------------------------------------
| 自定义常用函数库
|--------------------------------------------------------------------------
*/
/** 批量 更新或插入数据的sql
 * @param string $table         数据表名
 * @param array $insert_columns 数据字段
 * @param array $values         原始数据
 * @param array|string $update_columns 更新字段
 * @return bool|array          返回false(条件不符)，返回array(sql语句)
 * 示例：['laravel_table',['id','name'],[0=>['id'=>1,'name'=>'data1'],1=>['id'=>2,'name'=>'data2']],['id','name']]
 */
function batch_update_or_insert_sql($table='', $insert_columns=[], $values=[], $update_columns=[]){
    if(empty($table) || empty($insert_columns) || empty($values) || empty($update_columns)){
        return false;
    }
    // 数据字段必须包含更新字段
    if(is_string($update_columns)){
        if(!in_array($update_columns,$insert_columns)){
            return false;
        }
    }else{
        $common_columns= array_intersect($insert_columns,$update_columns);
        sort($common_columns);
        sort($update_columns);
        if($common_columns != $update_columns){
            return false;
        }
    }
    //数据字段
    $sql_insert_columns=[];
    foreach ($insert_columns as $insert_column){
        $sql_insert_columns[]='`'.$insert_column.'`';
    }
    $sql_insert_columns=implode(',',$sql_insert_columns);
    //数据分页
    $num=100;
    $page_values=[];
    foreach ($values as $k=>$value){
        $p=ceil(($k+1)/$num);
        $temp_values=[];
        foreach ($insert_columns as $insert_column){
            $temp=isset($value[$insert_column]) && !is_null($value[$insert_column])?(string)$value[$insert_column]:null;
            $temp_values[]="'".$temp."'";
        }
        $temp_values=implode(',',$temp_values);
        $page_values[$p][]='('.$temp_values.')';
    }
    //更新字段
    if(is_string($update_columns)){
        $sql_update_columns= ' `'.$update_columns.'` = values(`'.$update_columns.'`) ';
    }else{
        $sql_update_columns=[];
        foreach ($update_columns as $update_column){
            $sql_update_columns[]= ' `'.$update_column.'` = values(`'.$update_column.'`) ';
        }
        $sql_update_columns=implode(',',$sql_update_columns);
    }
    // 生成sql
    $sqls=[];
    foreach($page_values as $p=>$value){
        $sql_values=implode(',',$value);
        $sqls[]='insert into `'.$table.'` ('.$sql_insert_columns.') values '.$sql_values.' on duplicate key update '.$sql_update_columns;
    }

    return $sqls;
}

/** 批量更新数据 sql
 * @param string $table         数据表名
 * @param array $insert_columns 数据字段
 * @param array $values         原始数据
 * @param array|string $update_columns  更新字段
 * @param array|string $where_columns   条件字段
 * @return bool|string          返回false(条件不符)，返回string(sql语句)
 */
function batch_update_sql($table='', $insert_columns=[], $values=[], $update_columns=[], $where_columns='id'){
    if(empty($table) || empty($insert_columns) || empty($values) || empty($update_columns) || empty($where_columns)){
        return false;
    }
    // 数据字段必须包含更新字段
    if(is_string($update_columns)){
        if(!in_array($update_columns,$insert_columns)){
            return false;
        }
    }else{
        $common_columns= array_intersect($insert_columns,$update_columns);
        sort($common_columns);
        sort($update_columns);
        if($common_columns != $update_columns){
            return false;
        }
    }
    // 数据字段必须包含条件字段
    if(is_string($where_columns)){
        if(!in_array($where_columns,$insert_columns)){
            return false;
        }
    }else{
        $common_columns= array_intersect($insert_columns,$where_columns);
        sort($common_columns);
        sort($where_columns);
        if($common_columns != $where_columns){
            return false;
        }
    }
    //数据字段
    $sql_insert_columns=[];
    foreach ($insert_columns as $insert_column){
        $sql_insert_columns[]='`'.$insert_column.'`';
    }
    $sql_insert_columns=implode(',',$sql_insert_columns);
    /* ++++++++++ 创建虚拟表 ++++++++++ */
    //创建虚拟表 表名
    $temp_table='`'.$table.'_temp`';
    //创建虚拟表 sql
    $sqls[]='create temporary table '.$temp_table.' as ( select '.$sql_insert_columns.' from `'.$table.'` where 1<>1 )';

    /* ++++++++++ 添加数据 ++++++++++ */
    //数据分页
    $num=100;
    $page_values=[];
    foreach ($values as $k=>$value){
        $p=ceil(($k+1)/$num);
        $temp_values=[];
        foreach ($insert_columns as $insert_column){
            $temp=isset($value[$insert_column]) && !is_null($value[$insert_column])?(string)$value[$insert_column]:null;
            $temp_values[]="'".$temp."'";
        }
        $temp_values=implode(',',$temp_values);
        $page_values[$p][]='('.$temp_values.')';
    }
    //插入数据 sql
    foreach($page_values as $p=>$value){
        $sql_values=implode(',',$value);
        $sqls[]='insert into '.$temp_table.' ('.$sql_insert_columns.') values '.$sql_values;
    }
    /* ++++++++++ 批量更新 ++++++++++ */
    //更新字段
    if(is_string($update_columns)){
        $sql_update_columns= '`'.$table.'`.`'.$update_columns.'`='.$temp_table.'.`'.$update_columns.'`';
    }else{
        $sql_update_columns=[];
        foreach ($update_columns as $update_column){
            $sql_update_columns[]= '`'.$table.'`.`'.$update_column.'`='.$temp_table.'.`'.$update_column.'`';
        }
        $sql_update_columns=implode(',',$sql_update_columns);
    }
    //条件字段
    if(is_string($where_columns)){
        $sql_where_columns= '`'.$table.'`.`'.$where_columns.'`='.$temp_table.'.`'.$where_columns.'`';
    }else{
        $sql_where_columns=[];
        foreach ($where_columns as $where_column){
            $sql_where_columns[]= '`'.$table.'`.`'.$where_column.'`='.$temp_table.'.`'.$where_column.'`';
        }
        $sql_where_columns=implode(' and ',$sql_where_columns);
    }
    //更新数据 sql
    $sqls[]='update `'.$table.'`,'.$temp_table.' set '.$sql_update_columns.' where '.$sql_where_columns;

    return $sqls;
}

/** 生成GUID
 * @return string
 */
function create_guid(){
    $char_id = strtoupper(md5(uniqid(mt_rand(), true)));
    $hyphen = chr(45);// "-"
    $GUID = substr($char_id, 6, 2).substr($char_id, 4, 2).
        substr($char_id, 2, 2).substr($char_id, 0, 2).$hyphen
        .substr($char_id, 10, 2).substr($char_id, 8, 2).$hyphen
        .substr($char_id,14, 2).substr($char_id,12, 2).$hyphen
        .substr($char_id,16, 4).$hyphen.substr($char_id,20,12);
    return $GUID;
}
/**
 * 对象转换成数组
 * @param object|string $obj 需要转换的对象
 * @return array 数组
 */
function objToArray($obj)
{
    return json_decode(json_encode($obj), true);
}
/**
 * 统一返回信息
 * @param $code
 * @param $data
 * @param $msg
 * @return array
 */
function msg($code, $data, $msg)
{
    return compact('code', 'data', 'msg');
}
/**
 * [laravel5]返回自定义json
 * @param array $data 数组
 * @param string|int $status http状态
 * @return \Illuminate\Http\JsonResponse
 */
function json($data=[],$status='200'){
    return response()->json($data,$status);
}
/**
 * [laravel5]返回常用json
 * @param int|string $code 状态
 * @param string $msg 信息
 * @param array $data 数据数组
 * @param string|int $status http状态
 * @return \Illuminate\Http\JsonResponse
 */
function api_json($code=0,$msg='success',$data=[],$status='200'){
    return response()->json(['code'=>$code,'msg'=>$msg,'data'=>$data],$status);
}
/**
 * [laravel5]返回获取成功json
 * @param string $msg 信息
 * @param array $data 数据数组
 * @param string $url 跳转地址
 * @param int|string $code 状态
 * @param string|int $status http状态
 * @return \Illuminate\Http\JsonResponse
 */
function j_success($msg='success',$data=[],$url='',$code=0,$status='200'){
    return response()->json(['code'=>$code,'msg'=>$msg,'data'=>$data,'url'=>$url],$status);
}
/**
 * [laravel5]返回获取失败json
 * @param string $msg 信息
 * @param string $url 跳转地址
 * @param array $data 数据数组
 * @param int|string $code 状态
 * @param string|int $status http状态
 * @return \Illuminate\Http\JsonResponse
 */
function j_error($msg='error',$url='',$data=[],$code=1,$status='200'){
    return response()->json(['code'=>$code,'msg'=>$msg,'data'=>$data,'url'=>$url],$status);
}

/**
 * 删除数据中的空数组
 * @param  array $data 数组
 * @return mixed
 */
function del_null_arr($data){
    foreach ($data as $k => $v) {
        if (empty($v) && $v !='0') {
            unset($data[$k]);
        }
    }
    return $data;
}

/**
 * xml 转换成数组函数
 * @param  $simpleXmlElement
 * @return array
 */
function xmlToArray($simpleXmlElement)
{
    $simpleXmlElement = (array)$simpleXmlElement;
    foreach ($simpleXmlElement as $k => $v) {
        if ($v instanceof SimpleXMLElement || is_array($v)) {
            $simpleXmlElement[$k] = xmlToArray($v);
        }
    }
    return $simpleXmlElement;
}

/**
 * 生成唯一订单号
 * @return string
 */
function generate_out_trade_no(){
    $a = date('Ymd') . substr(implode(NULL, array_map('ord', str_split(substr(uniqid(), 7, 13), 1))), 0, 8);
    return 'F_' . $a . rand(10000000, 99999999);
}


/**
 * 检测文件夹是否存在 不存在则创建
 * @param string $dir
 * @param int $mode
 * @return string
 */
function mkdirs($dir, $mode = 0777)
{
    if (is_dir($dir) || @mkdir($dir, $mode)) return true;
    if (!mkdirs(dirname($dir), $mode)) return false;
    return @mkdir($dir, $mode);
}

