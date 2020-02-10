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

      $('.we-mega-menu-ul .product .subul .level-2').on("mouseover", function () {
        var current = this;
        var first = $('.we-mega-menu-ul .product .subul .level-2:first');
        if (current != first[0]) {
          $(first).removeClass('show');
        }
        else {
          $(first).addClass('show');
        }
      });

      $('.we-mega-menu-ul .product .subul .level-2').on("mouseout", function () {
        var current = this;
        var first = $('.we-mega-menu-ul .product .subul .level-2:first');
        if (current != first[0]) {
          $(first).addClass('show');
        }
      });

      // SLIDE Dropdown lvl0
      $("li[data-level='0']", context).once('socomecMobileMenulv0').each(function () {
        $(this).click(function (e) {
          $(this).toggleClass('active');
          var dropdown = $(this).find('.we-mega-menu-submenu.dropdown-menu').first();
          e.preventDefault();
          e.stopPropagation();
          $(dropdown).slideToggle("400");
        });
      });
      // CLOSE OTHER Dropdown lvl0
      $("li[data-level='0']", context).once('socomecMobileMenuClosinglv0').each(function () {
        $(this).click(function () {
          var dropdown = $(this).find('.we-mega-menu-submenu.dropdown-menu');
          $(".we-mega-menu-submenu.dropdown-menu").not(dropdown).slideUp("400");
        });
      });

      // SLIDE OPENING Dropdown lvl1
      $("[data-level='1'] .mobile-dropdown-trigger", context).once('socomecMobileMenu').each(function () {
        $(this).click(function (e) {
          $(this).toggleClass('active');
          var dropdown = $(this).next('.we-mega-menu-submenu.dropdown-menu');
          e.preventDefault();
          e.stopPropagation();
          $(dropdown).slideToggle("400");
        });
      });
      // CLOSE OTHER Dropdown lvl1
      $("[data-level='1'] .mobile-dropdown-trigger", context).once('socomecMobileMenuClosinglv1').each(function () {
        $(this).click(function () {
          var dropdown = $(this).next('.we-mega-menu-submenu.dropdown-menu');
          console.log('toto1');
          $(".level-1 > .we-mega-menu-submenu.dropdown-menu").not(dropdown).slideUp("400");
          console.log('toto2');
        });
      });

      // OVERLAY + Kill open dropdown
      $('.navbar-toggler', context).once('socomecOverlayResponsive').each(function() {
        var allDropdown = $('.we-mega-menu-submenu.dropdown-menu');
        $(this).click(function (){
          $('html .overlay').toggleClass('active');
          allDropdown.hide();
        })
      })
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

  /**
   * Open mobile share menu
   */
  Drupal.behaviors.socomec_add_to_bookmarks = {
    attach: function (context, settings) {
      $('.opening-share').click(function() {
          $('.share-menu').toggleClass('open');
      })
    }
  };

  /**
   * Smooth scroll for anchor
   */
  Drupal.behaviors.socomec_smooth_anchor_scrolling = {
    attach: function (context, settings) {
      $(function() {
        $('a[href*="#"]:not([href="#"])').click(function() {
          var offset = -200;
            var target = $(this.hash);
            target = target.length ? target : $('[name=' + this.hash.slice(1) +']');
            if (target.length) {
              $('html, body').animate({
                scrollTop: target.offset().top + offset
              }, 1000);
              return false;
            }
        });
      });
    }
  };

})(jQuery, Drupal);
