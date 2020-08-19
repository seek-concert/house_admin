<?php
/**
 * Class DataController  数据备份、还原
 * @package App\Http\Controllers\admin
 */
namespace App\Http\Controllers\admin;
use App\Model\NodeModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DataController extends BaseController
{
    /**
     * DataController constructor.
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * 备份首页列表
     * @return mixed
     */
    public function lists()
    {
        mkdirs(public_path('back'));
        $tables = DB::select('show tables');
        $tables = objToArray($tables);
        foreach ($tables as $key => $vo) {
            $sql = "select count(0) as alls from " . $vo['Tables_in_' . env('DB_DATABASE')];
            $tables[$key]['alls'] = objToArray(DB::select($sql))['0']['alls'];
            $table = $vo['Tables_in_' . env('DB_DATABASE')];
            $tables[$key]['operate'] = showOperate($this->makeButton($table));

            if (file_exists(public_path('back/'.$vo['Tables_in_' . env('DB_DATABASE')] . ".sql") )) {
                $tables[$key]['ctime'] = date('Y-m-d H:i:s', filemtime(public_path('back/' . $vo['Tables_in_' .
                    env('DB_DATABASE')] . ".sql")));
            } else {
                $tables[$key]['ctime'] = '无';
            }

        }
        return view('admin.data.lists')->with(['tables' => $tables]);
    }


    /**
     * 备份数据
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function import_data(Request $request)
    {
        set_time_limit(0);
        $table = $request->input('table');
        $sqlStr = "SET FOREIGN_KEY_CHECKS=0;\r\n";
        $sqlStr .= "DROP TABLE IF EXISTS `$table`;\r\n";
        $create = objToArray(DB::select('show create table ' . $table));
        $sqlStr .= $create['0']['Create Table'] . ";\r\n";
        $sqlStr .= "\r\n";
        $result = objToArray(DB::select('select * from ' . $table));
        foreach($result as $key=>$vo){
            $keys = array_keys($vo);
            $keys = array_map('addslashes', $keys);
            $keys = join('`,`', $keys);
            $keys = "`" . $keys . "`";
            $vals = array_values($vo);
            $vals = array_map('addslashes', $vals);
            $vals = join("','", $vals);
            $vals = "'" . $vals . "'";
            $sqlStr .= "insert into `$table`($keys) values($vals);\r\n";
        }
        $filename = public_path('back/'.$table . ".sql");
        $fp = fopen($filename, 'w');
        fputs($fp, $sqlStr);
        fclose($fp);
        return api_json(0,'success');
    }

    /**
     * 还原数据
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function back_data(Request $request)
    {
        set_time_limit(0);
        $table = $request->input('table');
        if(!file_exists(public_path('back/'.$table . ".sql"))){
            return api_json(-1,'备份数据不存在!');
        }
        $sqls = analysisSql(public_path( 'back/'.$table . ".sql"));
        foreach($sqls as $key=>$sql){
            objToArray(DB::select($sql));
        }
        return api_json(0,'success');
    }

    /**
     * 拼装操作按钮
     * @param $table
     * @return array
     */
    private function makeButton($table)
    {
        return [
            '备份' => [
                'auth' => 'data/importdata',
                'href' => "javascript:importData('" .$table ."')",
                'btnStyle' => 'primary',
                'icon' => 'fa fa-tasks'
            ],
            '还原' => [
                'auth' => 'data/backdata',
                'href' => "javascript:backData('" .$table ."')",
                'btnStyle' => 'info',
                'icon' => 'fa fa-retweet'
            ]
        ];
    }
}
