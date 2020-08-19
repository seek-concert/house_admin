<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{$module_name}}列表</title>
    <link rel="shortcut icon" href="{{asset('favicon.ico')}}">
    {{--  csrf令牌 --}}
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="{{asset('static/admin/css/bootstrap.min.css?v=3.3.6')}}" rel="stylesheet">
    <link href="{{asset('static/admin/css/font-awesome.min.css?v=4.4.0')}}" rel="stylesheet">
    <link href="{{asset('static/admin/css/plugins/bootstrap-table/bootstrap-table.min.css')}}" rel="stylesheet">
    <link href="{{asset('static/admin/css/animate.min.css')}}" rel="stylesheet">
    <link href="{{asset('static/admin/css/style.min.css?v=4.1.0')}}" rel="stylesheet">
    <link href="{{asset('static/admin/css/plugins/sweetalert/sweetalert.css')}}" rel="stylesheet">
</head>
<body class="gray-bg">
<div class="wrapper wrapper-content animated fadeInRight">
    <!-- Panel Other -->
    <div class="ibox float-e-margins">
        <div class="ibox-title">
            <h5>{{$module_name}}列表</h5>
        </div>
        <div class="ibox-content">
            <div class="form-group clearfix col-sm-1">
                <a href="{{$add_url}}">
                    <button class="btn btn-outline btn-primary" type="button">添加{{$module_name}}</button>
                </a>
            </div>
            <!--搜索框开始-->
            <form id='commentForm' role="form" method="post" class="form-inline pull-right">
                <div class="content clearfix m-b">
                    <div class="form-group">
                        <label>{{$module_name}}名称：</label>
                        <input type="text" class="form-control" id="name" name="name">
                    </div>
                    <div class="form-group">
                        <button class="btn btn-primary" type="button" style="margin-top:5px" id="search"><strong>搜 索</strong>
                        </button>
                    </div>
                </div>
            </form>
            <!--搜索框结束-->
            <div class="example-wrap">
                <div class="example">
                    <table id="cusTable">
                        <thead>{!! $filed_list !!}</thead>
                    </table>
                </div>
            </div>
            <!-- End Example Pagination -->
        </div>
    </div>
</div>
<!-- 角色分配 -->
<div class="zTreeDemoBackground left" style="display: none" id="role">
    <input type="hidden" id="nodeid">
    <div class="form-group">
        <div class="col-sm-5 col-sm-offset-2">
            <ul id="treeType" class="ztree"></ul>
        </div>
    </div>
    <div class="form-group">
        <div class="col-sm-4 col-sm-offset-4" style="margin-bottom: 15px">
            <input type="button" value="确认分配" class="btn btn-primary" id="postform"/>
        </div>
    </div>
</div>
<script type="text/javascript">
    zNodes = '';
</script>
<!-- End Panel Other -->
<script src="{{asset('static/admin/js/jquery.min.js?v=2.1.4')}}"></script>
<script src="{{asset('static/admin/js/bootstrap.min.js?v=3.3.6')}}"></script>
<script src="{{asset('static/admin/js/content.min.js?v=1.0.0')}}"></script>
<script src="{{asset('static/admin/js/plugins/bootstrap-table/bootstrap-table.min.js')}}"></script>
<script src="{{asset('static/admin/js/plugins/bootstrap-table/bootstrap-table-mobile.min.js')}}"></script>
<script src="{{asset('static/admin/js/plugins/bootstrap-table/locale/bootstrap-table-zh-CN.min.js')}}"></script>
<script src="{{asset('static/admin/js/plugins/suggest/bootstrap-suggest.min.js')}}"></script>
<script src="{{asset('static/admin/js/plugins/layer/laydate/laydate.js')}}"></script>
<script src="{{asset('static/admin/js/plugins/sweetalert/sweetalert.min.js')}}"></script>
<script src="{{asset('static/admin/js/plugins/layer/layer.min.js')}}"></script>
{{--引入树插件--}}
<link rel="stylesheet" href="{{asset('static/admin/js/plugins/zTree/zTreeStyle.css')}}" type="text/css">
<script type="text/javascript" src="{{asset('static/admin/js/plugins/zTree/jquery.ztree.core-3.5.js')}}"></script>
<script type="text/javascript" src="{{asset('static/admin/js/plugins/zTree/jquery.ztree.excheck-3.5.js')}}"></script>
<script type="text/javascript" src="{{asset('static/admin/js/plugins/zTree/jquery.ztree.exedit-3.5.js')}}"></script>
<script type="text/javascript">
    $.ajaxSetup({
        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')}
    });
    function initTable() {
        //先销毁表格
        $('#cusTable').bootstrapTable('destroy');
        //初始化表格,动态从服务器加载数据
        $("#cusTable").bootstrapTable({
            method: "get",  //使用get请求到服务器获取数据
            url: "{{$list_url}}", //获取数据的地址
            striped: true,  //表格显示条纹
            pagination: true, //启动分页
            pageSize: 10,  //每页显示的记录数
            pageNumber:1, //当前第几页
            pageList: [5, 10, 15, 20, 25],  //记录数可选列表
            sidePagination: "server", //表示服务端请求
            paginationFirstText: "首页",
            paginationPreText: "上一页",
            paginationNextText: "下一页",
            paginationLastText: "尾页",
            queryParamsType : "undefined",
            queryParams: function queryParams(params) {   //设置查询参数
                var param = {
                    pageNumber: params.pageNumber,
                    pageSize: params.pageSize,
                    name:$('#name').val()
                };
                return param;
            },
            onLoadSuccess: function(res){  //加载成功时执行
                if(0 == res.code){window.location.reload();}
                layer.msg("加载成功", {time : 1000});
            },
            onLoadError: function(){  //加载失败时执行
                layer.msg("加载数据失败");
            }
        });
    }
    $(document).ready(function () {
        //调用函数，初始化表格
        initTable();
        //当点击查询按钮的时候执行
        $("#search").bind("click", initTable);
    });
    function del(id){
        layer.confirm('确认删除此{{$module_name}}?', {icon: 3, title:'提示'}, function(index){
            //do something
            $.getJSON("{{$del_url}}", {'id' : id}, function(res){
                if(0 == res.code){
                    layer.alert(res.msg, {title: '友情提示', icon: 1, closeBtn: 0}, function(){initTable();});
                }else if(111 == res.code){window.location.reload();
                }else{layer.alert(res.msg, {title: '友情提示', icon: 2});}
            });
            layer.close(index);
        })

    }
    var index = '';
    var index2 = '';
    //分配权限
    function giveQx(id){
        $("#nodeid").val(id);
        //加载层
        index2 = layer.load(0, {shade: false}); //0代表加载的风格，支持0-2
        // 获取权限信息
        $.getJSON("{{route('g_role_give_access')}}", {'type' : 'get', 'id' : id}, function(res){
            layer.close(index2);
            if(0 == res.code){
                zNodes = JSON.parse(res.data);  //将字符串转换成obj
                console.log(zNodes);
                //页面层
                index = layer.open({
                    type: 1, area:['350px', '400px'], title:'权限分配',
                    skin: 'layui-layer-demo', content: $('#role')
                });
                //设置zetree
                var setting = {
                    check:{enable:true},
                    data: {simpleData: {enable: true}}
                };
                $.fn.zTree.init($("#treeType"), setting, zNodes);
                var zTree = $.fn.zTree.getZTreeObj("treeType");
                zTree.expandAll(true);
            }else if(111 == res.code){window.location.reload();
            }else{layer.alert(res.msg, {title: '友情提示', icon: 2});}
        });
    }
    //确认分配权限
    $("#postform").click(function(){
        var zTree = $.fn.zTree.getZTreeObj("treeType");
        var nodes = zTree.getCheckedNodes(true);
        var NodeString = '';
        $.each(nodes, function (n, value) {
            if(n>0){NodeString += ',';}
            NodeString += value.id;
        });
        var id = $("#nodeid").val();
        //写入库
        $.post("{{route('g_role_give_access')}}", {'type' : 'give', 'id' : id, 'rule' : NodeString}, function(res){
            layer.close(index);
            if(0 == res.code){layer.alert(res.msg, {title: '友情提示', icon: 1, closeBtn: 0}, function(){initTable();});
            }else if(111 == res.code){window.location.reload();
            }else{layer.alert(res.msg, {title: '友情提示', icon: 2});}
        },'json')
    })
</script>
</body>
</html>
