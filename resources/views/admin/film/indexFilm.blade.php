@extends('admin.layout.index')
@section('content')
	<div class="container-fluid am-cf">
        <div class="row">
            <div class="am-u-sm-12 am-u-md-12 am-u-lg-9">

                <div class="page-header-heading"><span class="am-icon-home page-header-heading-icon"></span> 电影管理 <small>江洋八子</small></div>

                <p class="page-header-description">简单组合。。。干不简单的事情。。。</p>
            </div>
            {{--<!-- 搜索 -->--}}
            <div class="am-fl tpl-header-search">
                <form class="tpl-header-search-form" action="{{url('admin/film')}}">
                    <button class="tpl-header-search-btn am-icon-search" type="submit"></button>
                    <input class="tpl-header-search-box" name="search" type="text" value="{{$search['search'] or ''}}" placeholder="搜索电影名。.">
                </form>
            </div>
        </div>
    </div>
    
    <div class="row-content am-cf">
    {{--显示表格--}}
        <div class="am-u-sm-12 am-u-md-12 am-u-lg-12">
            <div class="widget am-cf">
                <div class="widget-head am-cf">
                    <div class="widget-title am-fl">电影列表</div>
                </div>
                <div class="widget-body  widget-body-lg am-fr">
                    <table width="100%" class="am-table am-table-compact am-table-striped tpl-table-black " id="example-r">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>电影名称</th>
                                <th>缩略图</th>
                                <th>价格</th>
                                <th>类型</th>
                                <th>地区</th>
                                <th>年份</th>
                                <th>操作</th>
                            </tr>
                        </thead>
                        <tbody>
                        @foreach($data as $film)
                            <tr class="gradeX">
                                <td>{{$film->id}}</td>
                                <td><a href="{{url('admin/film')}}/{{$film->id}}">{{$film->name}}</a></td>

                                <td><img style="max-width:200px" src="{{$film->film_pic}}"></td>

                                <td>{{$film->price}}</td>
                                <td>{{$type[$film->_type]}}</td>
                                <td>{{$type[$film->area_type]}}</td>
								<td>{{$type[$film->year]}}</td>
                                <td>
                                    <div class="tpl-table-black-operation">

                                        <a href="{{url('admin/film')}}/{{$film->id}}/edit" title="编辑">
                                            <i class="am-icon-pencil"></i> 编辑
                                        </a>
                                        <a href="javascript:;" onclick="member_delete('{{$film->id}}')" class="tpl-table-black-operation-del" title="删除">
                                            <i class="am-icon-trash"></i> 删除
                                        </a>
                                        <a href="{{url('admin/review')}}/{{$film->id}}" class="tpl-table-black-operation-del" title="电影评论">
                                            <i class="am-icon-trash"></i> 电影评论
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                   @include('admin.layout.render')

                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script>
        function member_delete(id) {

            layer.confirm('真要删除这个电影吗？', {
                btn: ['忍痛删除','舍不得'] //按钮
            }, function(){
                $.post(
                    '{{url('admin/film')}}/'+id,

                    {
                        '_method' : 'delete',
                        '_token' : '{{csrf_token()}}'
                    },
                    function (msg){
                        var icon= 0;
                        if (msg.status){
                            icon = 5;
                        } else {
                            icon = 6;
                            setTimeout(function(){

                                location.href = '{{asset('/admin/film')}}';

                            },1000);
                        }
                        layer.alert(msg.msg,{icon:icon});
                    },
                    'json'
                );
            }, function(){

            });
        }
    </script>
@endsection