/*
    -- #filename       : jquery-landlord-user.js
    -- #authors-quote  : This Project Belongs to the CTHSC students Fortunate .M, Phillippa .M and Mc Samuel .S *2020-February-Semester
    -- ********************************************************************************************************************************* 
    --- this is a JQUERY script
    --- it was built to be used with  JQuery v3.4.1
    --- 

*/

//variables
var inputBorderColor = '';
var inputBgColor = '';
var inputFontColor = '';
var imgDelFormObj = {};
var formDataObj = {};
var LLDetailsObj = {};
var formDataHouseObj = {};
var tempInputVal = '';
var tempInputObj = {};
var tempInputEditVal = '';
var tempInputEditObj = {};
var canceled = false;
var housesObj = {};
var tempHouseObj = {};
var windowRedirect = {};
//detached elements
$modalContents = $('#modal-contents');
$modalContents.detach();

$(document).ready(function() {


    //on logging in
    $.ajax({
        type: "POST",
        url: "php/landlord-user.php",
        dataType: "html",
        timeout: 30000, //a 1/2 minute will be enough i guess
        beforeSend: function() {
            $('.user-details').append('<div class="loading-anim">' +
                '<div class="load-i"></div>' +
                '<div class="load-i"></div>' +
                '<div class="load-i"></div>' +
                '<div class="load-i"></div>' +
                '<div class="load-i"></div>' +
                '</div>');
        },
    }).done(function(responseData) {
        $('.user-details').html(responseData).slideDown();
        console.log('Ajax: login request completed with success');

        //From here onwards contains the necessary functions for manipulation of the reply data

        //on clicking add new profile
        $('#prof-add').on('click', function(e) {
                e.preventDefault();
                console.log('Profile about to about added');
                //prepare modal data
                let addPicmd = '<div class="add-pic">' +
                    '<form id="modal-add-pic-form" method="post" action="php/landlord-user.php">' +
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
                        },
                    }).done(function(dat) {
                        $('#modal-add-pic-form').empty().append(dat + '<button onclick="modal.close()" class="modal-complete">&timesb;close window</button>');
                        console.log('ajax: profile upload request is successful');
                    }).fail(function(_jXHR, err) {
                        $('#modal-add-pic-form').empty().append('<p class="error">Uploading failed with reason: ' + err + '</p>' +
                            '<button onclick="modal.close()" class="modal-complete">&timesb;close window</button>');
                        console.error('Ajax: profile upload request failed with reason :' + err);
                    }).always(function() {
                        console.log('Ajax: profile upload request is now complete');
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
                    url: 'php/landlord-user.php',
                    type: 'POST',
                    data: { deleteProfile: 1 },
                    dataType: 'html',
                    timeout: 30000, //a 1/2 minute will be enough i guess
                    beforeSend: function() {
                        $('.prof-pic-panel').append('<div class="loading-anim">' +
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
            $cancel.click(function(e) {
                e.preventDefault();
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
            $done.click(function(e) {
                e.preventDefault();
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
                url: "php/landlord-user.php",
                data: formDataObj,
                dataType: "html",
                timeout: 30000, //a 1/2 minute will be enough i guess
                beforeSend: function() {
                    $('.prof-pic-panel').append('<div class="loading-anim">' +
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
                //debug: console.log(formDataObj);
                $('div.loading-anim').remove();
            });
        });

        //on clicking delete button [pics]
        $('button.del-button').on('click', function() {
            //declare vars
            let $delButton = $(this);
            let $delImg = $delButton.next('img');
            let $delParent = $(this).parent();
            let $imgDesc = $(this).next().next('.house__pic-desc').children('p').children('b');
            //take image data
            imgDelFormObj['delImgId'] = $delImg.attr('hsen');
            imgDelFormObj['delImgLoc'] = $delImg.attr('hsel');
            //send delete Ajax request
            let delPicConfirm = window.confirm('Are you sure on removing the  ' + $imgDesc.text() + ' picture ?.');
            if (delPicConfirm) {
                $.ajax({
                    type: "POST",
                    url: "php/landlord-user.php",
                    data: imgDelFormObj,
                    dataType: "html",
                    timeout: 30000, //a minute will be enough i guess
                    beforeSend: function() {
                        $delParent.append('<div class="loading-anim">' +
                            '<div class="load-i"></div>' +
                            '<div class="load-i"></div>' +
                            '<div class="load-i"></div>' +
                            '<div class="load-i"></div>' +
                            '<div class="load-i"></div>' +
                            '</div>');
                    }
                }).done(function(data) {
                    $delParent.append(data);
                    console.log('Ajax: delete Request Completed Successfully : image has been deleted');
                    //remove pic on success
                    $delParent.empty().remove();

                }).fail(function(_jXHR, ErrStatus) {
                    $delParent.append('<br><p class="error">Delete failed : ' + ErrStatus + '</p>');
                    console.log('Ajax: deletion Failed with reason : ' + ErrStatus);
                }).always(function() {
                    console.log('Ajax: delete image request is now complete.');
                    $('div.loading-anim').remove();
                });
            }

        });
        //on clicking add photo [pics]
        $('.picTxt_div').on('click', function(e) {
            e.preventDefault();
            console.log('clicked add photo');
            //generate data for modal to add file
            let addHousePicDat = '<div class="md-add-house-pic">' +
                '<form id="md-house-pic-form" method="Post" action="php/landlord-user.php">' +
                '<br/>' +
                '<div class="prof-dat">' +
                '<p>House Picture</p>' +
                ' <input type="file" accept="image/*" name="AddPicImg" title="images only" name="newProfPic" required/>' +
                '</div>' +
                '<div class="prof-dat">' +
                '<p>Picture Description</p>' +
                ' <input type="text" name="AddPicImgDesc" placeholder="type of picture .eg bedroom, kitchen, bathroom etc" pattern="[a-zA-z\\s]*" required/>' +
                ' <input type="text" name="AddPicHouseId" value="' + $(this).attr('hseid') + '" hidden/>' +
                '</div>' +
                ' <button class="md-btn-yes" type="submit">Add Picture </button> <button class="md-btn-no">Cancel</button>' +
                '</form>' +
                '</div>';
            //call modal
            modal.open({ content: addHousePicDat });
            //add event listeners
            $('#md-house-pic-form').on('click', '.md-btn-yes', function(e) {
                e.preventDefault();
                //prepare ajax and request
                $.ajax({
                    url: $('#md-house-pic-form').attr('action'),
                    type: 'POST',
                    data: $('#md-house-pic-form').serialize(),
                    dataType: 'html',
                    timeout: 30000, //a minute will be enough i guess
                    beforeSend: function() {
                        $('.#md-house-pic-form').prepend('<div class="loading-anim">' +
                            '<div class="load-i"></div>' +
                            '<div class="load-i"></div>' +
                            '<div class="load-i"></div>' +
                            '<div class="load-i"></div>' +
                            '<div class="load-i"></div>' +
                            '</div>');
                    }
                }).done(function(dat) {
                    $('#md-house-pic-form').empty().append(dat + '<button onclick="modal.close()" class="modal-complete">&timesb;close window</button>');
                    console.log('ajax: picture upload request is successful');
                }).fail(function(_jXHR, err) {
                    $('#md-house-pic-form').empty().append('<p class="error">Uploading failed with reason: ' + err + '</p>' +
                        '<button onclick="modal.close()" class="modal-complete">&timesb;close window</button>');
                    console.error('Ajax: picture upload request failed with reason :' + err);
                }).always(function() {
                    console.log('Ajax: picture upload request is now complete');
                    $('div.loading-anim').remove();
                });

            });
            $('#md-house-pic-form').on('click', '.md-btn-no', function(e) {
                e.preventDefault();
                modal.close();
            });

        });
        //on clicking edit-button[house info]
        $('.house-edit').on('click', function(e) {
            e.preventDefault();
            console.log('clicked edit house and now editing house');
            //keep temp data incase of a cancel
            tempHouseObj = housesObj[$(this).attr('hseId')];
            //get data and prepare display
            let id = $(this).attr('hseId');
            let displayData = '<div class="display-data"> ' +
                '  <div class="prof-dat">' +
                '  <p>Address</p> <input type="text" value="' + housesObj[id].address + '" name="address" readonly="true"/> <button class="edit-info">Change</button>' +
                '  </div>' +
                '  <div class="prof-dat">' +
                '  <p>Rent ($)</p> <input type="text" value="' + housesObj[id].rent + '" name="rent" readonly="true"/> <button class="edit-info">Change</button>' +
                '  </div>' +
                '  <div class="prof-dat">' +
                '  <p>students per room</p> <input type="text" value="' + housesObj[id].accommodates + '" name="accommodates" readonly="true"/> <button class="edit-info">Change</button>' +
                '  </div>' +
                '  <div class="prof-dat">' +
                '  <p>Accommodates <em>( girls or boys or both )</em></p> <input type="text" value="' + housesObj[id].accomtype + '" name="accommtype" pattern="(both|boys|girls)" readonly="true"/> <button class="edit-info">Change</button>' +
                '  </div>' +
                '  <div class="prof-dat">' +
                '  <p>Location</p> <input type="text" value="' + housesObj[id].location + '" name="location" readonly="true"/> <button class="edit-info">Change</button>' +
                '  </div>' +
                '  <div class="prof-dat">' +
                '  <p>Offered </p> <input type="text" value="' + housesObj[id].offered + '" name="offered" readonly="true"/> <button class="edit-info">Change</button>' +
                '  </div>' +
                '  <div class="prof-dat">' +
                '  <p>Essentials</p> <input type="text" value="' + housesObj[id].essentials + '" name="essentials" readonly="true"/> <button class="edit-info">Change</button>' +
                '  </div>' +
                '  ' +
                '</div>' +
                ' <button class="modal-done" title="done with changing values">Done</button>';
            //add content to variable
            $modalContents.empty().append(displayData);
            //call modal var
            modal.open({ content: $modalContents });
            //add event listeners
            $('button.edit-info').on('click', function() {
                console.log('edit info button clicked');
                //take variables
                $editButton = $(this);
                $input = $editButton.prev('input');
                $parent = $input.parent();

                tempInputEditVal = $input.val();
                tempInputEditObj[$input.attr('name')] = tempInputEditVal;
                //add cancel and done button
                $editButton.fadeOut(10);
                $parent.append('<button class="edit-cancel">Cancel</button><button class="edit-done">Done</button>').fadeIn(); //add done button
                $('button.edit-cancel').css({ 'background-color': '#FF533D', 'color': '#f5f5f5', 'border': 'unset' });
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
                    inputVal = tempInputEditObj[$Input.attr('name')];
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

                    formDataHouseObj[inputName] = inputVal;

                    canceled = true;
                    if (canceled) {
                        $Done.remove();
                        $Cancel.remove();
                        canceled = false;
                    };

                });



            });
            //on clicking done button
            $('.modal-done').on('click', function(e) {
                e.preventDefault();
                console.log('clicked modal done');
                //check if any values changed
                let changedVals = (Object.values(formDataHouseObj).length);
                //if some where changed submit
                if (changedVals > 0) {
                    //submit via ajax
                    $.ajax({
                        type: 'POST',
                        url: 'php/landlord-user.php',
                        data: formDataHouseObj,
                        dataType: 'html',
                        timeout: 30000, // a 1/2 minute will be enough i guess
                        beforeSend: function() {
                            $('.display-data').append('<div class="loading-anim">' +
                                '<div class="load-i"></div>' +
                                '<div class="load-i"></div>' +
                                '<div class="load-i"></div>' +
                                '<div class="load-i"></div>' +
                                '<div class="load-i"></div>' +
                                '</div>');
                        }
                    }).done(function(responseData) {
                        console.log('Ajax: house data edit request is done and size is :' + (Object.values(formDataHouseObj)).length);
                        $('.display-data').empty().append(responseData);
                    }).fail(function(_jXHR, ErrStat) {
                        console.log('Ajax: data edit request <failed> :' + ErrStat);
                        $('.display-data').empty().append('<p class="error">Error: changing house information failed with reason :' + ErrStat + '</p>');

                    }).always(function() {
                        console.log('Ajax: data edit request is now <complete>');
                        $('div.loading-anim').remove();
                    });

                } else {
                    modal.close();
                }



            });




        });
        //on clicking delete-button[house info]
        $('.house-del').on('click', function(e) {
            e.preventDefault();
            console.log('clicked delete house and removing house');
            //get Vars
            var $houseDelButton = $(this);
            //generate content for modal window 
            let delHouseContent = '<div class="modal-del-house">' +
                '<p class="error">Are you sure you want to remove this house ?</p>' +
                '<div class="md-img"><img src="' + $houseDelButton.attr('hseimg') + '"/></div>' +
                '<address>' + housesObj[$houseDelButton.attr('hseid')].address + '</address>' +
                '  <div class="modal-confirm-btn">' +
                '    <button class="md-btn-yes">Yes</button><button class="md-btn-no">No</button>' +
                '  </div>' +
                '</div>';
            //add content to modal
            modal.open({ content: delHouseContent });
            //add event listeners
            $('.md-btn-yes').on('click', function(e) {
                e.preventDefault();
                console.log('modal yes button clicked');
                //prepare ajax data
                let delAjaxObj = {};
                delAjaxObj.houseId = $houseDelButton.attr('hseid');
                delAjaxObj.houseImgLocation = $houseDelButton.attr('hseimg');
                //send delete request via Ajax
                $.ajax({
                    url: 'php/landlord-user.php',
                    type: 'POST',
                    data: delAjaxObj,
                    dataType: 'html',
                    timeout: 30000, //a 1/2 minute will be enough i guess
                    beforeSend: function() {
                        $('.modal-del-house').append('<div class="loading-anim">' +
                            '<div class="load-i"></div>' +
                            '<div class="load-i"></div>' +
                            '<div class="load-i"></div>' +
                            '<div class="load-i"></div>' +
                            '<div class="load-i"></div>' +
                            '</div>');
                    }
                }).done(function(data) {
                    $('.modal-del-house').empty().append(data);
                    console.log('Ajax: house removal request is done');
                    console.log(delAjaxObj);
                }).fail(function(_jXHR, ERRst) {
                    $('.modal-del-house').empty().append('<p class="error">Error: Removing house failed with reason :' + ERRst + '</p>');
                    console.log('Ajax: Request failed with reason :' + ERRst);
                }).always(function() {
                    console.log('Ajax: House removal request is complete');
                    $('div.loading-anim').remove();
                })
            });
            $('.md-btn-no').on('click', function(e) {
                e.preventDefault();
                //close modal
                modal.close();
            })

        });
        //on clicking add new house-to-list
        $('.add-house').on('click', function(e) {
            e.preventDefault();
            console.log('clicked add house and now adding new house');
            //generate data for modal
            let newHouseModal = '<div class="house-add">' +
                '  <div class=" modal-house-details">' +
                ' ' +
                ' <form id="modal-form" action="php/landlord-user.php" method="POST" enctype="multipart/form-data">' +
                '  <div class="prof-dat">' +
                '  <p>Main Picture to use for the house</p> <input type="file" placeholder="house physical address" name="newHouseFile" accept="image/*" required/>' +
                '  </div>' +
                '  <div class="prof-dat">' +
                '  <p>Address</p> <input type="text" placeholder="house physical address"  minlength="8" maxlength="255" name="newHouseAddress" required/>' +
                '  </div>' +
                '  <div class="prof-dat">' +
                '  <p>Accommodation is for</p> ' +
                ' <select  name="newHouseAccomm_type" required>' +
                '   <option value="both"> -- </option>' +
                '   <option value="both" >Boys and Girls</option>' +
                '   <option value="boys" >Boys</option>' +
                '   <option value="girls" >Girls</option>' +
                ' </select>' +
                '  </div>' +
                '  <div class="prof-dat">' +
                '  <p>Accommodates how many students per room ?</p> <input type="text" size="3"  min="1" max="5"   name="newHouseAccommodates" title="numbers only" pattern="[0-9]*" required/>' +
                '  </div>' +
                '  <div class="prof-dat">' +
                '  <p>Rent to pe paid ($)</p> <input type="text" placeholder="rent amount"  min="0" pattern="[0-9]*" name="newHouseRent" required/>' +
                '  </div>' +
                '  <div class="prof-dat">' +
                '  <p>What should students bring ?</p> <textarea placeholder="e.g blankets, pots, toiletries etc"  minlength="8" maxlength="255" name="newHouseEssentials" required></textarea>' +
                '  </div>' +
                '  <div class="prof-dat">' +
                '  <p>What do you offer ?</p> <textarea placeholder="e.g wifi, breakfast, transportation, garage space , etc"  minlength="8" maxlength="255" name="newHouseOffers" required></textarea>' +
                '  </div>' +
                '  <div class="prof-dat">' +
                '  <p>Geographical Location</p> <input type="text" placeholder="name in full e.g Mount Pleasant, not Mt. Pleasant" minlength="8" maxlength="255" name="newHouseLocation" required/>' +
                '  </div>' +
                ' ' +
                ' <div class="modal-confirm-btn">' +
                '   <button class="md-btn-yes" type="submit">Add House</button> <button class="md-btn-no">Cancel</button>' +
                ' </div>' +
                ' </form>' +
                ' ' +
                '</div>' +
                '</div>';
            //call modal
            modal.open({ content: newHouseModal });
            //add event listeners
            let $addHouse = $('.modal-confirm-btn .md-btn-yes');
            let $cancelAddHouse = $('.modal-confirm-btn .md-btn-no');

            $addHouse.on('click', function(e) {
                e.preventDefault();
                console.log('clicked add house button and is of size: ' +
                    (Object.values($('#modal-form').serialize())).length);
                console.log($('#modal-form').serialize());
                //upload data to ajax
                $.ajax({
                    type: 'POST',
                    url: 'php/landlord-user.php',
                    data: $('#modal-form').serialize(),
                    dataType: 'html',
                    timeout: 30000, //a 1/2 minute will be enough i guess
                    beforeSend: function() {
                        $('#modal-form').prepend('<div class="loading-anim">' +
                            '<div class="load-i"></div>' +
                            '<div class="load-i"></div>' +
                            '<div class="load-i"></div>' +
                            '<div class="load-i"></div>' +
                            '<div class="load-i"></div>' +
                            '</div>');
                    }
                }).done(function(data) {
                    $('.modal-confirm-btn').fadeOut();
                    $('#modal-form').empty().append(data + '<button onclick="modal.close()" class="modal-complete">&timesb;close window</button>');
                    console.log('Ajax: add house request complete with success');
                }).fail(function(_jXHR, err) {
                    $('.modal-confirm-btn').fadeOut();
                    $('#modal-form').empty().append('<p class="error">Adding house failed with reason :' + err +
                        '</p><br/><button onclick="modal.close()" class="modal-complete">&timesb;close window</button>');
                    console.error('Ajax: add house request failed with reason :' + err);
                }).always(function() {
                    console.log('Ajax: add house request is now complete');
                    $('div.loading-anim').remove();
                });
            });
            $cancelAddHouse.on('click', function(e) {
                e.preventDefault();
                let reply = window.confirm('Are you sure you want to cancel ?');
                if (reply) {
                    modal.close();
                }
            });

        });

        $('header a#logout-btn').on('click', function(e) {
            e.preventDefault();
            logout = window.confirm(' Are you sure you want to logout ?');

            if (logout) {
                debug: console.log('yep...just do it');
                $.ajax({
                    type: "POST",
                    url: "php/landlord-user.php",
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













        //****************************************************      GENERIC  UNIFORMED STYLING ******************************************************************************************** */
        //input focus and blur

        $("input:not([readonly='true'])").on('focus', function() {
            //capture values 1st
            inputBorderColor = $(this).css('border-color');
            inputBgColor = $(this).css('background-color');
            inputFontColor = $(this).css('color');
            //change the border colour to :#ff533d
            $(this).css({ 'border-color': '#AB987A', 'border-style': 'dashed', 'caret-color': '#ff533d' });
            //change the bg colour to #0f1626:
            $(this).css('background-color', '#0f1626');
            //change the bg to : #f5f5f5
            $(this).css('color', '#f5f5f5');

        });
        $("input:not([readonly='true'])").on('blur', function() {
            //check if changed

            //change back the border colour 
            $(this).css({ 'border-color': inputBorderColor, 'border-style': 'solid' });
            //change back the bg colour 
            $(this).css('background-color', inputBgColor);
            //change back the font
            $(this).css('color', inputFontColor);

        });



    }).fail(function(_jXHR, errorTxt) {
        $('.user-details').html('<p class="error">Requesting data failed with reason : ' + errorTxt + '</p>');
        console.log('Ajax: login request failed due to :' + errorTxt);
    }).always(function() {
        console.log('Ajax: login request is now complete');
    });
    logging = true;





});