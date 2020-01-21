/**
 * @file
 * Global utilities.
 *
 */
(function($, Drupal) {

  'use strict';

  Drupal.behaviors.bootstrap_barrio_subtheme = {
    attach: function(context, settings) {
      $('[data-toggle="tooltip"]').tooltip();
      var position = $(window).scrollTop();
      $(window).scroll(function () {
        if ($(this).scrollTop() > 50) {
          $('body').addClass("scrolled");
        }
        else {
          $('body').removeClass("scrolled");
        }
        var scroll = $(window).scrollTop();
        if (scroll > position) {
          $('body').addClass("scrolldown");
          $('body').removeClass("scrollup");
        } else {
          $('body').addClass("scrollup");
          $('body').removeClass("scrolldown");
        }
        position = scroll;
      });

      var iframes = iFrameResize({
        log: false,
        onResized: function(messageData) {
          $('#iframe-container').height(messageData.height + 'px');
        },
      }, '#pardot-iframe');
    }
  };

  /**
   * Datatables Settings
   * @see: https://www.datatables.net
   */
  Drupal.behaviors.socomec_navigation = {
    attach: function(context, settings) {
      $(document).scroll(function () {
          var position = $(document).scrollTop();
          if (position > 1) {
            $('.nav-wrapper').addClass('scrolled');
          }
          else {
            $('.nav-wrapper').removeClass('scrolled');
          }
      });
    }
  };

  /**
   * Datatables Settings
   * @see: https://www.datatables.net
   */
  Drupal.behaviors.socomec_datatables = {
    attach: function(context, settings) {
      $( ".field table" ).each(function() {
        if($( this ).children( "thead").length > 0){
          if(!$( this ).hasClass('dataTable')){
            $(this).DataTable({
              retrieve:   true,
              responsive: true,
              paging:     false,
              ordering:   false,
              info:       false,
              searching:  false
            });
          }
        }
      });
    }
  };

  /**
   * Eu_cookie_compliance Settings
   */
  Drupal.behaviors.socomec_eu_cookie_compliance = {
    attach: function(context, settings) {
      $(document).on('eu_cookie_compliance_popup_open', '#sliding-popup', function() {
        $(".customize-button", context).click(function() {
          if ($("#eu-cookie-compliance-categories").hasClass("d-none")) {
            $("#custum-popup-header").addClass("d-none");
            $(".customize-button").addClass("d-none");
            $("#eu-cookie-compliance-categories").removeClass("d-none").addClass("d-bloc");
          }
          else{
            $("#eu-cookie-compliance-categories").removeClass("d-bloc").addClass("d-none");
          }
        });

        $("#eu-cookie-compliance-categories input:checkbox", context).each(function() {
          if($(this).prop('checked')){
            $(this).next("label").find('.toggle-activated').removeClass("d-none");
            if($(this).prop('disabled')){
              $(this).next("label").find('.toggle-activated').addClass("disabled");
            }
          }
          else{
            $(this).next("label").find('.toggle-normal').removeClass("d-none");
          }
        });

        $("#eu-cookie-compliance-categories input:checkbox", context).click(function() {
          if(!$(this).prop('disabled')){
            if($(this).prop('checked')){
              $(this).next("label").find('.toggle-activated').removeClass("d-none");
              $(this).next("label").find('.toggle-normal').addClass("d-none");
            }
            else{
              $(this).next("label").find('.toggle-activated').addClass("d-none");
              $(this).next("label").find('.toggle-normal').removeClass("d-none");
            }
          }
        });

      });
    }
  };


  /**
   * Add to bookmarks button
   */
  Drupal.behaviors.socomec_add_to_bookmarks = {
    attach: function (context, settings) {
      $('.add-to-bookmarks').each(function () {
        $(this).off('click').on('click', function (e) {
          e.preventDefault();
          $(this).toggleClass('active');
        })
      });
    }
  };

})(jQuery, Drupal);
