<div class="layui-card-body ">
    <form id="layer-form" class="layui-form" action="{{ route("admin.store.add") }}" method="POST">
        {{ csrf_field() }}
        <div class="layui-form-item">
            <label class="layui-form-label label-required-next">店舖名稱:</label>
            <div class="layui-input-block">
                <input type="text" name="name" placeholder="請輸入店舖名稱"
                       class="layui-input" required lay-verify="required">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label label-required-next">所屬商家:</label>
            <div class="layui-input-block">
                <select name="shop_id" lay-verify="required">
                    <option value=""></option>
                    {!! make_options($shop_options) !!}
                </select>
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label label-required-next">選擇區域:</label>
            <div class="layui-input-inline" style="width: 100px;">
                <select id="country_id" name="country_id" lay-filter="country_id"
                        lay-verify="required">
                    <option value=""></option>
                    {!! make_options($country_options, 1) !!}
                </select>
            </div>
            <div class="layui-input-inline" style="width: 100px;">
                <select id="province_id" name="province_id" lay-filter="province_id"
                        lay-verify="required">
                    <option value=""></option>
                </select>
            </div>
            <div class="layui-input-inline" style="width: 100px;">
                <select id="city_id" name="city_id" lay-filter="city_id"
                        lay-verify="required">
                    <option value=""></option>
                </select>
            </div>
            <div class="layui-input-inline" style="width: 100px;">
                <select id="area_id" name="area_id" lay-filter="area_id"
                        lay-verify="">
                    <option value=""></option>
                </select>
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label label-required-next">店舖地址:</label>
            <div class="layui-input-block">
                <input type="text" name="address" placeholder="請輸入店舖地址"
                       class="layui-input" required lay-verify="required">
            </div>
        </div>
        <div class="layui-form-item uploader-group uploader-group-img"
             data-token="{{ csrf_token() }}"
             data-dir="image"
             data-extensions="jpg,jpeg,png,bmp"
             data-multiple="true"
             data-auto="true"
             data-size="20"
             data-thumb_w="{{ $img_thumb_with }}"
             data-len="1"
             data-chunked='chunked'>
            <label class="layui-form-label">店舖照片:</label>
            <div class="layui-input-block">
                <!--用来存放文件信息-->
                <div class="uploader-list"></div>
                <a class="layui-btn layui-btn-sm layui-btn-primary uploader-picker" data-file="pictures[]" data-type="image">
                    <i class="layui-icon">&#xe67c;</i> 选择文件</a>
                <div class="layui-row">
                    <div class="layui-col-md12">
                        <div class="layui-form-mid layui-word-aux">格式：jpg、jpeg、png、bmp</div>
                    </div>
                </div>
                <input type="hidden" class="form-control file" datatype="file" nullmsg="至少上传一张" errmsg="至少上传一张">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label label-required-next">聯絡人:</label>
            <div class="layui-input-inline">
                <input type="text" name="contact" placeholder="請輸入聯絡人"
                       class="layui-input" required lay-verify="required">
            </div>
            <div class="layui-input-inline">
                <input type="radio" name="sex" value="1" title="男" checked />
                <input type="radio" name="sex" value="0" title="女" />
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label label-required-next">聯絡電話:</label>
            <div class="layui-input-inline" style="width: 100px;">
                <select name="phone_code" lay-verify="required">
                    <option value=""></option>
                    {!! make_options($mobile_prefix_options, '86') !!}
                </select>
            </div>
            <div class="layui-input-inline">
                <input type="text" name="phone" placeholder="請輸入聯絡電話"
                       class="layui-input" required lay-verify="required">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">店舖描述:</label>
            <div class="layui-input-block">
                <textarea id="content" name="content" placeholder="請輸入店舖描述"
                          class="layui-textarea"></textarea>
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label label-required-next">狀態:</label>
            <div class="layui-input-block">
                <select name="status" lay-verify="required">
                    <option value=""></option>
                    {!! make_options($status_options, 1) !!}
                </select>
            </div>
        </div>
        <div class="layui-form-item">
            <div class="layui-input-block">
                <button class="layui-btn layui-btn-sm" lay-filter="save" lay-submit>提交</button>
            </div>
        </div>
    </form>
</div>
<script src="{{ ADMIN_JS }}/webuploader.own.js"></script>
<script>
    var verify = { //自定義表單驗證規則
    };

    layui.use(['form', 'layedit'], function(){
        var form = layui.form;
        var $ = layui.$;

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

        //预加载省份下拉框
        get_options($('#country_id').val(), '#province_id');
        //在layui中使用jquery觸發select的change事件無效,改用此監聽
        form.on('select(country_id)', function(data){
            var pid = data.value;
            get_options(pid, '#province_id');
            $('#city_id').empty();
            $('#city_id').append(new Option('', ''));
            $('#area_id').empty();
            $('#area_id').append(new Option('', ''));
        });
        form.on('select(province_id)', function(data){
            var pid = data.value;
            get_options(pid, '#city_id');
            $('#area_id').empty();
            $('#area_id').append(new Option('', ''));
        });
        form.on('select(city_id)', function(data){
            var pid = data.value;
            get_options(pid, '#area_id');
        });
        function get_options(pid, element) {
            $.ajax({
                url: '{{ route("admin.common.ajax_get_area") }}',
                type: 'POST',
                data: { pid: pid }, //送到服务器數據
                success: function(res) {
                    if (res.code === 0) {
                        //遍历
                        $(element).empty(); //干掉下拉框值
                        $(element).append(new Option('', ''));
                        $.each(res.data, function(index, item){ //index=键名 item=键值
                            $(element).append(new Option(item, index)); //展示文字在前
                        });
                        layui.form.render('select'); //重新渲染 固定写法
                    }
                    else {
                        layui.layer.msg(res.msg, {time: 2000, icon: 5});
                    }
                },
                error: function () {
                }
            });
        }
    });
</script>
