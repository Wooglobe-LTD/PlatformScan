/**
 * Created by Abdul Rehman Aziz on 3/28/2018.
 */
$( document ).ready(function(){
    /*setTimeout(function(){
        window.location = base_url+'dashboard'; }, 30000);*/

    $('#question4').on({
        keypress: function(e) {
            var k;
            document.all ? k = e.keyCode : k = e.which;

            return ((k >= 97 && k <= 122) || (k >= 48 && k <= 57) || k == 8 || k == 0 || k == 45 || k==32 || 190);
        },
        paste: function(e) {
            var stopPasteChild = function() {
                this.value = this.value.replace(/[\s]/gi, ' ').toLowerCase();
                this.value = this.value.replace(/[^a-z0-9\-. ]/gi, '');
            };
            setTimeout(stopPasteChild.bind(this), 1);
        }
    });
    });