<div class="layui-card-body ">
    <form id="layer-form" class="layui-form" action="{{ route("admin.sys_sms.add") }}" method="POST">
        {{ csrf_field() }}
        <div class="layui-form-item">
            <label class="layui-form-label label-required-next">發送對象:</label>
            <div class="layui-input-block">
                <input type="radio" name="object_type" value="1" title="所有用戶" checked />
            </div>
            <div class="layui-input-block">
                <input type="radio" name="object_type" value="2" title="個人" lay-verify="personal" />
                <div class="layui-row">
                    <div class="layui-col-md12">
                        <div class="layui-input-inline" style="width: 200px;">
                            <div class="layui-inline layui-show-xs-block">
                                <select name="phone" lay-verify="" lay-search>
                                    <option value=""></option>
                                    {!! make_options($members) !!}
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="layui-input-block">
                <input type="radio" name="object_type" value="3" title="會員等級" />
                <div class="layui-row">
                    <div class="layui-col-md12">
                        <div class="layui-input-inline" style="width: 200px;">
                            <select id="member_level" lay-verify="">
                                <option value=""></option>
                                {!! make_options($member_levels) !!}
                            </select>
                        </div>
                    </div>
                </div>
            </div>
            <div class="layui-input-block">
                <input type="radio" name="object_type" value="4" title="註冊時間" />
                <div class="layui-row">
                    <div class="layui-col-md12">
                        <div class="layui-input-inline" style="width: 200px;">
                            <input type="text" id="start_time" class="layui-input" placeholder="請選擇起始日期">
                        </div>
                        <div class="layui-form-mid">-</div>
                        <div class="layui-input-inline" style="width: 200px;">
                            <input type="text" id="end_time" class="layui-input" placeholder="請選擇結束日期">
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label label-required-next">短信名稱:</label>
            <div class="layui-input-block">
                <input type="text" name="name" placeholder="請輸入短信名稱"
                       class="layui-input" required lay-verify="required">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label label-required-next">短信內容.中:</label>
            <div class="layui-input-block">
                <textarea id="content" name="content" placeholder="請輸入短信內容"
                          class="layui-textarea" required lay-verify="required"></textarea>
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label label-required-next">短信內容.英:</label>
            <div class="layui-input-block">
                <textarea id="content_en" name="content_en" placeholder="請輸入短信內容"
                          class="layui-textarea" required lay-verify="required"></textarea>
            </div>
        </div>
        <div class="layui-form-item">
            <div class="layui-input-block">
                <button class="layui-btn layui-btn-sm" id="submit" lay-filter="save" lay-submit>提交</button>
            </div>
        </div>
    </form>
</div>
<script>
    layui.use(['form', 'laydate'], function(){
        var form = layui.form;
        var $ = layui.$;
        var laydate = layui.laydate;

        laydate.render({
            elem: '#start_time',
            format: 'yyyy/MM/dd',
            trigger: 'click', //解决日期组件弹框一闪而过,指定被选元素要触发的事件
            //value: new Date(),
        });
        laydate.render({
            elem: '#end_time',
            format: 'yyyy/MM/dd',
            trigger: 'click', //解决日期组件弹框一闪而过,规定被选元素要触发的事件
        });

        form.on('submit(save)', function(data){
            var type = $('[name="object_type"]:checked').val();
            var object_ids;
            switch(type){
                case '1':
                    object_ids = '';
                    break;
                case '2':
                    if(!$('select[name="phone"]').val()){
                        layer.msg('請輸入用戶手機號', {time: 2000});
                        // setTimeout(function(){ //延遲多久后 (單位為毫秒)，才去调用函数
                        // },1000);
                        return false;
                    }
                    object_ids = $('select[name="phone"]').val();
                    break;
                case '3':
                    if(!$('#member_level').val()){
                        layer.msg('請選擇會員等級', {time: 2000});
                        return false;
                    }
                    object_ids = $('#member_level').val();
                    break;
                case '4':
                    if($('#start_time').val() ==='' || $('#end_time').val() === ''){
                        layer.msg('請選擇註冊時間', {time: 2000});
                        return false;
                    }
                    object_ids = $('#start_time').val() + ',' + $('#end_time').val();
                    break;
            }
            data.field["object_ids"] = object_ids; //送object_ids字段,不需在页面上再定义hidden

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
