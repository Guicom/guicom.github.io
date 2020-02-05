/**
 * @file
 * wishlist_datatable utilities.
 *
 */
(function($, Drupal) {

  'use strict';

  Drupal.behaviors.wishlistDatatable = {
    attach: function(context, settings) {
      var placeholder = "";
      if (typeof settings.wishlistDatatable != 'undefined') {
        placeholder = settings.wishlistDatatable.searchPlaceholder;
      }
      var table = $( "#wishlist_form_wrapper table", context );
      table.once('wishlistDatatable').DataTable({
        retrieve:   true,
        responsive: true,
        autoWidth: false,
        paging:     false,
        ordering:   true,
        info:       false,
        searching:  true,
        language: {
          searchPlaceholder: placeholder,
          search: ""
        },
        dom: '<"col-sm-12 col-md-6"f><"col-sm-12 col-md-6"l>tip',
        columnDefs: [
          { targets: 'no-sort', orderable: false }
        ]
      });
    }
  };

})(jQuery, Drupal);
