/*
    -- #filename       : jquery-about.js
    -- #authors-quote  : This Project Belongs to the CTHSC students Fortunate .M, Phillippa .M and Mc Samuel .S *2020-February-Semester
    -- ********************************************************************************************************************************* 
    --- it was built to be used with  JQuery v3.4.1
    --- 

*/

$(document).ready(function() {

    //take vars
    let elemId = "#story"
    let $storyElement = $(elemId);
    let $tabListAnchor = $('.about-tabs li a[href="' + elemId + '"]');
    let $tabList = $tabListAnchor.parent('li');
    var $activeTab = $tabList;

    var $accordionActive = null;

    //make sure an element is displaying
    $storyElement.addClass('active');
    $tabList.addClass('active-tab');

    //add event listener
    //---tabs
    $('.about-tabs li a').on('click', function(e) {
        e.preventDefault();
        let $clickedTab = $(this).parent('li');

        //get vars
        let tabVars = {
                active: $activeTab,
                current: $clickedTab,
            }
            //change tabs
        $activeTab = tabular(tabVars);
    });
    //--accordion
    $('#contact-us li>p').on('click', function(e) {
        console.log('clicked');
        //get vars
        let accordionObj = {
            active: $accordionActive,
            current: $(this)
        };

        $accordionActive = accordion(accordionObj);
    });
});