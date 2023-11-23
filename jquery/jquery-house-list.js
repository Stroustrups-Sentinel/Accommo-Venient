/*
    -- #filename       : jquery-house-lists.js
    -- #authors-quote  : This Project Belongs to the CTHSC students Fortunate .M, Phillippa .M and Mc Samuel .S *2020-February-Semester
    -- ********************************************************************************************************************************* 
    --- this is a JQUERY script
    --- it was built to be used with  JQuery v3.4.1
    --- 

*/
var SearchLoaded = false;
var inputBorderColor = '';
var inputBgColor = '';
var inputFontColor = '';

//function(s)
function redirToDetailsPage($redirbtn) {
    let $parent = $redirbtn.parent('.extra-info').parent('.slide-extra').prev('.slide-img');

    //put value into session storage 
    sessionStorage.redirAccomType = $parent.attr('acctype'),
        sessionStorage.redirHouseID = $parent.attr('hseid'),
        sessionStorage.redirUserID = $parent.attr('nid')

    //change location
    window.location = "house-details.html";
}


$(document).ready(function() {

    //get page info 
    if (sessionStorage.searchText) {
        var searchVal = sessionStorage.searchText; //from browser
        var searchInputText = document.getElementById('search-home');
        searchInputText.setAttribute('value', searchVal); //take users previous input and use it here
    } else {
        var searchVal = 'a'; //if information does not exist then query all
    }
    if (!SearchLoaded) {
        //alert('in house working');
        //query server for information from the page
        $.ajax({
            type: "GET",
            timeout: 60000, //60sec->1min
            beforeSend: function() {
                $('.house-list').html('<div class="min-adv">Loading Page information..</div>').slideDown();
            },
            url: "php/search.php",
            data: { search: searchVal },
            dataType: "html",
            timeout: 30000, //a 1/2 minute will be enough i guess
            beforeSend: function() {
                $('.house-list').append('<div class="loading-anim">' +
                    '<div class="load-i"></div>' +
                    '<div class="load-i"></div>' +
                    '<div class="load-i"></div>' +
                    '<div class="load-i"></div>' +
                    '<div class="load-i"></div>' +
                    '</div>');
            }
        }).done(function(response) {
            $('.house-list').html(response);
            //search is complete
            SearchLoaded = true;
            //add event listerner
            $('.view-more').on('click', function(e) {
                e.preventDefault();
                redirToDetailsPage($(this))
            });
            console.log('Finished loading data');
        }).fail(function(_jXHR, statusText) {
            $('.house-list').html('<p class="error">failed: list could not be fetched :' + statusText + '</p>').fadeIn();
        }).always(function() {
            console.log('done with loading house-list page');
            $('div.loading-anim').remove();
        });
    }

    //whe USER searches for house list
    $('#search-form').submit(function(e) {
        e.preventDefault(); //stops it from loading server data
        //submit data to server via -> an ajax call

        $.ajax({
            type: "GET",
            data: $(this).serialize(),
            dataType: 'html',
            url: 'php/search.php',
            timeout: 30000, //a 1/2 minute will be enough i guess
            beforeSend: function() {
                $('.house-list').append('<div class="loading-anim">' +
                    '<div class="load-i"></div>' +
                    '<div class="load-i"></div>' +
                    '<div class="load-i"></div>' +
                    '<div class="load-i"></div>' +
                    '<div class="load-i"></div>' +
                    '</div>');
            }
        }).done(function(response) {
            //when done output data
            $('.house-list').html(response).slideDown();
            console.log('Finished loading data');
            //add event listerners
            $('.view-more').on('click', function(e) {
                e.preventDefault();
                redirToDetailsPage($(this))
            });
        }).fail(function(_jXHR, statusText) {
            $('.house-list').html('<p class="error">failed: list could not be fetched :' + statusText + '</p>').fadeIn();
        }).always(function() {
            console.log('done with loading house-list page');
            $('div.loading-anim').remove();
        });
    }); //done with form
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