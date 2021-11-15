<div class="layui-card-body ">
    <form id="layer-form" class="layui-form" action="{{ route("admin.country.add") }}" method="POST">
        {{ csrf_field() }}
        <div class="layui-form-item">
            <label class="layui-form-label label-required-next">名稱:</label>
            <div class="layui-input-block">
                <input type="text" name="name" placeholder="請輸入名稱"
                       class="layui-input" required lay-verify="required">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">英文名稱:</label>
            <div class="layui-input-block">
                <input type="text" name="en_name" placeholder="請輸入英文名稱"
                       class="layui-input">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">英文簡稱:</label>
            <div class="layui-input-block">
                <input type="text" name="en_short_name" placeholder="請輸入英文簡稱"
                       class="layui-input">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">電話國碼:</label>
            <div class="layui-input-block">
                <input type="text" name="mobile_prefix" placeholder="請輸入電話國碼"
                       class="layui-input">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label label-required-next">默認展示:</label>
            <div class="layui-input-block">
                <input type="checkbox" name="is_default" lay-skin="switch" lay-filter="is_default"
                       lay-text="是|否">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label label-required-next">固定頻道:</label>
            <div class="layui-input-block">
                <input type="checkbox" name="is_fix" lay-skin="switch" lay-filter="is_fix"
                       lay-text="是|否">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label label-required-next">狀態:</label>
            <div class="layui-input-block">
                <input type="checkbox" checked name="status" lay-skin="switch" lay-filter="status"
                       lay-text="啟用|禁用">
            </div>
        </div>
        <div class="layui-form-item">
            <div class="layui-input-block">
                <button class="layui-btn layui-btn-sm" lay-filter="save" lay-submit>提交</button>
            </div>
        </div>
    </form>
</div>
<script>
    var verify = { //自定義表單驗證規則
    };

    layui.use('form', function(){
        var form = layui.form;
        var $ = layui.$;

        form.verify(verify);
        form.on('submit(save)', function(data){
            $.post($('#layer-form').attr("action"), data.field, function(res) {
                if (res.code === 0) {
                    layui.layer.close(layer.index);//关闭弹出层
                    layui.layer.msg(res.msg, {time: 2000, icon: 6});
                    parent.location.reload();//刷新父頁面
                } else {
                    layui.layer.msg(res.msg, {time: 2000, icon: 5});
                }
            });
            return false; //阻止表單跳转
        });
    });
</script>
