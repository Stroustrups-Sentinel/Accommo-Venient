/*
    -- #filename       : jquery-index.js
    -- #authors-quote  : This Project Belongs to the CTHSC students Fortunate .M, Phillippa .M and Mc Samuel .S *2020-February-Semester
    -- ********************************************************************************************************************************* 
    ---  this is a JQUERY script
    --- it was built to be used with  JQuery v3.4.1
    --- 


*/
//global variables here
var SearchLoaded = false;
var getSearch = '';

var inputBorderColor = '';
var inputBgColor = '';
var inputFontColor = '';
//function(s)
function redirToDetailsPage($redirbtn) {
    let $parent = $redirbtn.parent('p').parent('.slide-info').parent('.slide-img');

    //put value into session storage 
    sessionStorage.redirAccomType = $parent.attr('acctype'),
        sessionStorage.redirHouseID = $parent.attr('hseid'),
        sessionStorage.redirUserID = $parent.attr('nid')

    //change location
    window.location = "house-details.html";
};

$(document).ready(function() {
    /* alert("index.html"); */
    //load page from server

    if (!SearchLoaded) {
        $.ajax({
            type: "GET",
            timeout: 60000, //a minute will be enough i guess
            beforeSend: function() {
                $('.slideshow-pictures').html('<div class="loading-anim">' +
                    '<div class="load-i"></div>' +
                    '<div class="load-i"></div>' +
                    '<div class="load-i"></div>' +
                    '<div class="load-i"></div>' +
                    '<div class="load-i"></div>' +
                    '</div>');
            },
            url: "php/search.php", //get info from server
            data: "search=a",
            dataType: "html"
        }).done(function(response) {
            $('.slideshow-pictures').html(response).slideDown(); //output info onto page
            //add event listerners
            $('.info-view-more').on('click', function() {
                console.log('clicked view more');
                redirToDetailsPage($(this));
            });
            //search is complete
            SearchLoaded = true;
            console.log('Finished loading data');
        }).fail(function() {
            $('.slideshow-pictures').html('<p class="error">failed: data could not be fetched</p>');
        }).always(function() {
            console.log('done with loading page');
        });


        //input search info into browser
        $('#search-home').keyup(function() {
            var searchText = $(this).val();
            sessionStorage.setItem('searchText', searchText); //passing value to browser local storage
        });;

    }

    $('#search-form').submit(function(e) {
        e.preventDefault();
        //post search data to server
        $.ajax({
            url: 'php/search.php',
            data: { search: sessionStorage.searchText, redirect: 'true' },
            dataType: 'text',
            timeout: 15000, //a 1/4 minute will be enough i guess
            beforeSend: function() {
                $('.slideshow-pictures').html('<div class="loading-anim">' +
                    '<div class="load-i"></div>' +
                    '<div class="load-i"></div>' +
                    '<div class="load-i"></div>' +
                    '<div class="load-i"></div>' +
                    '<div class="load-i"></div>' +
                    '</div>');
            },
        }).done(
            function(response) {
                $('.slideshow-pictures').html(response);
                console.log('AJAX request was a success.');
            }
        ).fail(function(_jXHR, statusText) { console.log('AJAX request was a failure. cause : ' + statusText); }).always(function() {
            console.log('AJAX request process is complete.');
        });
        //redirect to page
        window.location = 'house-list.html';
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