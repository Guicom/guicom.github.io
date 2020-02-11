/**
 * @file
 * Global utilities.
 *
 */
(function($, Drupal) {

  'use strict';

  Drupal.behaviors.product_datatable = {
    attach: function(context, settings) {

      $('#product-reference-table').once("product-datatable").DataTable( {
        "lengthChange": false,
        "autoWidth": true,
        "info": false,
        "ordering": false,
        "responsive": true,
        "sPaginationType":"simple_numbers",
        "iDisplayLength": 4,
        language: {
          search: "_INPUT_",
          searchPlaceholder: Drupal.t("Search, Filter..."),
          "decimal": ",",
          "thousands": "."
        },
        initComplete: function () {
          this.api().columns().every( function () {
            var column = this;
            var colheader = this.header();
            var colname = $(colheader).text().trim();
            if (colname != Drupal.t('Select', {}, {context: "product-reference-table"})) {
              var select = $('<select><option value="">' + colname + '</option></select>')
                .appendTo( $(column.header()).empty() )
                .selectpicker({virtualScroll: false})
                .on( 'change', function () {
                  var val = $.fn.dataTable.util.escapeRegex(
                    $(this).val()
                  );

                  column
                    .search( val ? '^'+val+'$' : '', true, false )
                    .draw();
                } );
              column.data().unique().sort().each( function ( d, j ) {
                var StrippedString = d.replace(/(<([^>]+)>)/ig,"");
                select.append( '<option value="'+ StrippedString +'">'+ StrippedString +'</option>' )
              });
            }
          });
        },
        preDrawCallback: function (settings) {
          var api = new $.fn.dataTable.Api(settings);
          var pagination = $(this)
            .closest('.dataTables_wrapper')
            .find('.dataTables_paginate');
          pagination.toggle(api.page.info().pages > 1);
        }
      });

      $('.add-to-cart').each(function () {
        $(this).click(function () {
          $(this).toggleClass('active');
        })
      });
    }
  };
})(jQuery, Drupal);
