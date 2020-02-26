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

      if ($(window).width() < 992) {
        $('.navbar-toggler').click(function () {
          $('body').toggleClass('fixed')
        });
        // SLIDE Dropdown lvl0
        $("li[data-level='0']", context).once('socomecMobileMenulv0').each(function () {
          $(this).click(function (e) {
            $(this).toggleClass('active');
            var dropdown = $(this).find('.we-mega-menu-submenu.dropdown-menu').first();
            e.preventDefault();
            e.stopPropagation();
            $(dropdown).slideToggle("400");
            console.log('LIDE Dropdown lvl0');
          });
        });
        // CLOSE OTHER Dropdown lvl0
        $("li[data-level='0']", context).once('socomecMobileMenuClosinglv0').each(function () {
          $(this).click(function () {
            var dropdown = $(this).find('.we-mega-menu-submenu.dropdown-menu');
            $(".we-mega-menu-submenu.dropdown-menu").not(dropdown).slideUp("400");
            console.log('CLOSE OTHER Dropdown lvl0');
          });
        });

        // SLIDE OPENING Dropdown lvl1
        $("[data-level='1'] > .mobile-dropdown-trigger", context).once('socomecMobileMenu').each(function () {
          $(this).click(function (e) {
            $(this).toggleClass('active');
            var dropdown = $(this).next('.we-mega-menu-submenu.dropdown-menu');
            e.preventDefault();
            e.stopPropagation();
            $(dropdown).slideToggle("400");
            console.log('SLIDE OPENING Dropdown lvl1');
          });
        });
        // CLOSE OTHER Dropdown lvl1
        $("[data-level='1'] > .mobile-dropdown-trigger", context).once('socomecMobileMenuClosinglv1').each(function () {
          $(this).click(function () {
            var dropdown = $(this).next('.we-mega-menu-submenu.dropdown-menu');
            $(".level-1 > .we-mega-menu-submenu.dropdown-menu").not(dropdown).slideUp("400");
            console.log('CLOSE OTHER Dropdown lvl1');
          });
        });

        // SLIDE OPENING Dropdown lvl2
        $("[data-level='2'] > .mobile-dropdown-trigger", context).once('socomecMobileMenulvl2').each(function () {
          $(this).click(function (e) {
            $(this).toggleClass('active');
            var dropdown = $(this).next('.we-mega-menu-submenu.dropdown-menu');
            e.preventDefault();
            e.stopPropagation();
            $(dropdown).slideToggle("400");
            console.log('SLIDE OPENING Dropdown lvl2');
          });
        });
        // CLOSE OTHER Dropdown lvl2
        $("[data-level='2'] > .mobile-dropdown-trigger", context).once('socomecMobileMenuClosinglv2').each(function () {
          $(this).click(function () {
            var dropdown = $(this).next('.we-mega-menu-submenu.dropdown-menu');
            $(".level-2 > .we-mega-menu-submenu.dropdown-menu").not(dropdown).slideUp("400");
            console.log('CLOSE OTHER Dropdown lvl2');
          });
        });

        // OVERLAY + Kill open dropdown
        $('.navbar-toggler', context).once('socomecOverlayResponsive').each(function() {
          var allDropdown = $('.we-mega-menu-submenu.dropdown-menu');
          $(this).click(function (){
            $('html .£').toggleClass('active');
            allDropdown.hide();
          })
        });
      }
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

        $("#eu-cookie-compliance-categories  input:checkbox", context).each(function() {
          $(this).next("label").find('.toggle-normal').removeClass("d-none");
        });

        if (drupalSettings.eu_cookie_compliance.method === 'categories') {
          var status = Drupal.eu_cookie_compliance.getCurrentStatus();
          var categories_checked = [];
          var all_categories = drupalSettings.eu_cookie_compliance.cookie_categories;
          var categories_count = all_categories.length;
          if (status === null) {
            if (drupalSettings.eu_cookie_compliance.select_all_categories_by_default) {
              categories_checked = drupalSettings.eu_cookie_compliance.cookie_categories;
            }
          }
          else {
            categories_checked = Drupal.eu_cookie_compliance.getAcceptedCategories();
          }

          for (var i = 0 ; i < categories_count ; i++) {
            if ($.inArray(all_categories[i], categories_checked) > -1) {
              $("#eu-cookie-compliance-categories input:checkbox", context).each(function() {
                 if($(this).val() === all_categories[i]){
                   $(this).next("label").find('.toggle-normal').addClass("d-none");
                   $(this).next("label").find('.toggle-activated').removeClass("d-none");
                   if($(this).prop('disabled')){
                     $(this).next("label").find('.toggle-activated').addClass("disabled");
                   }
                 }
              });
            }
          }
        }

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
        $('a[href^="#"]:not([href="#"])').click(function() {
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

  /**
   * Facets bootstrap_select
   */
  Drupal.behaviors.socomec_facets_bootstrap_select = {
    attach: function (context, settings) {
      $('select').each(function () {
        $(this).selectpicker({
          virtualScroll: false,
        });
        if ($(this).parents('.facet-inline-position-title').length) {
          var width = $(this).parents("div.block-facet--dropdown").find("div.facet-title").first().width();
          $(this).siblings('.dropdown-toggle.btn-light').css('padding-left', width + 20 + 'px');
        }
        else{
          var height = $(this).parents("div.block-facet--dropdown").find("div.facet-title").first().height();
          $(this).siblings('.dropdown-toggle.btn-light').css('padding-top', height + 7 + 'px');
        }
        $(this).parents(".bootstrap-select").find("div.dropdown-menu").first().mCustomScrollbar({
          theme:"minimal-dark",
          mouseWheel:{ preventDefault:true }
        });
      });

      $('.bootstrap-select').each(function () {
        $(this).find("div.dropdown-menu").first().mCustomScrollbar({
          theme:"minimal-dark",
          mouseWheel:{ preventDefault:true }
        });
      });
    }
  };

  /**
   * Facets bootstrap_list_select
   */
  Drupal.behaviors.socomec_facets_list_select = {
    attach: function (context, settings) {
      $(".facet-list-display-select").each(function () {
        var element = $(this);
        var reset = element.find(".facets-reset > a");
        if(reset.length > 0){
          var facetTitle = $(this).find('.facet-title');
          if(facetTitle.find(".filter-option").length < 1){
            facetTitle.append('<div class="filter-option"></div>').find(".filter-option").append(reset.text());
            facetTitle.wrap('<div class="wrapper-facet-title"></div>')
          }
        }
        element.find(".wrapper-facet-title").once().on("click", function (e) {
          $(this).toggleClass("active");
          element.find("ul.item-list__links").toggleClass("active");
        });
        var active = element.find("li:not(.facets-reset) .is-active");
        if(active.length > 0){
          active.parents('li').addClass('active');
          element.find(".wrapper-facet-title").addClass("active");
          element.find("ul.item-list__links").addClass("active");
        }
        $(element).find(".facets-widget-links").mCustomScrollbar({
          theme:"minimal-dark",
          mouseWheel:{ preventDefault:true }
        });
      });
    }
  };

  /**
   * Facets show facets-category
   */
  Drupal.behaviors.socomec_facets_show = {
    attach: function (context, settings) {
      $( document ).ready(function() {
        var elementFamilyTerms = $("#block-familyterms div[data-drupal-facet-id='family_terms']");
        if (!elementFamilyTerms.hasClass('facet-empty')) {
          $('.facets-category-range').removeClass('d-none');
        }

        var elementTypeResource = $("#block-familyterms div[data-drupal-facet-id='type_of_resource_terms']");
        var elementLanguage = $("#block-familyterms div[data-drupal-facet-id='type_of_resource_terms']");
        if (!elementTypeResource.hasClass('facet-empty') || !elementLanguage.hasClass('facet-empty')) {
          $('.facets-category-characteristics').removeClass('d-none');
        }
      });
    }
  };

  /**
   * Search menu
   */
  Drupal.behaviors.socomec_search = {
    attach: function (context, settings) {
      $( document ).ready(function() {
        $(".menu--header-visitors [href='#search']", context).once('socomecSearchMenu').each(function () {
          $(this).click(function (e) {
            $(this).toggleClass('close-search');
            $(".block-soc-search-block").toggleClass('d-none');
            $(".we-mega-menu-submenu").removeClass('show');
            $('.nav-wrapper').toggleClass('open-menu');
            e.preventDefault();
            e.stopPropagation();
          });
        });
      });
    }
  };


})(jQuery, Drupal);
