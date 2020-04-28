/**
 * @file
 * Global utilities.
 *
 */
(function($, Drupal) {

  'use strict';

  Drupal.behaviors.product_datatable = {
    attach: function(context, settings) {
      var dataSelect = [];
      $('#product-reference-table tbody tr').prepend('<td></td>');
      $('#product-reference-table thead tr').prepend('<th></th>');
      $('#product-reference-table').once("product-datatable").DataTable( {
        "lengthChange": false,
        "autoWidth": false,
        "retrieve":   true,
        "info": false,
        "ordering": false,
        "responsive": true,
        "sPaginationType":"simple_numbers",
        "iDisplayLength": 4,
        columnDefs: [
          { responsivePriority: 10001, targets: -3 },
          { responsivePriority: 10001, targets: -2 },
          { responsivePriority: 10002, targets: -1 }
        ],
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
            if (colname !== Drupal.t('Select', {}, {context: "product-reference-table"})) {
              var select = $('<select><option value="">' + colname + '</option></select>')
                .appendTo( $(column.header()).empty() )
                .on( 'change', function () {
                  var val = $.fn.dataTable.util.escapeRegex(
                    $(this).val()
                  );

                  column
                    .search( val ? val : '', false, false )
                    .draw();
                } );
              column.data().unique().sort().each( function ( d, j ) {
                var optionTag = $.parseHTML(d);
                var checkDuplicate = $.inArray(optionTag[0].text, dataSelect);
                if (optionTag.length && checkDuplicate === -1) {
                  var optionValue = optionTag[0].text;
                  select.append( '<option value="'+ optionValue +'">'+ optionValue +'</option>' );
                  dataSelect.push(optionTag[0].text);
                }
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
