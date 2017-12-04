@extends('layouts.app')

@section('content')
<div class="container">
    <table id="posts" class="table table-hover" cellspacing="0" width="100%">
            <thead style="background: #fff; border-radius: 5px;">
                <tr>
                    <th>#ID</th>
                    <th>Title</th>
                    <th>Category</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
    </table>
</div>
@endsection

@section('ext_js')
    <script>
        $(document).ready(function() {
            $.ajaxSetup({ headers: { 'csrftoken' : '{{ csrf_token() }}' } });
            $('#posts').DataTable( {
                "processing": true,
                "serverSide": true,
                ajax: {
                    url: '/api/posts/list',
                    type: 'POST'
                },
                "columns": [
                    { "data": "id" },
                    { "data": "title" },
                    { "data": "category" },
                    { "data": "status",
                      "className": "liststatus",
                    },
                    {
                        "data": "id",
                        render : function(data,type,full,meta){
                            if(full.status == 0) {
                                var play = "<a href='javascript:void(0)' data-action='publish' data-status='"+full.status+"' class=' btn btn-success btn-sm updatepoststatus' data-postid='" + full.id + "'><span class='glyphicon glyphicon-play'></span></a>";
                            }else{
                               var play = "<a href='javascript:void(0)' data-action='unpublish' data-status='"+full.status+"' class=' btn btn-warning btn-sm updatepoststatus' data-postid='" + full.id + "'><span class='glyphicon glyphicon-pause'></span></a>";
                            }
                            var view = "<a href='"+baseUrl+"posts/view/"+data+"' target='_blank' data-action='view' class=' btn btn-primary btn-sm' data-postid='" + data + "'><span class='glyphicon glyphicon-eye-open'></span></a>";
                            var edit = "<a href='"+baseUrl+"posts/edit/"+data+"' data-action='edit' class=' btn btn-default btn-sm' data-postid='" + data + "'><span class='glyphicon glyphicon-pencil'></span></a>";
                            var del = "<a href='javascript:void(0)' data-action='delete' class=' btn btn-danger btn-sm delete-post' data-postid='" + data + "'><span class='glyphicon glyphicon-remove'></span></a>";
                            return play + ' ' + view + ' ' + edit + ' ' + del ;
                        }
                    }
                ],
                "columnDefs": [ {
                    "targets": 4,
                    "orderable": false
                } ]
            } );
        } );


        $(document).on('click','.updatepoststatus',function() {
            var status = $(this).attr("data-status");
            var action = $(this).attr("data-action");
            var pid = $(this).attr("data-postid");
            var a = $(this);
            $.ajax({
                url:baseUrl+"api/posts/updatestatus",
                type:"post",
                data: {
                    status:status,
                    action:action,
                    pid:pid,
                    _token: $("input[name='_token']").val()
                },
                beforeSend: function(data) {
                    $(a).parent().parent().css("opacity","0.4");
                },
                success: function(response) {
                    if(response.status=='12200') {
                        if(status == "1" || status == 1) {
                            $(a).attr("data-status","0");
                            $(a).attr("data-action","publised");
                            $(a).removeClass("btn-warning").addClass("btn-success");
                            $(a).find("span").removeClass("glyphicon-pause").addClass("glyphicon-play");
                            $(a).parent().parent().find('.liststatus').html("0");
                        }
                        if(status == "0" || status == 0) {
                            $(a).attr("data-status","1");
                            $(a).attr("data-action","unpublised");
                            $(a).removeClass("btn-success").addClass("btn-warning");
                            $(a).find("span").removeClass("glyphicon-play").addClass("glyphicon-pause");
                            $(a).parent().parent().find('.liststatus').html("1");
                        }
                        toastr.info(response.message);

                    } else {
                        toastr.info(response.message);
                    }
                    $(a).parent().parent().css("opacity","1");
                }
            })
        });


        $(document).on('click','.delete-post',function() {
            var action = $(this).attr("data-action");
            var pid = $(this).attr("data-postid");
            var a = $(this);
            $.ajax({
                url:baseUrl+"api/posts/delete",
                type:"post",
                data: {
                    pid:pid,
                    _token: $("input[name='_token']").val()
                },
                beforeSend: function(data) {
                    $(a).parent().parent().css("opacity","0.4");
                },
                success: function(response) {
                    if(response.status=='12200') {
                        location.reload();

                    } else {
                        alert(response.message);
                    }
                    $(a).parent().parent().css("opacity","1");
                }
            })
        });
    </script>
@endsection