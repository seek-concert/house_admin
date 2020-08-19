<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title></title>
    <link rel="stylesheet" type="text/css" href="/admin/static/h-ui/css/H-ui.min.css" />
    <link rel="stylesheet" type="text/css" href="/admin/static/h-ui.admin/css/H-ui.admin.css" />
    <link rel="stylesheet" type="text/css" href="/admin/lib/Hui-iconfont/1.0.8/iconfont.css" />
    <link rel="stylesheet" type="text/css" href="/admin/static/h-ui.admin/skin/default/skin.css" id="skin" />
    <link rel="stylesheet" type="text/css" href="/admin/static/h-ui.admin/css/style.css" />
    <link rel="stylesheet" href="/admin/css/uploadImg.css">

</head>
<body>


<style>
    * {
        margin: 0;
        padding: 0;
    }

    li {
        list-style-type: none;
    }

    label {
        cursor: pointer;
    }


    #wrap {
        width: 160px;
        height: 200px;
        margin: 20px auto;
        box-shadow: #272727 0px 0px 5px;
        position: relative;
        overflow: hidden;
    }

    .menu {
        width: 300px;
        height: 320px;
        border-bottom: 1px solid #272727;
        float: left;
    }

    .menu > li {
        line-height: 49px;
        height: 49px;
        float: left;
        padding: 0 10px;
        cursor: pointer;
        text-align: center;
        position: relative;
    }

    .menu > li:after {
        content: "|";
        width: 5px;
        position: absolute;
        right: 0;
        top: 0;
    }

    .menu > li label {
        width: 100px;
        height: 100px;
        display: inline-block;
    }

    .wrap {
        width: 500px;
        height: 200px;
        overflow: auto;
        position: relative;
    }

    .edit_wrap {
        width: 96%;
        padding: 2%;
        height: auto;
        overflow: auto;
        line-height: 1.5;
        outline: none;
    }

    .btnBox {
        display: none;
        position: absolute;
        left: 0;
        top: 49px;
        width: 100%;
    }

    .btnBox > li {
        width: 100%;
        height: 35px;
        line-height: 35px;
        text-align: center;
        background: #fff;
    }

    input {
        border: none;
        width: 100px;
        height: 100px;
        cursor: pointer;
        outline: none;
        font-size: 16px;
        background: inherit;
    }

    .menu li:hover ul {
        display: block;
        z-index: 20;
    }

    .btnBox > li:hover, .menu > li:hover {
        background: #CCCCCC;
    }

    .item {
        list-style-type: square !important;
        margin-left: 10px;
    }

    #dialog {
        width: 50%;
        height: 150px;
        position: absolute;
        top: 50%;
        left: 50%;
        margin: -15% 0 0 -25%;
        background: #fff;
        border-radius: 10px 10px 0 0;
        overflow: hidden;
        box-shadow: 0 0 5px #808080;
        display: none;
    }

    #dialog h1 {
        width: 100%;
        height: 45px;
        background: #03A9F4;
        color: #fff;
        text-align: center;
        line-height: 45px;
        font-size: 20px;
    }

    #dialog input {
        width: 90%;
        height: 30px;
        margin: 10px auto;
        display: block;
        background: ghostwhite;
        cursor: auto;
    }

    #dialog span {
        display: block;
        margin: 25px auto;
        width: 60%;
    }

    #dialog button {
        display: inline-block;
        outline: none;
        width: 40%;
        height: 30px;
        border: solid #03A9F4 1px;
        background: #fff;
        cursor: pointer;
    }

    #dialog button:last-child {
        float: right;
    }

    #handleWrap {
        position: absolute;
        display: none;
    }

    #handleWrap ul {
        width: 100px;
        height: 100px;
        position: relative;
        border: 2px solid #03A9F4;
    }

    #handleWrap ul li {
        width: 20px;
        height: 20px;
        position: absolute;
        border: 1px solid #03A9F4;
        background: #03A9F4;
        border-radius: 60%;
        bottom: 0;
        right: 0;
        margin: 0 -10px -10px 0px;
        cursor: move;
    }

    /*#handleWrap ul li:first-child {
        top:0;
        left:0;
        margin:-10px 0 0 -10px;
        cursor:nw-resize;
    }
    */
    /*#handleWrap ul li:nth-child(2) {
top:0;
right:0;
margin:-10px -10px 0 0;
cursor:ne-resize;
}
*/
    /*#handleWrap ul li:nth-child(3) {
bottom:0;
left:0;
margin:0 0 -10px -10px;
cursor:sw-resize;
}
*/
    /*#handleWrap ul li:nth-child(4) {
bottom:0;
right:0;
margin:0 -10px -10px 0;
cursor:se-resize;
}
*/


    @media only screen and (min-width: 350px) and (max-width: 450px) {
        .wrap {
            height: calc(500px - 100px);
        }
    }

    @media only screen and (min-width: 321px) and (max-width: 509px) {
        .wrap {
            height: calc(100% - 150px);
        }
    }

    @media only screen and (min-width: 100px) and (max-width: 320px) {
        .wrap {
            height: calc(100% - 200px);
        }
    }
</style>

 <article class="page-container">
    <form action="{{route('g_new_create')}}" method="post" class="form form-horizontal" id="form-member-add">
        {{csrf_field()}}
        {{@method_field('PUT')}}
        <div class="row cl">
            <label class="form-label col-xs-4 col-sm-2"><span class="c-red">*</span>新闻分类：</label>
            <div class="formControls col-xs-8 col-sm-9"> <span class="select-box">

                    <select name="cate_id" class="select">
                        @foreach($data_new_cate as $value => $cate)
                    <option value={{$cate ->id}}>{{$cate ->name}}</option>
                        @endforeach
                </select>

				</span>
            </div>
        </div>
        <div class="row cl">
            <label class="form-label col-xs-4 col-sm-3"><span class="c-red">*</span>新闻标题：</label>
            <div class="formControls col-xs-8 col-sm-9">
                <input type="text" class="input-text" value="" placeholder="" id="username" name="title"
                >
            </div>
        </div>
        <div class="row cl">
            <label class="form-label col-xs-4 col-sm-3"><span class="c-red">*</span>新闻简介：</label>
            <div class="formControls col-xs-8 col-sm-9">
                <input type="text" class="input-text" value="" name="introduction" id="password" >
            </div>
        </div>

        <div>
            <div>
                新闻内容:
                <script id="editor" type="text/plain" style="width:100%;height:200px;" name="content"></script>
            </div>
        </div>
        <div class="row cl">
            <label class="form-label col-xs-4 col-sm-3"><span class="c-red">*</span>发布人：</label>
            <div class="formControls col-xs-8 col-sm-9">
                <input type="text" class="input-text" name="publisher" id="email" value="">
            </div>
        </div>
        <div class="row cl">
            <label class="form-label col-xs-4 col-sm-2"><span class="c-red">*</span>新闻是否置顶：</label>
            <div class="formControls col-xs-8 col-sm-9"> <span class="select-box">
                    <select name="is_top" class="select">
                            <option value='1'>1</option>
                            <option value='2'>2</option>
                </select>
				</span>
            </div>
        </div>  <div class="row cl">
            <label class="form-label col-xs-4 col-sm-2"><span class="c-red">*</span>新闻时候隐藏：</label>
            <div class="formControls col-xs-8 col-sm-9"> <span class="select-box">

                    <select name="status" class="select">
                        <option value='1'>1</option>
                        <option value='2'>2</option>
                </select>
				</span>
            </div>
        </div>
        <div class="row cl">
            <label class="form-label col-xs-4 col-sm-3"><span class="c-red">*</span>时间：</label>
            <div class="formControls col-xs-8 col-sm-9">
                <input type="date" class="input-text" name="created_at" id="email" value="">
            </div>
        </div>

        <div class="row cl">
            <div class="col-xs-8 col-sm-9 col-xs-offset-4 col-sm-offset-3">
                <input class="btn btn-primary radius" type="submit" value="&nbsp;&nbsp;提交&nbsp;&nbsp;">
            </div>
        </div>
    </form>
</article>
 <script type="text/javascript"  src="/admin/lib/My97DatePicker/4.8/WdatePicker.js"></script>


 <script type="text/javascript"  src="/admin/lib/ueditor/1.4.3/ueditor.config.js"></script>
 <script type="text/javascript"  src="/admin/lib/ueditor/1.4.3/ueditor.all.min.js"></script>
 <script type ="text/javascript" src="/admin/lib/ueditor/1.4.3/lang/zh-cn/zh-cn.js"></script>
<script src="/admin/js/jquery-3.2.1.min.js"></script>
<script type ="text/javascript" src="/admin/js/bootstrap.min.js"></script>
<script type ="text/javascript" src="/admin/js/uploadImg.js"></script>
</body>

<script type="text/javascript">


    //实例化编辑器
    //建议使用工厂方法getEditor创建和引用编辑器实例，如果在某个闭包下引用该编辑器，直接调用UE.getEditor('editor')就能拿到相关的实例
    var ue = UE.getEditor('editor');


    function isFocus(e) {
        alert(UE.getEditor('editor').isFocus());
        UE.dom.domUtils.preventDefault(e)
    }

    function setblur(e) {
        UE.getEditor('editor').blur();
        UE.dom.domUtils.preventDefault(e)
    }

    function insertHtml() {
        var value = prompt('插入html代码', '');
        UE.getEditor('editor').execCommand('insertHtml', value)
    }

    function createEditor() {
        enableBtn();
        UE.getEditor('editor');
    }

    function getAllHtml() {
        alert(UE.getEditor('editor').getAllHtml())
    }

    function getContent() {
        var arr = [];
        arr.push("使用editor.getContent()方法可以获得编辑器的内容");
        arr.push("内容为：");
        arr.push(UE.getEditor('editor').getContent());
        alert(arr.join("\n"));
    }

    function getPlainTxt() {
        var arr = [];
        arr.push("使用editor.getPlainTxt()方法可以获得编辑器的带格式的纯文本内容");
        arr.push("内容为：");
        arr.push(UE.getEditor('editor').getPlainTxt());
        alert(arr.join('\n'))
    }

    function setContent(isAppendTo) {
        var arr = [];
        arr.push("使用editor.setContent('欢迎使用ueditor')方法可以设置编辑器的内容");
        UE.getEditor('editor').setContent('欢迎使用ueditor', isAppendTo);
        alert(arr.join("\n"));
    }

    function setDisabled() {
        UE.getEditor('editor').setDisabled('fullscreen');
        disableBtn("enable");
    }

    function setEnabled() {
        UE.getEditor('editor').setEnabled();
        enableBtn();
    }

    function getText() {
        //当你点击按钮时编辑区域已经失去了焦点，如果直接用getText将不会得到内容，所以要在选回来，然后取得内容
        var range = UE.getEditor('editor').selection.getRange();
        range.select();
        var txt = UE.getEditor('editor').selection.getText();
        alert(txt)
    }

    function getContentTxt() {
        var arr = [];
        arr.push("使用editor.getContentTxt()方法可以获得编辑器的纯文本内容");
        arr.push("编辑器的纯文本内容为：");
        arr.push(UE.getEditor('editor').getContentTxt());
        alert(arr.join("\n"));
    }

    function hasContent() {
        var arr = [];
        arr.push("使用editor.hasContents()方法判断编辑器里是否有内容");
        arr.push("判断结果为：");
        arr.push(UE.getEditor('editor').hasContents());
        alert(arr.join("\n"));
    }

    function setFocus() {
        UE.getEditor('editor').focus();
    }

    function deleteEditor() {
        disableBtn();
        UE.getEditor('editor').destroy();
    }

    function disableBtn(str) {
        var div = document.getElementById('btns');
        var btns = UE.dom.domUtils.getElementsByTagName(div, "button");
        for (var i = 0, btn; btn = btns[i++];) {
            if (btn.id == str) {
                UE.dom.domUtils.removeAttributes(btn, ["disabled"]);
            } else {
                btn.setAttribute("disabled", "true");
            }
        }
    }

    function enableBtn() {
        var div = document.getElementById('btns');
        var btns = UE.dom.domUtils.getElementsByTagName(div, "button");
        for (var i = 0, btn; btn = btns[i++];) {
            UE.dom.domUtils.removeAttributes(btn, ["disabled"]);
        }
    }

    function getLocalData() {
        alert(UE.getEditor('editor').execCommand("getlocaldata"));
    }

    function clearLocalData() {
        UE.getEditor('editor').execCommand("clearlocaldata");
        alert("已清空草稿箱")
    }
</script>



</html>



