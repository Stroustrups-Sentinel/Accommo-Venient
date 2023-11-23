/*
    -- #filename       : jquery-event-styling.js
    -- #authors-quote  : This Project Belongs to the CTHSC students Fortunate .M, Phillippa .M and Mc Samuel .S *2020-February-Semester
    -- ********************************************************************************************************************************* 
    --- this is a JQUERY script
    --- it was built to be used with  JQuery v3.4.1
    --- to be exported to other files 

*/
export var  inputBorderColor = '';
export var  inputBgColor = '';
export var  inputFontColor = '';
export function inputListener(){
    /*            ----------- INPUTs -----------  */
        //add event listerner for focus and blur
        $('#password').on('keyup',function(){
            //add values to var
            sessionStorage.varp1 = $(this).val();
            checkVals();

        });

        $('#password-c').on('keyup',function(){
            //add values to var
            sessionStorage.varp2 = $(this).val();
            checkVals();
        });
        function checkVals(){
              var p1 = sessionStorage.varp1;
              var p2 = sessionStorage.varp2;
              const unMatched = '<h5 class="warn" id="un-matched">Passwords do not match..</h5>';

              if(p1.length > 5 && p2.length > 5 ){
                    if(p1 != p2){
                      $(this).append(unMatched).$(selector).fadeIn(300) || $('#un-matched').fadeIn(300).show(200); 
                    }else if(p1 == p2){
                      $(this).remove(unMatched).$(selector).fadeOut(300) || $('#un-matched').fadeOut(300).hide(200);
                    }
            }

        }


}
