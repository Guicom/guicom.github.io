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
      $(this).focus(function() {
        $(this).parent().addClass('focused');
      });
      $(this).focus(function(){
        $(this).parent().addClass('focused');
      }).blur(function(){
        $(this).parent().removeClass('focused');
      });
    });

    $('.pd-checkbox').each(function () {
      var input = $(this).find('input');
      $(this).prepend(input);
    });

    if($('select').val() && $('select').val() != 866572){
      $(this).parent().addClass('filled');
    } else {
      $(this).parent().removeClass('filled');
    }

  });
  $(window).on('load', function() {
    $('body').show();
  });
})();

