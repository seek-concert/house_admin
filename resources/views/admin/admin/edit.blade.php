<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>编辑{{$module_name}}</title>
    <link rel="shortcut icon" href="{{asset('favicon.ico')}}">
    {{--  csrf令牌 --}}
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="{{asset('static/admin/css/bootstrap.min.css?v=3.3.6')}}" rel="stylesheet">
    <link href="{{asset('static/admin/css/font-awesome.min.css?v=4.4.0')}}" rel="stylesheet">
    <link href="{{asset('static/admin/css/animate.min.css')}}" rel="stylesheet">
    <link href="{{asset('static/admin/css/plugins/iCheck/custom.css')}}" rel="stylesheet">
    <link href="{{asset('static/admin/css/style.min.css?v=4.1.0')}}" rel="stylesheet">
</head>
<body class="gray-bg">
<div class="wrapper wrapper-content animated fadeInRight">
    <div class="row">
        <div class="col-sm-8">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <h5>编辑{{$module_name}}</h5>
                </div>
                <div class="ibox-content">
                    <form class="form-horizontal m-t" id="commentForm" method="post" action="{{$edit_url}}">
                        <input type="hidden" name="id" value="{{isset($infos['id'])?$infos['id']:''}}">
                        <div class="form-group">
                            <label class="col-sm-3 control-label">管理员名称：</label>
                            <div class="input-group col-sm-4">
                                <input id="username" type="text" class="form-control" name="username" required="" aria-required="true" value="{{isset($infos['username'])?$infos['username']:''}}">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-3 control-label">管理员角色：</label>
                            <div class="input-group col-sm-4">
                                <select class="form-control" name="role_id" required="" aria-required="true">
                                    <option value="">请选择</option>
                                    @if(isset($role_arr))
                                        @foreach($role_arr as $k=>$v)
                                        <option value="{{$k}}" @if(isset($infos['role_id'])&&$infos->getOriginal('role_id')==$k)selected @endif>{{$v}}</option>
                                         @endforeach
                                    @endif
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-3 control-label">登录密码：</label>
                            <div class="input-group col-sm-4">
                                <input id="password" type="text" class="form-control" name="password"  placeholder="再次输入修改密码">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-3 control-label">真是姓名：</label>
                            <div class="input-group col-sm-4">
                                <input id="realname" type="text" class="form-control" name="realname" required="" aria-required="true" value="{{isset($infos['realname'])?$infos['realname']:''}}">

                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-3 control-label">是否启用：</label>
                            <div class="input-group col-sm-6">
                                <div class="radio i-checks col-sm-4">
                                    <label><input type="radio" value="1" @if(isset($infos['status'])&&$infos->getOriginal('status')==1)checked @endif name="status"> <i></i> 启用</label>
                                </div>
                                <div class="radio i-checks col-sm-4">
                                    <label><input type="radio" value="-1" @if(isset($infos['status'])&&$infos->getOriginal('status')==-1)checked @endif name="status"> <i></i> 停用</label>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-sm-4 col-sm-offset-6">
                                <button class="btn btn-primary" type="submit">提交</button>
                                <a href="{{$list_url}}"><button class="btn btn-success" type="button">返回</button></a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

        </div>
    </div>
</div>
<script src="{{asset('static/admin/js/jquery.min.js?v=2.1.4')}}"></script>
<script src="{{asset('static/admin/js/bootstrap.min.js?v=3.3.6')}}"></script>
<script src="{{asset('static/admin/js/content.min.js?v=1.0.0')}}"></script>
<script src="{{asset('static/admin/js/plugins/validate/jquery.validate.min.js')}}"></script>
<script src="{{asset('static/admin/js/plugins/validate/messages_zh.min.js')}}"></script>
<script src="{{asset('static/admin/js/plugins/iCheck/icheck.min.js')}}"></script>
<script src="{{asset('static/admin/js/plugins/layer/laydate/laydate.js')}}"></script>
<script src="{{asset('static/admin/js/plugins/layer/layer.min.js')}}"></script>
<script src="{{asset('static/admin/js/jquery.form.js')}}"></script>
<script type="text/javascript">
    $.ajaxSetup({
        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')}
    });
    var index = '';
    function showStart(){index = layer.load(0, {shade: false});return true;}
    function showSuccess(res){
        layer.ready(function(){
            layer.close(index);
            if(0 == res.code){layer.alert(res.msg, {title: '友情提示', icon: 1, closeBtn: 0}, function(){window.location.href = res.url;});
            }else if(111 == res.code){window.location.reload();
            }else{layer.msg(res.msg, {anim: 6});}
        });
    }
    $(document).ready(function(){
        $(".i-checks").iCheck({checkboxClass:"icheckbox_square-green",radioClass:"iradio_square-green",});
        // 编辑
        var options = {beforeSubmit:showStart, success:showSuccess};
        $('#commentForm').submit(function(){$(this).ajaxSubmit(options);return false;});
    });
    // 表单验证
    $.validator.setDefaults({
        highlight: function(e) {$(e).closest(".form-group").removeClass("has-success").addClass("has-error")},
        success: function(e) {e.closest(".form-group").removeClass("has-error").addClass("has-success")},
        errorElement: "span",
        errorPlacement: function(e, r) {e.appendTo(r.is(":radio") || r.is(":checkbox") ? r.parent().parent().parent() : r.parent())},
        errorClass: "help-block m-b-none",
        validClass: "help-block m-b-none"
    });
</script>
</body>
</html>
