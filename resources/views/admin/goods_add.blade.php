<div class="layui-card-body ">
    <form id="layer-form" class="layui-form" action="{{ route("admin.goods.add") }}" method="POST">
        {{ csrf_field() }}
        <div class="layui-form-item">
            <label class="layui-form-label label-required-next">商品類型:</label>
            <div class="layui-input-block">
                <input type="radio" name="type" value="1" title="實物商品" checked />
                <input type="radio" name="type" value="2" title="虛擬商品" />
                <input type="radio" name="type" value="3" title="電子卡密" />
            </div>
        </div>
        <div class="layui-form-item">
            <div class="layui-inline">
                <label class="layui-form-label label-required-next">商品名稱:</label>
                <div class="layui-input-inline">
                    <input type="text" name="name" placeholder="請輸入商品名稱"
                           class="layui-input" required lay-verify="required">
                </div>
            </div>
            <div class="layui-inline">
                <label class="layui-form-label">商品英文名</label>
                <div class="layui-input-inline">
                    <input type="text" name="name_en" placeholder="請輸入商品英文名"
                           class="layui-input" lay-verify="">
                </div>
            </div>
        </div>
        <div class="layui-form-item">
            <div class="layui-inline">
                <label class="layui-form-label label-required-next">商品分類:</label>
                <div class="layui-input-inline">
                    <input type="text" name="cat_id" id="cat_id" lay-filter="cat_id"
                           class="layui-input" required lay-verify="required" />
                </div>
            </div>
            <div class="layui-inline">
                <label class="layui-form-label label-required-next">所屬店舖:</label>
                <div class="layui-input-inline">
                    <select name="store_id" id="store_id" lay-filter="store_id" lay-verify="required">
                        <option value=""></option>
                        {!! make_options($store_options) !!}
                    </select>
                </div>
            </div>
        </div>
        <div class="layui-form-item">
            <div class="layui-inline">
                <label class="layui-form-label label-required-next">商品價格:</label>
                <div class="layui-input-inline">
                    <input type="text" name="price" placeholder="0.00"
                           class="layui-input" required lay-verify="required">
                </div>
            </div>
            <div class="layui-inline">
                <label class="layui-form-label">原價:</label>
                <div class="layui-input-inline">
                    <input type="text" name="origin_price" placeholder="0.00"
                           class="layui-input" lay-verify="">
                </div>
            </div>
        </div>
        <div class="layui-form-item">
            <div class="layui-inline">
                <label class="layui-form-label label-required-next">幣種:</label>
                <div class="layui-input-inline">
                    <select name="currency_code" lay-verify="required">
                        <option value=""></option>
                        {!! make_options($currency_options, 'USD') !!}
                    </select>
                </div>
            </div>
            <div class="layui-inline">
                <label class="layui-form-label">商品編號:</label>
                <div class="layui-input-inline">
                    <input type="text" name="sn" placeholder="請輸入商品編號"
                           class="layui-input" lay-verify="">
                </div>
            </div>
        </div>
        <div class="layui-form-item">
            <div class="layui-inline">
                <label class="layui-form-label label-required-next">庫存:</label>
                <div class="layui-input-inline">
                    <input type="text" name="stock" value="0" placeholder="請輸入庫存"
                           class="layui-input" required lay-verify="required">
                </div>
            </div>
            <div class="layui-inline">
                <label class="layui-form-label">已售數量:</label>
                <div class="layui-input-inline">
                    <input type="text" name="sold_num" value="0" placeholder="請輸入已售數量"
                           class="layui-input" lay-verify="">
                </div>
            </div>
        </div>
        <div class="layui-form-item">
            <div class="layui-inline">
                <label class="layui-form-label">限購數量:</label>
                <div class="layui-input-inline">
                    <input type="text" name="limit_buy" value="0" placeholder="請輸入限購數量"
                           class="layui-input" lay-verify="">
                </div>
            </div>
            <div class="layui-inline">
                <label class="layui-form-label">單位:</label>
                <div class="layui-input-inline">
                    <input type="text" name="unit" placeholder="請輸入單位"
                           class="layui-input" lay-verify="">
                </div>
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">商品規格:</label>
            <div class="layui-input-block">
                <input type="text" name="spec" placeholder="請輸入商品規格"
                       class="layui-input" lay-verify="">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">英文規格:</label>
            <div class="layui-input-block">
                <input type="text" name="spec_en" placeholder="請輸入英文規格"
                       class="layui-input" lay-verify="">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">商品介紹:</label>
            <div class="layui-input-block">
                <textarea id="desc" name="desc" placeholder="請輸入商品介紹"
                          class="layui-textarea"></textarea>
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">英文介紹:</label>
            <div class="layui-input-block">
                <textarea id="desc_en" name="desc_en" placeholder="請輸入英文介紹"
                          class="layui-textarea"></textarea>
            </div>
        </div>
        <div class="layui-form-item uploader-group uploader-group-img"
             data-token="{{ csrf_token() }}"
             data-dir="image"
             data-extensions="jpg,jpeg,png,bmp"
             data-multiple="true"
             data-auto="true"
             data-size="20"
             data-thumb_w=""
             data-len="1"
             data-chunked='chunked'>
            <label class="layui-form-label">商品列表小图(中):</label>
            <div class="layui-input-block">
                <!--用来存放文件信息-->
                <div class="uploader-list"></div>
                <a class="layui-btn layui-btn-sm layui-btn-primary uploader-picker" data-file="thumb[]" data-type="image">
                    <i class="layui-icon">&#xe67c;</i> 选择文件</a>
                <div class="layui-row">
                    <div class="layui-col-md12">
                        <div class="layui-form-mid layui-word-aux">格式：jpg、jpeg、png、bmp</div>
                    </div>
                </div>
                <input type="hidden" class="form-control file" datatype="file" nullmsg="至少上传一张" errmsg="至少上传一张">
            </div>
        </div>
        <div class="layui-form-item uploader-group uploader-group-img"
             data-token="{{ csrf_token() }}"
             data-dir="image"
             data-extensions="jpg,jpeg,png,bmp"
             data-multiple="true"
             data-auto="true"
             data-size="20"
             data-thumb_w=""
             data-len="1"
             data-chunked='chunked'>
            <label class="layui-form-label">商品详情大图(中):</label>
            <div class="layui-input-block">
                <!--用来存放文件信息-->
                <div class="uploader-list"></div>
                <a class="layui-btn layui-btn-sm layui-btn-primary uploader-picker" data-file="img[]" data-type="image">
                    <i class="layui-icon">&#xe67c;</i> 选择文件</a>
                <div class="layui-row">
                    <div class="layui-col-md12">
                        <div class="layui-form-mid layui-word-aux">格式：jpg、jpeg、png、bmp</div>
                    </div>
                </div>
                <input type="hidden" class="form-control file" datatype="file" nullmsg="至少上传一张" errmsg="至少上传一张">
            </div>
        </div>
        <div class="layui-form-item uploader-group uploader-group-img"
             data-token="{{ csrf_token() }}"
             data-dir="image"
             data-extensions="jpg,jpeg,png,bmp"
             data-multiple="true"
             data-auto="true"
             data-size="20"
             data-thumb_w=""
             data-len="1"
             data-chunked='chunked'>
            <label class="layui-form-label">商品列表小图(英):</label>
            <div class="layui-input-block">
                <!--用来存放文件信息-->
                <div class="uploader-list"></div>
                <a class="layui-btn layui-btn-sm layui-btn-primary uploader-picker" data-file="thumb_en[]" data-type="image">
                    <i class="layui-icon">&#xe67c;</i> 选择文件</a>
                <div class="layui-row">
                    <div class="layui-col-md12">
                        <div class="layui-form-mid layui-word-aux">格式：jpg、jpeg、png、bmp</div>
                    </div>
                </div>
                <input type="hidden" class="form-control file" datatype="file" nullmsg="至少上传一张" errmsg="至少上传一张">
            </div>
        </div>
        <div class="layui-form-item uploader-group uploader-group-img"
             data-token="{{ csrf_token() }}"
             data-dir="image"
             data-extensions="jpg,jpeg,png,bmp"
             data-multiple="true"
             data-auto="true"
             data-size="20"
             data-thumb_w=""
             data-len="1"
             data-chunked='chunked'>
            <label class="layui-form-label">商品详情大图(英):</label>
            <div class="layui-input-block">
                <!--用来存放文件信息-->
                <div class="uploader-list"></div>
                <a class="layui-btn layui-btn-sm layui-btn-primary uploader-picker" data-file="img_en[]" data-type="image">
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
            <label class="layui-form-label">商品颜色:</label>
            <div class="layui-input-block">
                @foreach($colors as $v)
                    <input type="checkbox" name="color[]" lay-skin="primary" value="{{ $v['id'] }}" title="{{ $v['name'] }}"/>
                @endforeach
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">相關配件:</label>
            <div class="layui-input-block">
                <div id="accessory" class="demo-transfer" align="left"></div>
                <button type="button" class="layui-btn" lay-demotransferactive="getData">获取右侧数据</button>
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label label-required-next">新品:</label>
            <div class="layui-input-block">
                <input type="checkbox" name="is_new" lay-skin="switch" lay-filter="is_new"
                       lay-text="是|否">
            </div>
        </div>
        <div class="layui-form-item">
            <div class="layui-inline">
                <label class="layui-form-label label-required-next">熱門商品:</label>
                <div class="layui-input-inline">
                    <input type="checkbox" name="is_hot" lay-skin="switch" lay-filter="is_hot"
                           lay-text="是|否">
                </div>
            </div>
            <div class="layui-inline">
                <label class="layui-form-label label-required-next">推薦商品:</label>
                <div class="layui-input-inline">
                    <input type="checkbox" name="is_rec" lay-skin="switch" lay-filter="is_rec"
                           lay-text="是|否">
                </div>
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">有效時間:</label>
            <div class="layui-input-inline">
                <input type="text" name="start_time" id="start_time" class="layui-input" placeholder="請選擇起始日期">
            </div>
            <div class="layui-input-inline">
                <input type="text" name="end_time" id="end_time" class="layui-input" placeholder="請選擇結束日期">
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
<script src="{{ ADMIN_JS }}/webuploader.own.js"></script>
<script>
    var verify = { //自定義表單驗證規則
    };

    layui.use(['form', 'layedit', 'laydate', 'treeSelect', 'transfer','util'], function(){
        var form = layui.form;
        var $ = layui.$;
        var laydate = layui.laydate;
        var treeSelect = layui.treeSelect;
        var transfer = layui.transfer;
        var util = layui.util;

        get_options($('#store_id').val(), $('#cat_id').val(), '#cat_id');
        //在layui中使用jquery觸發select的change事件無效,改用此監聽
        form.on('select(store_id)', function(data){
            var store_id = data.value;
            var id = $('#cat_id').val();
            treeSelect.revokeNode('cat_id', function(d){ //撤销选中节点
                console.log(d);
                console.log(d.treeId);
            });
            treeSelect.destroy('cat_id'); //先销毀數據
            get_options(store_id, id, '#cat_id');
        });
        function get_options(store_id, id, element) {
            treeSelect.render({ //渲染
                elem: '#cat_id', //选择器
                data: '{{ route('admin.common.ajax_get_goods_cat') }}?store_id=' + store_id, // 数据
                headers: {}, // 请求头
                type: 'get', // 异步加载方式：get/post，默认get
                placeholder: '請選擇', // 占位符
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
                    if(store_id && id != 0) {
                        treeSelect.checkNode('cat_id', id); //选中节点，根据id筛选
                    }
                    treeSelect.refresh('cat_id');
                }
            });
        }

        laydate.render({
            elem: '#start_time',
            format: 'yyyy/MM/dd',
            trigger: 'click', //解决日期组件弹框一闪而过,指定被选元素要触发的事件
            //value: new Date(),
            min: '{{ $today }}',
        });
        laydate.render({
            elem: '#end_time',
            format: 'yyyy/MM/dd',
            trigger: 'click', //解决日期组件弹框一闪而过,规定被选元素要触发的事件
            min: '{{ $today }}',
        });

        form.verify({
        });
        form.on('submit(save)', function(data){
            var getData = transfer.getData('accessory'); //唯一标识
            var ids = new Array();
            for(var i=0;i<getData.length;i++){
                ids.push(getData[i].value);
            }
            data.field["accessory"] = ids; //送字段,不需在页面上再定义hidden

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

        //获取数据
        var data ='{!! $accessories !!}';//后台json字符串
        data = JSON.parse(data); //数据格式解析
        var values = new Array();
        var data_selected = '[]'; //后台json串
        data_selected = JSON.parse(data_selected);
        for(var i=0;i<data_selected.length;i++){
            values.push(data_selected[i].id);
        }

        transfer.render({
            elem: '#accessory',
            title: ['未选数据', '已选数据'], //自定义标题
            width: 200,  //定义宽度
            height: 300,  //定义高度
            parseData: function(res){//数据参数来源data转换处理
                return {
                    "value": res.id, //data属性
                    "title": res.name, //data属性
                    //"disabled": res.disabled, // false  //是否禁用
                    //"checked": res.checked, // false //是否选中
                }
            },
            data: data, //数据参数来源
            showSearch: false, //显示搜索框
            id: "accessory", //唯一标识
            value: values, //用于回显或默认选中
        });

        //util监听事件
        util.event('lay-demotransferactive',{
            getData:function(){//获取右侧数据
                var getData = transfer.getData('accessory'); //唯一标识
                layer.alert(JSON.stringify(getData)); //序列化成 JSON 字符串
            },
            reload:function(){//重新加载
                transfer.reload('accessory',{
                    value: values
                });
            }
        });
    });
</script>
