<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title></title>
<link rel="shortcut icon" href="{{asset('favicon.ico')}}">
<link href="{{asset('static/admin/css/bootstrap.min.css?v=3.3.6')}}" rel="stylesheet">
<link href="{{asset('static/admin/css/font-awesome.min.css?v=4.4.0')}}" rel="stylesheet">
<link href="{{asset('static/admin/css/plugins/bootstrap-table/bootstrap-table.min.css')}}" rel="stylesheet">
<link href="{{asset('static/admin/css/animate.min.css')}}" rel="stylesheet">
<link href="{{asset('static/admin/css/style.min.css?v=4.1.0')}}" rel="stylesheet">
<link href="{{asset('static/admin/css/plugins/sweetalert/sweetalert.css')}}" rel="stylesheet">
</head>
<body>

<div class="page-container">


    <div class="cl pd-5 bg-1 bk-gray mt-20"><span class="l">
            <a href="{{url('admin/new_add')}}" class="btn btn-primary radius"><i
                        class="Hui-iconfont">&#xe600;</i> 添加新闻</a></span>
    </div>
    <div class="mt-20">
        <table class="table table-border table-bordered table-hover table-bg table-sort">
            <thead>
            <tr class="text-c">
                <th width="25"><input type="checkbox" name="" value=""></th>
                <th width="80">ID</th>
                <th width="100">标题</th>
                <th width="40">简介</th>
                <th width="150">发布人</th>
                <th width="100">是否置顶</th>
                <th width="130">状态</th>
                <th width="130">创建时间</th>
                <th width="100">操作</th>
            </tr>
            </thead>
            <tbody>
            @foreach($data_new as $item)
                <tr class="text-c">
                    <td><input type="checkbox" value="{{$item->id}}" name="id[]"></td>
                    <td>{{$item->id}}</td>
                    <td>{{$item->title}}</td>
                    <td>{{$item->introduction}}</td>
                    <td>{{$item->publisher}}</td>
                    <td>{{$item->is_top}}</td>
                    <td>{{$item->status}}</td>
                    <td>{{$item->created_at}}</td>
                    <td class="td-manage">
                        <a href=""
                           class="label label-secondary radius">修改</a>
                        <a href=""
                           class="label label-danger radius delbtn">删除</a>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>


    </div>

</div>
<script src="{{asset('static/admin/js/jquery.min.js?v=2.1.4')}}"></script>
<script src="{{asset('static/admin/js/bootstrap.min.js?v=3.3.6')}}"></script>
<script src="{{asset('static/admin/js/content.min.js?v=1.0.0')}}"></script>
<script src="{{asset('static/admin/js/layui/layui.js')}}"></script>
<script src="{{asset('static/admin/js/jquery.form.js')}}"></script>

</body>

</html>



