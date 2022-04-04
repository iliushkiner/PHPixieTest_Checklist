$.fn.messageForm = function(url) {
    var form = this;
    var textGroup = form.find('.form-group');
    var errorText = form.find('.error');
    var lock = false;

    form.submit(function (event) {
        event.preventDefault();

        if(lock) {
            return;
        }

        lock = true;
        errorText.text('');
        textGroup.removeClass('has-danger');

        $.post(url, form.serialize())
            .done(function() {
                window.location.reload()
            })
            .fail(function (xhr) {
                errorText.text(xhr.responseText);
                textGroup.addClass('has-danger');
                lock = false;
            });
    });

    form.find('textarea').keydown(function(event) {
        if (event.keyCode == 13) {
            $(form).submit();
            return false;
        }
    });
};

$.fn.checklistForm = function(url) {
    var form = this;
    var textGroup = form.find('.form-group');
    var errorText = form.find('.error');
    var lock = false;

    form.submit(function (event) {
        event.preventDefault();

        if(lock) {
            return;
        }

        lock = true;
        errorText.text('');
        textGroup.removeClass('has-danger');

        $.post(url, form.serialize())
            .done(function() {
                window.location.reload()
            })
            .fail(function (xhr) {
                errorText.text(xhr.responseText);
                textGroup.addClass('has-danger');
                lock = false;
            });
    });

    form.find('input').keydown(function(event) {
        if (event.keyCode == 13) {
            $(form).submit();
            return false;
        }
    });
    
    form.find('button').click(function() {
        if($(this).html() == '-'){
            $(this).html('+');
        } else {
            $(this).html('-');
        }
    });
};