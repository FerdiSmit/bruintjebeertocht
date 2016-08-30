$(document).ready(function()
{
   $(function() {
       $("#datepicker").datepicker();
   });

    $('#slider').slick({
        slidesToShow: 1,
        slidesToScroll: 1,
        autoplay: true,
        autoplaySpeed: 5000
    });

    tinymce.init({
        selector: '#newssection'
    });
});