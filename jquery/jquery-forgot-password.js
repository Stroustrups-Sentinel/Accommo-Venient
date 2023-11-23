/*
    -- #filename       : jquery-forgot-password.js
    -- #authors-quote  : This Project Belongs to the CTHSC students Fortunate .M, Phillippa .M and Mc Samuel .S *2020-February-Semester
    -- ********************************************************************************************************************************* 
    --- this is a JQUERY script
    --- it was built to be used with  JQuery v3.4.1
    --- 

*/

var inputBorderColor = '';
var inputBgColor = '';
var inputFontColor = '';




$(document).ready(function() {
    //send details and get message
    $('#submit-recovery-email').submit(function(e) {
        e.preventDefault();
        //send data Asynchronously
        $.ajax({
            url: 'php/forgot-password.php',
            type: 'POST',
            data: { recovery_email: $('#recovery-email').val() },
            dataType: "html",
            timeout: 15000, //15sec
            beforeSend: function() {
                $('#recovery-div').append('<div class="loading-anim">' +
                    '<div class="load-i"></div>' +
                    '<div class="load-i"></div>' +
                    '<div class="load-i"></div>' +
                    '<div class="load-i"></div>' +
                    '<div class="load-i"></div>' +
                    '</div>');
            }
        }).done(function(data) {
            $('#recovery-div').html(data).fadeIn();
            console.log(':AJAX: processing was successful.');
        }).fail(function(_jXHR, statusText) {
            $('#recovery-div').html('<p class="error">failed: process has failed :' + statusText + '<p>').fadeIn();
            console.log(':AJAX: processing has failed ,: ' + statusText);
        }).always(function() {
            console.log(':AJAX: processing is now complete.');
            $('div.loading-anim').remove();
        });

    });




    //****************************************************      GENERIC  UNIFORMED STYLING ******************************************************************************************** */
    //input focus and blur

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

});