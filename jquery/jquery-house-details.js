/*
    -- #filename       : jquery-house-details.js
    -- #authors-quote  : This Project Belongs to the CTHSC students Fortunate .M, Phillippa .M and Mc Samuel .S *2020-February-Semester
    -- ********************************************************************************************************************************* 
    --- this is a JQUERY script
    --- it was built to be used with  JQuery v3.4.1
    --- 

*/

$(document).ready(function() {

    //get data from server
    $.ajax({
        type: "GET",
        url: "php/house-details.php",
        data: { houseID: sessionStorage.redirHouseID, userID: sessionStorage.redirUserID, accomType: sessionStorage.redirAccomType },
        dataType: "html",
        timeout: 30000, //a 1/2 minute will be enough i guess
        beforeSend: function() {
            $('#complete-info').append('<div class="loading-anim">' +
                '<div class="load-i"></div>' +
                '<div class="load-i"></div>' +
                '<div class="load-i"></div>' +
                '<div class="load-i"></div>' +
                '<div class="load-i"></div>' +
                '</div>');
        }
    }).done(function(response) {
        document.getElementById('complete-info').innerHTML = response; //used some Vanilla/Pure JavaScript here
        //add event listeners
        $('#alias').toggle();
        //toggle alias input
        $('#alias-comment').on('click', function() {
            $('#alias').toggle();
        })
    }).fail(function(_jXHR, statusText) {
        console.log('failed AJAX :get: request didnt succeed.');
        document.getElementById('complete-info').innerHTML = '<p class="error">Failed: to get house information : ' + statusText + '<p>';
    }).always(function() {
        console.log('completed AJAX :get: request to server.');
        $('div.loading-anim').remove();
    });
    //in case of submitting comments 
    if (document.getElementById('comment-form')) { //check if query is available
        $('#comment-form').submit(function(e) {
            e.preventDefault(); //stop page reload
            $.ajax({
                url: 'php/house-details.php',
                type: 'POST',
                data: { nameAlias: $('#alias').val(), commentTxt: $('#comment-box').val() },
                dataType: 'html',
                timeout: 30000, //a 1/2 minute will be enough i guess
                beforeSend: function() {
                    $('.house-details').append('<div class="loading-anim">' +
                        '<div class="load-i"></div>' +
                        '<div class="load-i"></div>' +
                        '<div class="load-i"></div>' +
                        '<div class="load-i"></div>' +
                        '<div class="load-i"></div>' +
                        '</div>');
                }
            }).done(function(commentResponse) {
                $('.house-details').html(commentResponse).fadeIn();
            }).fail(function(_jXHR, statusText) {
                $('.house-details').append('<p class="error">failed: on comment submisiion :</p>' + statusText).fadeIn();
            }).always(
                function() {
                    $('div.loading-anim').remove();
                }
            );

        });
    }
});