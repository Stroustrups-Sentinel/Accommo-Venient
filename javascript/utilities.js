/*
    -- #filename       : utilities.js
    -- #authors-quote  : This Project Belongs to the CTHSC students Fortunate .M, Phillippa .M and Mc Samuel .S *2020-February-Semester
    -- ********************************************************************************************************************************* 
    --- this is supposed to be a vanilla javascript script although it contains some Jquery
    --- it was built to be used with  JQuery v3.4.1
    --- 

*/

//accordion 
function accordion(accordionElement) {
    //vars
    let $activeElem = accordionElement.active;
    let $currentElem = accordionElement.current;


    //check if already opened
    if ($activeElem != $currentElem) {
        if ($activeElem != null) {
            //remove as active
            $activeElem.next('section').removeClass('active-section');
            $activeElem.removeClass('active-p-tab');
        }
        //set as active
        $currentElem.next('section').addClass('active-section');
        $currentElem.addClass('active-p-tab');

        return $currentElem;
    } else {
        return $activeElem;
    }
}


//tabs
function tabular(tabElementsObj) {

    //get vars
    let $liveTab = tabElementsObj.active;
    let $currentTab = tabElementsObj.current;
    // get anchor elements
    let $currentTabAnchor = $currentTab.children('a');
    let $liveTabAnchor = $liveTab.children('a');
    //if not opened
    if ($currentTabAnchor.attr('href') != $liveTabAnchor.attr('href')) {
        //debug: console.log('tab ' + $currentTabAnchor.attr('href') + ' is not opened');
        //hide-currently opened
        $($liveTabAnchor.attr('href')).removeClass('active');
        //remove or hide-class 
        $liveTab.removeClass('active-tab');
        //add class to show content
        $($currentTabAnchor.attr('href')).addClass('active').slideDown();
        //show tab as active 
        $currentTab.addClass('active-tab');

        //return new active tab
        return $currentTab;
    }

    //debug: console.log('tab :' + $currentTabAnchor.attr('href') + ' already opened');
    return $liveTab;
}
//modals
var modal = (function() {
        var $window = $(window);
        var $modalBg = $('<div class="modal-bg "/>');
        var $modal = $('<div class="modal-outer " />');
        var $content = $('<div class="modal-inner "/>');
        var $close = $('<button class="modal-close" title="close this window">X</button>');
        var $document = $(document);
        //append info to modal var
        $modal.append($close, $content);
        //append modal to modal bg
        $modalBg.append($modal);
        //on clicking close
        $close.on('click', function(e) {
            e.preventDefault();
            modal.close();
        })

        return {
            open: function(modalData) {
                //empty modal content
                $content.empty().append(modalData.content);
                //size modal
                $modal.css({ width: modalData.width || 'auto', height: modalData.height || 'auto' }).appendTo('body');
                $modalBg.css({ width: '100%', height: '100%' }).appendTo('body');
                //center and show modal
                modal.center();
                //recenter incase of resize 
                $(window).on('resize', modal.center);
                //recenter incase of scroll
                $(window).on('scroll', modal.center);
            },
            center: function() {
                //get height and width to position on center
                var top = Math.max($window.height() - $modal.outerHeight(), 0) / 2;
                var topBg = 0; //initial pos.
                var left = Math.max($window.width() - $modal.outerWidth(), 0) / 2;
                var leftBg = 0; //initial pos.
                //set the center position
                $modal.css({ top: top + $window.scrollTop(), left: left + $window.scrollLeft() });
                //set the values for the modal bg
                $modalBg.css({ top: topBg, left: leftBg, height: $document.outerHeight() });

            },
            close: function() {
                //clear modal content
                $content.empty();
                //detach modal
                $modal.detach();
                $modalBg.detach(); //detach modal bg
                //clear event listeners
                $(window).off('resize', modal.center);
            }

        };
    }
    ());
//sliders
//gallery