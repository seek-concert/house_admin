<?php
/**
 * Class HomeController  后台
 * @package App\Http\Controllers\admin
 */
namespace App\Http\Controllers\admin;
use App\Model\NodeModel;
use Illuminate\Support\Facades\DB;

class HomeController extends AuthController
{
    public function __construct()
    {
        parent::__construct();
    }
    /**
     * @return mixed
     */
    public function index()
    {
        // 获取权限菜单
        $node = new NodeModel();
        return view('admin.index')->with(['menu' => $node->getMenu(session('admin_user.rule'))])->with($this->user_info);
    }

    /**
     * 后台默认首页
     * @return mixed
     */
    public function home()
    {
        return view('admin.home.index');
    }
}
