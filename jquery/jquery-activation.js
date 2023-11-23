/*
    -- #filename       : jquery-activation.js
    -- #authors-quote  : This Project Belongs to the CTHSC students Fortunate .M, Phillippa .M and Mc Samuel .S *2020-February-Semester
    -- ********************************************************************************************************************************* 
    --- this is a JQUERY script
    ---  built to be used with  JQuery v3.4.1
    --- 

*/
//global variables
var inputBorderColor = '';
var inputBgColor = '';
var inputFontColor = '';


//when everything has finished loading
$(document).ready(function() {
    //hide tags
    $('#password-unmatched').fadeOut().hide();
    $('.inline-br-p').fadeOut().hide();


    //on submission
    $('#acc-recovery').on('submit', function(e) {
        e.preventDefault();
        $.ajax({
            url: 'php/activation.php',
            type: 'POST',
            data: $(this).serialize(),
            dataType: 'html',
            timeout: 15000, //15sec
            beforeSend: function() {
                $('.inner-box').append('<div class="loading-anim">' +
                    '<div class="load-i"></div>' +
                    '<div class="load-i"></div>' +
                    '<div class="load-i"></div>' +
                    '<div class="load-i"></div>' +
                    '<div class="load-i"></div>' +
                    '</div>');
            }
        }).done(function(response) {
            //load response data
            $('.inner-box em').empty();
            $('.inner-box').append(response);

            //hide button
            $('#recover-btn').hide().fadeOut();
            //console.log
            console.log('loading data was a success, response  :' + response);
        }).fail(function(_jXHR, responseTxt) {
            //load response data
            $('#form-elements').text('<p class="error">failed: Request was not successful :' + responseTxt + '</p>').fadeIn();
            //hide button
            $('#recover-btn').hide().fadeOut();
            // log failure
            console.log('loading data from the server failed ,response error text is :' + responseTxt);

        }).always(function() {
            console.log('the ajax call is now complete !');
            $('div.loading-anim').remove();
        });

    }); //complete with submission


    //add event listener for passwords and validate them
    $('#password-c').on('keyup', function() {
        //add values to var
        validateStrings('#password-c', '#password', '#password-unmatched', 'passwords do not match', '.inline-br-p');
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
    }

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