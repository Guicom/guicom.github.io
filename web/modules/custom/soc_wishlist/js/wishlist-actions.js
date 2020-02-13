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
        }
      }
    }
  };

})(jQuery, Drupal);
