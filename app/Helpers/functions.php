<?php
/*
|--------------------------------------------------------------------------
| 自定义函数
|--------------------------------------------------------------------------
*/

/** [laravel5]获取操作控制器和方法
 * @param int|string $type 1为获取控制器 2为获取操作方法 all为全部
 * @return array|string
 */
function get_method($type='all'){
    $ret = explode('@',class_basename(request()->route()->getActionName()));
    if($type&&$type==1) $ret = str_replace('Controller','',lcfirst($ret[0]));
    if($type&&$type==2) $ret = lcfirst($ret[1]);
    return $ret;
}

/**
 * [layui-admin后台]生成操作按钮
 * @param array $operate 操作按钮数组
 * @return string
 */
function showOperate($operate = [])
{
    if(empty($operate)){
        return '';
    }
    $option = '';
    foreach($operate as $key=>$vo){
        $option .= ' <a href="' . $vo['href'] . '"><button type="button" class="btn btn-' . $vo['btnStyle'] . ' btn-sm">'.
            '<i class="' . $vo['icon'] . '"></i> ' . $key . '</button></a>';
    }
    return $option;
}

/**
 * [layui-admin后台]生成操作按钮（带权限检测） 新页面打开
 * @param array $operate 操作按钮数组
 * @return string
 */
function showOperates($operate = [])
{
    if(empty($operate)){
        return '';
    }
    $option = '';
    foreach($operate as $key=>$vo){
        if(authCheck($vo['auth'])){
            $option .= ' <a href="' . $vo['href'] . '" target="_blank"><button type="button" class="btn btn-' . $vo['btnStyle'] . ' btn-sm">'.
                '<i class="' . $vo['icon'] . '"></i> ' . $key . '</button></a>';
        }
    }
    return $option;
}

/**
 * 将字符解析成数组
 * @param $str
 * @return array
 */
function parseParams($str)
{
    $arrParams = [];
    parse_str(html_entity_decode(urldecode($str)), $arrParams);
    return $arrParams;
}

/**
 * [layui-admin后台]子孙树 用于菜单整理
 * @param $param
 * @param int $pid
 * @return array
 */
function subTree($param, $pid = 0)
{
    static $res = [];
    foreach($param as $key=>$vo){

        if( $pid == $vo['pid'] ){
            $res[] = $vo;
            subTree($param, $vo['id']);
        }
    }
    return $res;
}

/**
 * [layui-admin后台]整理菜单住方法
 * @param $param
 * @return array
 */
function prepareMenu($param)
{
    $param = objToArray($param);
    $parent = []; //父类
    $child = [];  //子类
    foreach($param as $key=>$vo){

        if(0 == $vo['pid']){
            $vo['href'] = '#';
            $parent[] = $vo;
        }else{
			$vo['href'] = url('admin/'.$vo['control_name'].'_'.$vo['action_name'] ); //跳转地址
        //  $vo['href'] = url($vo['control_name'] .'/'. $vo['action_name']); //跳转地址
        //    $vo['href'] = url('admin/'.$vo['route_name'] ); //跳转地址
            $child[] = $vo;
        }
    }
    foreach($parent as $key=>$vo){
        foreach($child as $k=>$v){

            if($v['pid'] == $vo['id']){
                $parent[$key]['child'][] = $v;
            }
        }
    }
    unset($child);
    return $parent;
}

/**
 * [layui-admin后台]解析备份sql文件
 * @param $file
 * @return array
 */
function analysisSql($file)
{
    // sql文件包含的sql语句数组
    $sqls = array ();
    $f = fopen ( $file, "rb" );
    // 创建表缓冲变量
    $create = '';
    while ( ! feof ( $f ) ) {
        // 读取每一行sql
        $line = fgets ( $f );
        // 如果包含空白行，则跳过
        if (trim ( $line ) == '') {
            continue;
        }
        // 如果结尾包含';'(即为一个完整的sql语句，这里是插入语句)，并且不包含'ENGINE='(即创建表的最后一句)，
        if (! preg_match ( '/;/', $line, $match ) || preg_match ( '/ENGINE=/', $line, $match )) {
            // 将本次sql语句与创建表sql连接存起来
            $create .= $line;
            // 如果包含了创建表的最后一句
            if (preg_match ( '/ENGINE=/', $create, $match )) {
                // 则将其合并到sql数组
                $sqls [] = $create;
                // 清空当前，准备下一个表的创建
                $create = '';
            }
            // 跳过本次
            continue;
        }
        $sqls [] = $line;
    }
    fclose ( $f );
    return $sqls;
}

/**
 * [layui-admin后台]权限检测
 * @param $rule
 * @return bool
 */
function authCheck($rule)
{
    $control = explode('/', $rule)['0'];
    if(session('admin_user.rule')=='*') return true;
    if(in_array($control, ['login', 'index'])) return true;
    if(in_array($rule, session('admin_user.action')?:[])) return true;
    return false;
}

/**
 * [layui-admin后台]整理出tree数据 ---  layui tree
 * @param $pInfo
 * @param bool $spread
 * @return array
 */
function getTree($pInfo, $spread = true)
{
    $res = [];
    $tree = [];
    //整理数组
    foreach($pInfo as $key=>$vo){
        if($spread) $vo['spread'] = true; //默认展开
        $res[$vo['id']] = $vo;
        $res[$vo['id']]['children'] = [];
    }
    unset($pInfo);
    //查找子孙
    foreach($res as $key=>$vo){
        if(0 != $vo['pid']){
            $res[$vo['pid']]['children'][] = &$res[$key];
        }
    }
    //过滤杂质
    foreach( $res as $key=>$vo ){
        if(0 == $vo['pid']){
            $tree[] = $vo;
        }
    }
    unset( $res );
    return $tree;
}

/**
 * 获取时间数组
 * @param string $show_time 时间周期
 * @param string $time 开始时间
 * @param int $time_type 传入时间类型|返回时间格式（1-时间格式Y-m-d 2-时间戳）
 * @return array|bool
 */
function get_time_arr($show_time,$time='',$time_type=1){
    $data = [];
    if(empty($show_time)||!in_array($show_time,['一天','一周','一个月','三个月','半年','一年'])) return false;
    if($time_type==1){if(empty($time)) $time = strtotime(date('Y-m-d H:i:s')); else $time = strtotime($time);}
    else{if(empty($time)) $time =time();}
    switch ($show_time){
        case '一天':$end_time =date('Y-m-d H:i:s',strtotime('+1 day',$time));break;
        case '一周':$end_time =date('Y-m-d H:i:s',strtotime('+1 week',$time));break;
        case '一个月':$end_time =date('Y-m-d H:i:s',strtotime('+1 month',$time));break;
        case '三个月':$end_time =date('Y-m-d H:i:s',strtotime('+3 month',$time));break;
        case '半年':$end_time =date('Y-m-d H:i:s',strtotime('+6 month',$time));break;
        case '一年':$end_time =date('Y-m-d H:i:s',strtotime('+1 year',$time));break;
        default:$end_time = $time;break;
    }
    if($time_type==1)$time = date('Y-m-d H:i:s',$time);
    if($time_type==2)$end_time = strtotime($end_time);
    $data['start_time'] = $time;
    $data['end_time'] = $end_time;
    return $data;
}

/**
 * [laravel5]获取表字段
 * @param string $table_name 表名
 * @return array 表字段数组
 */
function get_field($table_name=''){
    $data = objToArray(\Illuminate\Support\Facades\DB::select('show COLUMNS FROM '.$table_name));
    $data = array_column($data, 'Field');
    return $data;
}

