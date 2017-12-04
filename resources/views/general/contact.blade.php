@extends('layouts.app-main')

@section('content')

    <div class="container my-3">
        <h2>Contact Us</h2>
        <p>Feel free to contact us by filling the form.</p>
        <div class="row">
            <div class="col-md-8">
                <form class="form-horizontal" action={{ route('postForm') }} id="contact" method="POST">
                    {{csrf_field()}}
                    <div class="form-group">
                        <label for="exampleInputName2">Name</label>
                        <input type="text" class="form-control" id="exampleInputName2" placeholder="Name" name="name">
                    </div>
                    <div class="form-group">
                        <label for="exampleInputEmail2">Email</label>
                        <input type="email" class="form-control" id="exampleInputEmail2" placeholder="Email" name="email">
                    </div>
                    <div class="form-group ">
                        <label for="exampleInputText">Your Message</label>
                        <textarea  class="form-control" placeholder="Message" name="message"></textarea>
                    </div>
                    <button type="submit" class="btn btn-success">Send Message</button>
                </form>
            </div>
        </div>
    </div>

@endsection

@section('ext_js')
    <script src="https://cdn.jsdelivr.net/jquery.validation/1.15.1/jquery.validate.min.js"></script>
    <script>
        $(document).ready(function(){
            $("#contact").validate({
                ignore:[],
                onkeyup: false,
                errorClass:'form-err',
                errorPlacement: function(error, element) {
                    return false
                },
                rules: {
                    "name": {
                     required: true
                     },
                     "email": {
                     required: true,
                     email:true
                     },
                     "message": {
                     required: true
                     }
                },
                messages:{
                    "name": {
                        required: "Name is required"
                    },
                    "email": {
                        required: "Email is required"
                    },
                    "message": {
                        required: "Account type is required"
                    }
                },
                invalidHandler: function(e,validator) {
                    for (var i=0;i<validator.errorList.length;i++){
                        //// console.log(validator.errorList[i]);
                    }
                    var str='';
                    str += '<ul style="padding-left:0px;">';
                    //validator.errorMap is an object mapping input names -> error messages
                    for (var i in validator.errorMap) {
                        str += '<li style="list-style:none;">';
                        str = str+'<i class="fa fa-remove"></i>&nbsp; '+validator.errorMap[i] + "</br>";
                        str += '</li>';
                    }
                    str += "</ul>";
                    toastr["error"](str)
                },
                submitHandler: function(form) {
                    var form_data = new FormData();
                    var other_data = $(form).serializeArray();
                    $.each(other_data,function(key,input){
                        form_data.append(input.name,input.value);
                    });


                    $.ajax({
                        url: "{{ route('postForm') }}" ,
                        type: "post",
                        data: form_data,
                        datatype:'json',
                        beforeSend: function(){

                        },
                        processData: false,
                        contentType: false,
                        success: function(response) {
                            if(response.status == 12200) {
                                toastr.success(response.message);
                            }
                        },
                        error: function(xhr,status,response){
                            response = xhr.responseJSON;
                            var errorText = '';
                            $.each(response,function(i,v){
                                errorText += v[0]+"<br/>"
                            });
                            toastr["error"](errorText);
                        }
                    });

                }
            });
        });
    </script>

@endsection