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
        pageLength: 50,
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
      
      var nbPage = 0;
      $("#wishlist_form_wrapper .dataTables_wrapper .pagination > li").each(function( index ) {
        nbPage = nbPage + 1;
      });
      if (nbPage < 4) {
        $("#wishlist_form_wrapper .dataTables_wrapper .pagination").hide();
      }
    }
  };

})(jQuery, Drupal);
