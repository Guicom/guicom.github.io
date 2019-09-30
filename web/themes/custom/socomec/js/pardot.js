(function(){
  $(document).ready(function() {
    $('input').each(function () {
      $(this).on('blur input', function() {
        if ($(this).val()) {
          $(this).parent().addClass('filled');
        }
        else {
          $(this).parent().removeClass('filled');
        }
      });
    });
  });
})();

