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
        paging:     true,
        ordering:   true,
        order: [],
        info:       false,
        searching:  true,
        pageLength: 20,
        lengthChange: false,
        language: {
          searchPlaceholder: placeholder,
          search: ""
        },
        dom: '<"row" <"custom-datatable-header-left col-sm-12 col-md-6"f><"custom-datatable-header-right col-sm-12 col-md-6"l>>tip',
        columnDefs: [
          { targets: 'no-sort', orderable: false }
        ]
      });
    }
  };

})(jQuery, Drupal);
