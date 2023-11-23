/*
    -- #filename       : jquery-event-styling.js
    -- #authors-quote  : This Project Belongs to the CTHSC students Fortunate .M, Phillippa .M and Mc Samuel .S *2020-February-Semester
    -- ********************************************************************************************************************************* 
    --- this is a JQUERY script
    --- it was built to be used with  JQuery v3.4.1
    --- 

*/
var inputBorderColor = '';
var inputBgColor = '';
var inputFontColor = '';
var p1 = '';
var p2 = '';





$(document).ready(function() {
    //hide the <p>tag
    $('#password-unmatched').fadeOut().hide();
    $('#email-unmatched').fadeOut().hide();
    $('#inline-br-e').fadeOut().hide();
    $('#inline-br-p').fadeOut().hide();


    //check if user is already logged in
    $.ajax({
        type: 'POST',
        url: 'php/sessions.php',
        data: { checkLogin: "check" },
        dataType: 'html',
        timeout: 30000, //a minute will be enough i guess
        beforeSend: function() {
            $('#sign-in').after('<div class="loading-anim">' +
                '<div class="load-i"></div>' +
                '<div class="load-i"></div>' +
                '<div class="load-i"></div>' +
                '<div class="load-i"></div>' +
                '<div class="load-i"></div>' +
                '</div>');
        }
    }).done(function(data) {

        console.log('done');
        $('#sign-in').after(data);

    }).always(function() {
        $('div.loading-anim').remove();
    })



    //input focus and leave

    $('input').on('focus', function() {
        //capture values 1st
        sessionStorage.inputBorderColor = $(this).css('border-color');
        inputBgColor = $(this).css('background-color');
        inputFontColor = $(this).css('color');
        //change the border colour to :#ff533d
        $(this).css({ 'border-color': '#AB987A', 'border-style': 'dashed' });
        //change the bg colour to #0f1626:
        $(this).css('background-color', '#0f1626');
        //change the bg to : #f5f5f5
        $(this).css('color', '#f5f5f5');

    });
    $('input').on('blur', function() {
        //check if changed

        //change back the border colour 
        $(this).css({ 'border-color': inputBorderColor, 'border-style': 'solid' });
        //change back the bg colour 
        $(this).css('background-color', inputBgColor);
        //change back the font
        $(this).css('color', inputFontColor);

    });

    //submit login info via ajax
    $('#login-form').on('click', 'button', function(e) {
        e.preventDefault();
        //debug :console.log('submit button clicked');
        $.ajax({
            type: 'POST',
            url: $('#login-form').attr('action'),
            data: $('#login-form').serialize(),
            dataType: 'html',
            timeout: 30000, //a minute will be enough i guess
            beforeSend: function() {
                $('.login').append('<div class="loading-anim">' +
                    '<div class="load-i"></div>' +
                    '<div class="load-i"></div>' +
                    '<div class="load-i"></div>' +
                    '<div class="load-i"></div>' +
                    '<div class="load-i"></div>' +
                    '</div>');
            }
        }).done(function(data) {
            console.log('completed ajax login request');
            $('div.loading-anim').remove();
            $('.login').append('<div class="failed-login">' + data + '</div>'); //show data
            $('.login input').on('keyup', function() { //remove the error prompt when user presses key
                $('.failed-login').one().remove();
            });

        }).always(function() {
            console.log('ajax request is now complete');
        }).fail(function(_jXHR, err) {
            $('.login').append('<p class="failed-login">' + err + '</p>');
            console.error('Ajax login request has failed due to :' + err)
        });
    });





    //add event listener for passwords and validate them
    $('#password-c').on('keyup', function() {
        //add values to var
        validateStrings('#password-c', '#password', '#password-unmatched', 'passwords do not match', '#inline-br-p');
    });

    //add event listener for emails and validate
    $('#email-c').on('keyup', function() {
        //add values to var
        validateStrings('#email-c', '#email', '#email-unmatched', 'emails do not match', '#inline-br-e');
    });

    function validateStrings(str1JQId, str2JQId, outputElementJQId, outputElementText, inlineBrJQId) {
        p1 = $(str1JQId).val();
        p2 = $(str2JQId).val();

        if (p1.length > 4 && p2.length > 4) {
            if (p1 != p2) {
                $(inlineBrJQId).fadeIn();
                $(outputElementJQId).text(outputElementText).fadeIn(300);
            } else if (p1 == p2) {
                $(outputElementJQId).fadeOut().hide();
                $(inlineBrJQId).fadeOut().hide();
            }
        } else {
            $(outputElementJQId).fadeOut().hide();
            $(inlineBrJQId).fadeOut().hide();
        }


        console.log('p1 is :' + p1 + '.....p2 is :' + p2);
        console.log('p1.length > 5 is :' + (p1.length > 5) + 'p2.length > 5 is :' + (p2.length > 5))
        console.log(' p1.length > 5 && p2.length > 5 is :' + (p1.length > 5 && p2.length > 5));
    }


});