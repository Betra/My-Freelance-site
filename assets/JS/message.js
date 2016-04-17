$(document).ready(function() {
    $("#ajax").submit(function() {
        var form = $(this);
        var error = false;
        form.find('textarea').each(function() {
            if($(this).val() == '') {
                alert('Заполните поле "'+$(this).attr('placeholder')+'"!');
                error = true;
            }
        });
        if(!error) {
            var data = form.serialize();
            $.ajax({
                type: 'POST',
                url: 'send.php',
                dataType: 'json',
                data: data,
                beforeSend: function(data) {
                    form.find('input[type="submit"]'.attr('disabled','disabled'))
                },
                success: function(data) {
                    if(data['error']) {
                        alert(data['error']);
                    }
                    else {
                        alert('Письмо отправлено! Ждите ответа :)');
                    }
                },
                error: function(xhr,ajaxOptions,throwError) {
                    alert(xhr.status);
                    alert(throwError);
                },
                complete: function(data) {
                    form.find('input[type="submit"]').prop('disabled',false);
                }
            });
        }
        return false;

    });
});