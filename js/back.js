$(document).ready(function()
{
   $(function() {
       $("#datepicker").datepicker({
           dateFormat: 'mm-yy-dd'
       }).val();
   });

    tinymce.init({
        selector: '#newssection'
    });
});