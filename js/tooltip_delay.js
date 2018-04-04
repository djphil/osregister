/* Add delay to bootstrap tooltip */

$(document).ready(function(){
    $('[data-toggle="tooltip"]').tooltip({
        animation: true,
        delay: {show: 100, hide: 100}
    }); 
    $('button').on('mousedown', function(){
        $('[data-toggle="tooltip"]').tooltip('hide');
    });
    $('[data-toggle="tooltip"]').on('mouseleave', function(){
        $('[data-toggle="tooltip"]').tooltip('hide');
    });
});
