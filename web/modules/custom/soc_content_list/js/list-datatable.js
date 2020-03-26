/**
 * @file
 * wishlist_datatable utilities.
 *
 */
(function($, Drupal) {

  'use strict';

  Drupal.behaviors.socContentListDatatable = {
    attach: function(context, settings) {
      var placeholder = "";
      if (typeof settings.socListDatatable != 'undefined') {
        placeholder = settings.socListDatatable.searchPlaceholder;
      }
      var table = $( ".soc_my_list_form_wrapper table", context );

      table.once('socContentListDatatable').DataTable({
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
      $(".navbar-soc-my-list-top").show();
      var nbPage = 0;
      $(".soc_my_list_form_wrapper .dataTables_wrapper .pagination > li").each(function( index ) {
        nbPage = nbPage + 1;
      });
      if (nbPage < 4) {
        $(".soc_my_list_form_wrapper .dataTables_wrapper .pagination").hide();
      }
    }
  };

})(jQuery, Drupal);
