(function ($) { //an IIFE so safely alias jQuery to $
    $('#addMediaLink').bind( "click", function() {
        alert( "User clicked on 'foo.'" );
    });

    $.Player = function (media) { //renamed arg for readability
        this.mediaAddLink = document.getElementById('addMediaLink');
        //stores the passed media as a property of the created instance.
        //This way we can access it later
        this.media = (media instanceof $) ? media : $(media);
        //instanceof is an extremely simple method to handle passed jQuery objects,
        //DOM elements and selector strings.
        //This one doesn't check if the passed element is valid
        //nor if a passed selector string matches any elements.
    };

    //assigning an object literal to the prototype is a shorter syntax
    //than assigning one property at a time
    $.Player.prototype = {
        init: function (mediaList) {

            $('#addMediaLink').bind( "click", function() {
                alert( "User clicked on 'foo.'" );
            });

            console.log("sf");
            //`this` references the instance object inside of an instace's method,
            //however `this` is set to reference a DOM element inside jQuery event
            //handler functions' scope. So we take advantage of JS's lexical scope
            //and assign the `this` reference to another variable that we can access
            //inside the jQuery handlers
            var mediaList = this;


            this.media.each(function (e) {
                console.log(e);
            });
            //I'm using `document` instead of `this` so it will catch arrow keys
            //on the whole document and not just when the element is focused.
            //Also, Firefox doesn't fire the keypress event for non-printable
            //characters so we use a keydown handler
            $(document).keydown(function (e) {
                var key = e.which;
                if (key == 39) {
                    that.moveRight();
                } else if (key == 37) {
                    that.moveLeft();
                }
            });

            this.media.css({
                //either absolute or relative position is necessary 
                //for the `left` property to have effect
                position: 'absolute',
                left: $.Player.defaultOptions.playerX
            });
        },
        //renamed your method to start with lowercase, convention is to use
        //Capitalized names for instanceables only
        moveRight: function () {
            this.media.css("left", '+=' + 10);
        },
        moveLeft: function () {
            this.media.css("left", '-=' + 10);
        }
    };


    $.Player.defaultOptions = {
        playerX: 0,
        playerY: 0
    };

}(jQuery));
