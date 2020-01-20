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

      $('.we-mega-menu-ul').on('show.bs.dropdown', function () {
        $('.nav-wrapper').addClass('open-menu');
        $('html, body').css({
          overflow: 'hidden'
        });
      });
      $('.we-mega-menu-ul').on('hide.bs.dropdown', function () {
        $('.nav-wrapper').removeClass('open-menu');
        $('html, body').css({
          overflow: 'auto'
        });
      });

      $('.we-mega-menu-ul .product .subul .level-1').on("mouseover", function () {
        var current = this;
        var first = $('.we-mega-menu-ul .product .subul .level-1:first');
        if (current != first[0]) {
          $(first).removeClass('show');
        }
        else {
          $(first).addClass('show');
        }
      });

      $('.we-mega-menu-ul .product .subul .level-1').on("mouseout", function () {
        var current = this;
        var first = $('.we-mega-menu-ul .product .subul .level-1:first');
        if (current != first[0]) {
          $(first).addClass('show');
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
      });
    }
  };
})(jQuery, Drupal);
