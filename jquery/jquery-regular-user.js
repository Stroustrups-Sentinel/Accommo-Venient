/*
    -- #filename       : jquery-regular-user.js
    -- #authors-quote  : This Project Belongs to the CTHSC students Fortunate .M, Phillippa .M and Mc Samuel .S *2020-February-Semester
    -- ********************************************************************************************************************************* 
    --- this is a JQUERY script
    --- it was built to be used with  JQuery v3.4.1
    --- 

*/

//global variables
var inputBorderColor = '';
var inputBgColor = '';
var inputFontColor = '';
var imgDelFormObj = {};
var formDataObj = {};
var tempInputVal = '';
var tempInputObj = {};
var canceled = false;
var logging = false;


//get user data from server
//
$(document).ready(function() {

    //call for user data jquery
    //if (!logging) {
    $.ajax({
        type: "POST",
        url: "php/regular-user.php",
        dataType: "html",
        timeout: 30000, //a minute will be enough i guess
        beforeSend: function() {
            $('.user-details').prepend('<div class="loading-anim">' +
                '<div class="load-i"></div>' +
                '<div class="load-i"></div>' +
                '<div class="load-i"></div>' +
                '<div class="load-i"></div>' +
                '<div class="load-i"></div>' +
                '</div>');
        }
    }).done(function(responseData) {
        $('.user-details').html(responseData);
        console.log('Ajax: request completed with success');


        //From here onwards contains the necessary functions for manipulation of the reply data

        //on clicking add new profile
        $('#prof-add').on('click', function(e) {
                e.preventDefault();
                console.log('Profile about to about added');
                //prepare modal data
                let addPicmd = '<div class="add-pic">' +
                    '<form id="modal-add-pic-form" method="post" action="php/regular-user.php">' +
                    '<br/>' +
                    ' <input type="file" accept="image/*" title="images only" name="newProfPic" required/>' +
                    ' <button class="md-btn-yes" type="submit">Upload Profile</button> <button class="md-btn-no">Cancel Upload</button>' +
                    '</form>' +
                    '</div>';
                //call modal
                modal.open({ content: addPicmd });
                //add event listeners
                $('#modal-add-pic-form').on('click', '.md-btn-yes', function(e) {
                    e.preventDefault();
                    //prepare ajax and request
                    $.ajax({
                        url: $('#modal-add-pic-form').attr('action'),
                        type: 'POST',
                        data: $('#modal-add-pic-form').serialize(),
                        dataType: 'html',
                        timeout: 60000, //a minute will be enough i guess
                        beforeSend: function() {
                            $('#modal-add-pic-form').append('<div class="loading-anim">' +
                                '<div class="load-i"></div>' +
                                '<div class="load-i"></div>' +
                                '<div class="load-i"></div>' +
                                '<div class="load-i"></div>' +
                                '<div class="load-i"></div>' +
                                '</div>');
                        }
                    }).done(function(dat) {
                        $('#modal-add-pic-form').empty().append(dat + '<button onclick="modal.close()" class="modal-complete">&timesb;close window</button>');
                        console.log('ajax: profile upload request is successful');
                    }).fail(function(_jXHR, err) {
                        $('#modal-add-pic-form').empty().append('<p class="error">Uploading failed with reason: ' + err + '</p>' +
                            '<button onclick="modal.close()" class="modal-complete">&timesb;close window</button>');
                        console.error('Ajax: profile upload request failed with reason :' + err);
                    }).always(function() {
                        console.log('Ajax: profile upload request is now complete');
                        $('div.loading-anim').remove();
                    });

                });
                $('#modal-add-pic-form').on('click', '.md-btn-no', function(e) {
                    e.preventDefault();
                    modal.close();
                });

            })
            //on clicking remove profile
        $('#prof-del').on('click', function(e) {
            e.preventDefault();
            console.log('profile about to be removed');
            //get user reply
            let profileRemoval = window.confirm('Are you sure you want to remove the Profile picture ?.');
            //call ajax if true
            if (profileRemoval) {
                $.ajax({
                    url: 'php/regular-user.php',
                    type: 'POST',
                    data: { deleteProfile: 1 },
                    dataType: 'html',
                    timeout: 30000, //a 1/2 minute will be enough i guess
                    beforeSend: function() {
                        $('.prof-pic-panel').prepend('<div class="loading-anim">' +
                            '<div class="load-i"></div>' +
                            '<div class="load-i"></div>' +
                            '<div class="load-i"></div>' +
                            '<div class="load-i"></div>' +
                            '<div class="load-i"></div>' +
                            '</div>');
                    }
                }).done(function(dat) {
                    $('.prof-pic-panel').append(dat);
                    console.log('ajax: profile removal request is successful');
                }).fail(function(_jXHR, err) {
                    $('.prof-pic-panel').append('<p class="error anim-fade">removing profile failed with reason: ' + err + '</p>');
                    console.error('Ajax: profile removal request failed with reason :' + err);
                }).always(function() {
                    console.log('Ajax: profile removal request is has finished.');
                    $('div.loading-anim').remove();
                });
            }
        });

        //on clicking edit button [profile] {handling of :-> :done: :cancel:}
        $('.edit:not( .pass-btn, .edit-address)').on('click', function() {
            //take variables
            $editButton = $(this);
            $input = $editButton.prev('input');
            $parent = $input.parent();

            tempInputVal = $input.val();
            tempInputObj[$input.attr('name')] = tempInputVal;
            //add cancel and done button
            $editButton.fadeOut(10);
            $parent.append('<button class="edit-cancel">Cancel</button><button class="edit-done">Done</button>').fadeIn(); //add done button
            $('button.edit-cancel').css({ 'background-color': '#FF533D', 'color': '#f5f5f5', 'border': 'unset' });
            $('button.edit-cancel:hover').css({ 'border-bottom': 'solid' });
            //take variables again
            $done = $(this).next().next('.edit-done');
            $cancel = $(this).next('.edit-cancel');
            //make input attr:readonly to false
            $input.attr({ 'readonly': false, 'disabled': false });
            $input.css({ 'color': '#FF533D', 'border-bottom': '#FF533d dotted' });
            //focus input
            $input.focus();
            //on-click and on blur
            $input.on('focus', function() {
                //capture values 1st
                inputBorderColor = $(this).css('border-color');
                inputBgColor = $(this).css('background-color');
                inputFontColor = $(this).css('color');
                //change the border colour to :#ff533d //change the bg colour to #ff533d: //change the bg to : #f5f5f5
                $(this).css({ 'border-color': '#AB987A', 'border-style': 'dashed', 'background-color': '#ff533d', 'color': '#f5f5f5' });
            });
            $input.not('[readonly="true"]').on('blur', function() {
                //check if changed //change back the border colour //change back the bg colour //change back the font
                $(this).css({ 'border': 'unset', 'border-bottom': '#FF533d dotted', 'background-color': 'unset', 'color': 'initial' });
            });
            //      *****************************    handling the CANCEL and DONE buttons
            /*        -   CANCEL **/
            $cancel.click(function() {

                //re-declare vars [ variable re-declaration was done due to an issue that arose when clicking a different var ]
                let $Input = $(this).prev().prev('input');
                let $EditButton = $(this).prev();
                let $Cancel = $(this);
                let $Done = $(this).next();
                let inputVal = '';


                $EditButton.toggle();
                $Input.attr({ 'readonly': true, 'disabled': true });

                $Input.css({ 'border': 'unset', 'color': 'initial', 'background-color': 'initial' });
                inputVal = tempInputObj[$Input.attr('name')];
                $Input.val(inputVal);
                //
                canceled = true;
                if (canceled) {
                    $Done.remove();
                    $Cancel.remove();
                    canceled = false;
                };

            });

            /*        -   DONE **{ the battle between processing power and code length made me choose Duplicating it over making a simple function }*/
            $done.click(function() {

                //re-declare vars [ variable re-declaration was done due to an issue that arose when clicking a different var ]
                let $Input = $(this).prev().prev().prev('input');
                let $EditButton = $(this).prev().prev();
                let $Cancel = $(this).prev();
                let $Done = $(this);
                let inputVal = '';
                let inputName = '';


                $EditButton.toggle();
                $Input.attr({ 'readonly': true, 'disabled': true });

                $Input.css({ 'border': 'unset', 'color': 'initial', 'background-color': 'initial' });
                inputName = $Input.attr('name');
                inputVal = $Input.val();

                formDataObj[inputName] = inputVal;

                canceled = true;
                if (canceled) {
                    $Done.remove();
                    $Cancel.remove();
                    canceled = false;
                };

            });



        });
        //on clicking edit address
        $('button.edit-address').on('click', function() {
            //make vars
            let $editAddrBtn = $(this);
            let $parentAddr = $(this).parent();
            let $Addr = $(this).prev('address');
            //get data
            tempInputVal = $Addr.text();
            //make elements and hide some
            $editAddrBtn.fadeOut(5);
            $Addr.fadeOut(5);
            $parentAddr.append('<input name="address" class="addr" placeholder="Physical Address" value="' + tempInputVal +
                '" /><button class="edit-cancel">Cancel</button><button class="edit-done">Done</button>');
            $('button.edit-cancel').css({ 'background-color': '#FF533D', 'color': '#f5f5f5', 'border': 'unset' });
            $('button.edit-cancel:hover').css({ 'border-bottom': '#f5f5f5 solid' });
            //take variables again
            let $input = $(this).next();
            let $done = $input.next().next('.edit-done');
            let $cancel = $input.next('.edit-cancel');

            $input.css({ 'min-width': '20rem', 'color': '#FF533D', 'border-bottom': '#FF533d dotted' });
            tempInputObj[$input.attr('name')] = tempInputVal;
            //manipulate data
            //on-click and on blur
            $input.on('focus', function() {
                //capture values 1st
                inputBorderColor = $(this).css('border-color');
                inputBgColor = $(this).css('background-color');
                inputFontColor = $(this).css('color');
                //change the border colour to :#ff533d //change the bg colour to #ff533d: //change the bg to : #f5f5f5
                $(this).css({ 'border-color': '#AB987A', 'border-style': 'dashed', 'background-color': '#ff533d', 'color': '#f5f5f5' });
            });
            $input.on('blur', function() {
                //check if changed //change back the border colour //change back the bg colour //change back the font
                $(this).css({ 'border': 'unset', 'border-bottom': '#FF533d dotted', 'background-color': 'unset', 'color': 'initial' });
            });
            //restore data

            /*        -   CANCEL **/
            $cancel.click(function() {

                //re-declare vars [ variable re-declaration was done due to an issue that arose when clicking a different var ]
                let $Input = $(this).prev('input');
                let $EditButton = $(this).prev().prev('.edit-address');
                let $Cancel = $(this);
                let $Done = $(this).next();
                let inputVal = '';
                let $Address = $EditButton.prev('address');


                $EditButton.toggle();
                $Input.toggle();


                inputVal = tempInputObj[$Input.attr('name')];
                $Address.val(inputVal);
                //
                canceled = true;
                if (canceled) {
                    $Address.toggle();
                    $input.remove();
                    $Done.remove();
                    $Cancel.remove();
                    canceled = false;
                };

            });

            /*        -   DONE **{ the battle between processing power and code length made me choose Duplicating it over making a simple function }*/
            $done.click(function() {

                //re-declare vars [ variable re-declaration was done due to an issue that arose when clicking a different var ]
                let $Input = $(this).prev().prev('input');
                let $EditButton = $(this).prev().prev().prev('.edit-address');
                let $Cancel = $(this).prev();
                let $Done = $(this);
                let inputVal = '';
                let inputName = '';
                let $Address = $EditButton.prev('address');


                $EditButton.toggle();
                $Input.toggle();

                inputName = $Input.attr('name');
                inputVal = $Input.val();

                formDataObj[inputName] = inputVal;
                $Address.text(inputVal);


                canceled = true;
                if (canceled) {
                    $Address.toggle();
                    $input.remove();
                    $Done.remove();
                    $Cancel.remove();
                    canceled = false;
                };

            });

        });
        //on clicking the confirm changes button
        //get data val
        let $passInput = $('.confirm-changes');
        passVal = '';
        //take value on keyup
        $passInput.on('keyup', function() { passVal = $(this).val(); })
        $('.pass-btn').on('click', function(e) {
            //prevent Default first
            e.preventDefault();
            //create Vars
            formDataObj[$passInput.attr('name')] = passVal;
            $passInput.val('');
            passVal = '';
            //catch data from input

            //call ajax
            $.ajax({
                type: "POST",
                url: "php/regular-user.php",
                data: formDataObj,
                dataType: "html",
                timeout: 30000, //a 1/2 minute will be enough i guess
                beforeSend: function() {
                    $(this).parent().prepend('<div class="loading-anim">' +
                        '<div class="load-i"></div>' +
                        '<div class="load-i"></div>' +
                        '<div class="load-i"></div>' +
                        '<div class="load-i"></div>' +
                        '<div class="load-i"></div>' +
                        '</div>');
                }

            }).done(function(data) {
                console.log('Ajax for change confirmation was a success :');
                console.log('eureka');
            }).fail(function(_jXHR, Err) {
                console.log('Ajax for change confirmation failed due to :' + Err);
                console.log(Err);
            }).always(function() {
                console.log('Ajax for change confirmation : is now complete');
                console.log(formDataObj);
                $('div.loading-anim').remove();
            });
        });


    }).fail(function(_jXHR, errorTxt) {
        $('.user-details').html('<p class="error">Requesting data failed with reason : ' + errorTxt + '</p>');
        console.log('Ajax: request failed due to :' + errorTxt);
    }).always(function() {
        console.log('Ajax: request is now complete');
        $('div.loading-anim').remove();
    });
    logging = true;

    $('header a#logout-btn').on('click', function(e) {
        e.preventDefault();
        logout = window.confirm(' Are you sure you want to logout ?');

        if (logout) {
            debug: console.log('yep...just do it');
            $.ajax({
                type: "POST",
                url: "php/regular-user.php",
                data: { logoutString: "logout" },
                dataType: "html",
            }).done(
                function(data) {
                    console.log('done logging out');
                    window.location = "login.html";
                }
            );
        }
    });
    //}







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