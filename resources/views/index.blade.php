<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <meta name="keywords" content="免费代理IP,代理IP,高匿IP,优质IP,全球免费代理,最新IP"/>
    <meta name="description" content="全球免费代理IP库，高可用IP，精心筛选优质IP，2s必达,每秒持续更新"/>
    <title>{{ $title }}高可用全球免费代理IP库</title>
    <link rel="stylesheet" href="/layui/css/layui.css">
    <link rel="stylesheet" href="/css/main.css">
    @include('layout.common_js')
</head>
<body>
<div class="layui-layout layui-layout-admin">

    @include("layout.header")

    <div class="layui-row">
        <div class="layui-col-md9 ip-tables">
            <div class="layui-form">
                <table class="layui-table">
                    <thead>
                    <tr>
                        <th>IP</th>
                        <th>端口</th>
                        <th>匿名度</th>
                        <th>类型</th>
                        <th width="160">位置</th>
                        <th>所属地区</th>
                        <th>运营商</th>
                        <th>响应速度</th>
                        <th>存活时间</th>
                        <th>最后验证时间</th>
                        <th width="100"></th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($proxy_ips as $key => $proxy_ip)
                        @if($key == intval($proxy_ips->count() / 2) && $index_center_ad)
                            <tr>
                                <td colspan="11">
                                    <div>
                                        {!! $index_center_ad->ad_content !!}
                                    </div>
                                </td>
                            </tr>
                        @endif
                        <tr>
                            <td>{{ $proxy_ip->ip }}</td>
                            <td>{{ $proxy_ip->port }}</td>
                            <td>{{ \App\Http\Common\Helper::formatAnonymity($proxy_ip->anonymity) }}</td>
                            <td>{{ strtoupper($proxy_ip->protocol) }}</td>
                            <td>{{ $proxy_ip->ip_address }}</td>
                            <td>{{ $proxy_ip->country }}</td>
                            <td>{{ $proxy_ip->isp }}</td>
                            <td>{{ \App\Http\Common\Helper::formatSpeed($proxy_ip->speed) }}</td>
                            <td>{{ \App\Http\Common\Helper::formatDateDay($proxy_ip->created_at) }}</td>
                            <td>{{ $proxy_ip->validated_at }}</td>
                            <td>
                                <button class="layui-btn layui-btn-sm btn-copy"
                                        data-url="{{ sprintf("%s://%s:%s",$proxy_ip->protocol,$proxy_ip->ip,$proxy_ip->port) }}"
                                        data-unique-id="{{ $proxy_ip->unique_id }}">复制
                                </button>
                                <button class="layui-btn layui-btn-sm btn-speed "
                                        data-url="{{ sprintf("%s://%s:%s",$proxy_ip->protocol,$proxy_ip->ip,$proxy_ip->port) }}"
                                        data-protocol="{{ $proxy_ip->protocol }}" data-ip="{{ $proxy_ip->ip }}"
                                        data-port="{{ $proxy_ip->port }}" data-unique-id="{{ $proxy_ip->unique_id }}">测速
                                </button>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>


            <div id="paginate"></div>
        </div>
        <div class="layui-col-md3 ad-area">
            @include('layout.right_nav')
        </div>
    </div>

    <div class="modal fade" id="modal-speed" tabindex="-1" style="display: none">
        <div class="modal-body">
            <form class="layui-form layui-form-pane">
                <div class="layui-form-item layui-form-text">
                    <label for="recipient-name" class="layui-form-label">代理地址</label>
                    <div class="layui-input-block">
                        <input type="text" class="layui-input" id="proxy-ip-address" readonly>
                    </div>
                    <input type="hidden" class="form-control" id="proxy-ip">
                    <input type="hidden" class="form-control" id="proxy-port">
                    <input type="hidden" class="form-control" id="proxy-protocol">
                </div>
                <div class="layui-form-item layui-form-text">
                    <label for="message-text" class="layui-form-label">访问地址</label>
                    <div class="layui-input-block">
                        <input class="layui-input" id="web-link" value="http://www.baidu.com">
                    </div>
                </div>
                <div class="layui-form-item layui-form-text">
                    <label for="message-text" class="layui-form-label">访问结果</label>
                    <div class="layui-input-block">
                        <iframe  id="proxy-iframe" style="min-height: 300px;width: 100%" name="proxy-iframe"></iframe>
                    </div>
                </div>
            </form>
        </div>
    </div>

</div>

@include("layout.footer")
<script src="/layui/layui.all.js"></script>
<script src="https://cdn.bootcss.com/jquery/3.2.1/jquery.min.js"></script>
<script src="https://cdn.bootcss.com/clipboard.js/1.5.16/clipboard.min.js"></script>
<script>
    /*JavaScript代码区域*/
    layui.use('element', function () {
        var element = layui.element;

    });

    /*页面参数*/
    var pagePrams = {
        page: "{{ $proxy_ips->currentPage() }}",
        protocol: "{{ isset($_GET['protocol']) ? $_GET['protocol'] : "" }}",
        anonymity: "{{ isset($_GET['anonymity']) ? $_GET['anonymity'] : "" }}",
        country: "{{ isset($_GET['country']) ? $_GET['country'] : "" }}",
        isp: "{{ isset($_GET['isp']) ? $_GET['isp'] : "" }}",
    };
    /*页面配置*/
    var pageConfig = {
        autoRefresh: true,
        refreshIntervalTime: 30000,
    };

    /*组装链接参数*/
    function makeUrlParams(obj) {
        var params = [];
        for (var key in obj) {
            if (obj[key] != "") {
                params.push(key + '=' + obj[key])
            }
        }
        return params.join("&")
    };

    /*刷新页面*/
    function refreshPageAction() {
        window.location.href = "/?" + encodeURI(makeUrlParams(pagePrams))
    };

    /*初始化自动刷新*/
    function initAutoRefresh() {
        window.setInterval(function () {
            if (!pageConfig.autoRefresh) {
                return;
            }
            refreshPageAction();
        }, pageConfig.refreshIntervalTime);
    };

    $(function () {

        $(".ad-area").css("width", "540px");
        $(".ip-tables").css("width", $(".layui-row").innerWidth() - $(".ad-area").innerWidth() - 15);

        initAutoRefresh();

        /*分页渲染*/
        var laypage = layui.laypage;
        laypage.render({
            elem: 'paginate',
            count: "{{ $proxy_ips->total() }}",
            limit: "{{ $proxy_ips->perPage() }}",
            layout: ['count', 'prev', 'page', 'next', 'skip'],
            curr: pagePrams.page,
            jump: function (obj, first) {
                if (!first) {
                    pagePrams.page = obj.curr;
                    refreshPageAction();
                }
            }
        });

        /*复制粘贴功能*/
        var clipboard = new Clipboard(".btn-copy", {
            text: function (_this) {
                return $(_this).attr('data-url');
            }
        });
        clipboard.on("success", function (t) {
            alert('复制成功!');
        }).on("error", function (t) {
            alert('复制失败!');
        });

        /*提交测速*/
        function ipSpeed() {
            var src = '/api/web-request-speed?protocol=' + $("#proxy-protocol").val() + '&ip=' + $("#proxy-ip").val() + '&port=' + $("#proxy-port").val() + '&web_link=' + encodeURIComponent($('#web-link').val());
            $('#proxy-iframe').contents().find("html").html("");
            $('#proxy-iframe').attr('src', src);
        };

        $('.btn-speed').on('click', function () {
            $('#proxy-ip-address').val($(this).attr('data-url'));
            $("#proxy-ip").val($(this).attr('data-ip'));
            $("#proxy-port").val($(this).attr('data-port'));
            $("#proxy-protocol").val($(this).attr('data-protocol'));
            layer.open({
                type: 1,
                title: "IP测速",
                area: ['840px', '640px'],
                shadeClose: false,
                content: $('#modal-speed'),
                btn: ['立即测速'],
                yes: function () {
                    ipSpeed();
                },
                success: function (index, layero) {
                    pageConfig.autoRefresh = false;
                    ipSpeed();
                },
                cancel: function (index, layero) {
                    pageConfig.autoRefresh = true;
                }
            });
        });

        /*广告*/
        var adInterval = setInterval(function () {
            $("#BottomMsg").removeAttr("style");
        }, 100);
        setTimeout(function(){
            clearInterval(adInterval)
        },6000);
    });
</script>
</body>
</html>