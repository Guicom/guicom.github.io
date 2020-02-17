/**
 * @file
 * wishlist_datatable utilities.
 *
 */
(function($, Drupal) {

  'use strict';

  Drupal.behaviors.wishlistActions = {
    attach: function(context, settings) {
      checkBtnState()
      $('#edit-select-all-select').click(function () {
        if( $('#edit-select-all-select').is(':checked') ){
          $('.wishlist-action-item input').prop('checked', true);
        }
        else{
          $('.wishlist-action-item input').prop('checked', false);
        }
        $('#edit-select-all-select').parent().removeClass('checkbox-indeterminate');
      });

      $('.wishlist-action-item input').click(function () {
        checkBtnState()
      });

      function checkBtnState() {
        var checked = 0;
        var unchecked = 0;
        $('.wishlist-action-item input').each(function( index ) {
          if( $(this).is(':checked') ){
            checked = 1;
          }
          else{
            unchecked = 1;
          }
        });

        if (checked == 1 && unchecked == 1) {
          $('#edit-select-all-select').parent().addClass('checkbox-indeterminate');
        }
        else{
          $('#edit-select-all-select').parent().removeClass('checkbox-indeterminate');
          if (checked == 1 && unchecked == 0) {
            $('#edit-select-all-select').prop('checked', true);
          }
          else{
            $('#edit-select-all-select').prop('checked', false);
          }
        }
      }

      $("#wishlist_form_wrapper input.form-number").once("wishlist_form_number").each(function( index ) {
        $(this).after('<div class="quantity-nav"><div class="quantity-button quantity-up"></div><div class="quantity-button quantity-down"></div></div>');
        var spinner = $(this).parent(),
            input = spinner.find('input[type="number"]'),
            btnUp = spinner.find('.quantity-up'),
            btnDown = spinner.find('.quantity-down'),
            min = input.attr('min'),
            max = input.attr('max');

        btnUp.click(function() {
          var oldValue = parseFloat(input.val());
          var newVal = oldValue;
          if (oldValue >= max) {
            newVal = oldValue;
          } else {
            newVal = oldValue + 1;
          }
          spinner.find("input").val(newVal);
          spinner.find("input").trigger("change");
        });

        btnDown.click(function() {
          var oldValue = parseFloat(input.val());
          var newVal = oldValue;
          if (oldValue <= min) {
            newVal = oldValue;
          } else {
            newVal = oldValue - 1;
          }
          spinner.find("input").val(newVal);
          spinner.find("input").trigger("change");
        });
      });

    }
  };

})(jQuery, Drupal);
