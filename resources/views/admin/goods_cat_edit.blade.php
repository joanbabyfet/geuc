<div class="layui-card-body ">
    <form id="layer-form" class="layui-form" action="{{ route("admin.goods_cat.edit") }}" method="POST">
        {{ csrf_field() }}
        <input type='hidden' name='id' value="{{ $row['id'] }}"/>
        <div class="layui-form-item">
            <label class="layui-form-label label-required-next">分類名稱:</label>
            <div class="layui-input-block">
                <input type="text" name="name" value="{{ $row['name'] }}" placeholder="請輸入分類名稱"
                       class="layui-input" required lay-verify="required">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">英文名稱:</label>
            <div class="layui-input-block">
                <input type="text" name="name_en" value="{{ $row['name_en'] }}" placeholder="請輸入英文名稱"
                       class="layui-input">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">上級分類:</label>
            <div class="layui-input-block">
{{--                <input type="text" name="pid" value="{{ $row['pid'] }}" placeholder="請輸入上級分類"--}}
{{--                       class="layui-input" required lay-verify="required">--}}
                <input type="text" name="pid" id="pid" value="{{ $row['pid'] }}" lay-filter="pid"
                       class="layui-input" lay-verify="" />
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label label-required-next">所屬店舖:</label>
            <div class="layui-input-block">
                <select name="store_id" id="store_id" lay-filter="store_id" lay-verify="required">
                    <option value=""></option>
                    {!! make_options($store_options, $row['store_id']) !!}
                </select>
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">分類描述:</label>
            <div class="layui-input-block">
                <textarea id="desc" name="desc" placeholder="請輸入分類描述"
                          class="layui-textarea">{{ $row['desc'] }}</textarea>
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label label-required-next">狀態:</label>
            <div class="layui-input-block">
                <input type="checkbox" {!! $row['status'] == 1 ? 'checked="checked"':'' !!} name="status" lay-skin="switch" lay-filter="status"
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

    layui.use(['form', 'layedit', 'treeSelect'], function(){
        var form = layui.form;
        var $ = layui.$;
        var treeSelect= layui.treeSelect;

        get_options($('#store_id').val(), $('#pid').val(), '#pid');
        //在layui中使用jquery觸發select的change事件無效,改用此監聽
        form.on('select(store_id)', function(data){
            var store_id = data.value;
            var pid = $('#pid').val();
            treeSelect.revokeNode('pid', function(d){ //撤销选中节点
                console.log(d);
                console.log(d.treeId);
            });
            treeSelect.destroy('pid'); //先销毀數據
            get_options(store_id, pid, '#pid');
        });
        function get_options(store_id, pid, element) {
            treeSelect.render({ //渲染
                elem: '#pid', //选择器
                data: '{{ route('admin.common.ajax_get_goods_cat') }}?store_id=' + store_id + '&id=' + '{{ $row['id'] }}', // 数据
                headers: {}, // 请求头
                type: 'get', // 异步加载方式：get/post，默认get
                placeholder: '頂級分類', // 占位符
                search: false, // 是否开启搜索功能：true/false，默认false
                style: { // 一些可定制的样式
                    folder: {
                        enable: false
                    },
                    line: {
                        enable: true
                    }
                },
                // 点击回调
                click: function(d){
                    console.log(d);
                },
                // 加载完成后的回调函数
                success: function (d) {
                    if(store_id && pid != 0) {
                        treeSelect.checkNode('pid', pid); //选中节点，根据id筛选
                    }
                    treeSelect.refresh('pid');
                }
            });
        }

        form.verify({
        });
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
