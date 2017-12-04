$(document).ready(function () {
    $(document).ajaxStart(function() { Pace.restart(); });
    $('#subsciption-form').on('submit',function (e) {
        e.preventDefault();
        var email = $('#email').val();

        try {
            if(email.trim() == '') {
                throw new ValidationFailedException('This field is required');
            }
            if(!validateEmail(email)) {
                throw new ValidationFailedException('Please enter a valid email');
            }
        }catch(e) {
            $('#email').css('border-color','#ff0000');
            $('#email').attr('placeholder',e.message);
            return false;
        }

        // ajax request

        $.post('/api/subscribe', $('#subsciption-form').serialize(),function (response) {
            if(response.status == 12000){
                toastr.success(response.message);
                $('#email').val('');
            }
            else
                toastr.error(response.message);
        });

    });

    $('#email').on('focus',function () {
        $('#email').css('border-color','#00893C');
        $('#email').attr('placeholder','Email');
    });

});


function validateEmail(email) {
    var re = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
    return re.test(email);
}

function ValidationFailedException(value) {
    this.message = value;
    this.toString = function() {
        return this.message;
    };
}


