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
          Drupal.behaviors.socomec_eu_cookie_compliance.openCategories();
        });

        $(".close-button", context).click(function() {
          Drupal.behaviors.socomec_eu_cookie_compliance.closeCategories();
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

        var height = $(".eu-cookie-compliance-content").height();
        $("body").css('padding-bottom',height+'px');
      });

    },

    openCategories: function () {
      $(".eu-cookie-compliance-banner").addClass("categorie-open");
      $(".customize-button").addClass("d-none");
      $(".eu-cookie-compliance-categories").removeClass("d-none").addClass("d-bloc");
    },
    closeCategories: function () {
      $(".eu-cookie-compliance-banner").removeClass("categorie-open");
      $(".customize-button").addClass("d-none");
      $(".eu-cookie-compliance-categories").removeClass("d-bloc").addClass("d-none");
      $("#sliding-popup").addClass("d-none");
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

      $(document).ready(function() {
        if ($("body").hasClass("node--type-product")) {
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
      });
    }
  };

  /**
   * Facets bootstrap_select
   */
  function SocomecfacetsCalculate(element) {
    if ($(element).parents('.facet-inline-position-title').length) {
      var width = $(element).parents("div.block-facet--dropdown").find("div.facet-title").first().width();
      $(element).siblings('.dropdown-toggle.btn-light').css('padding-left', width + 20 + 'px');
    }
    else{
      var height = $(element).parents("div.block-facet--dropdown").find("div.facet-title").first().height();
      $(element).siblings('.dropdown-toggle.btn-light').css('padding-top', height + 7 + 'px');
    }
  }

  function SocomecfacetsSelect(context, settings) {
    $('select').each(function () {
      var element = $(this);
        if ($("body").hasClass("path-where-to-buy")) {
          element.attr('data-live-search', 'true');
        }
      element.selectpicker({
        virtualScroll: false,
      });
      SocomecfacetsCalculate(element);
      element.parents(".bootstrap-select").find("div.dropdown-menu").first().mCustomScrollbar({
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

  Drupal.behaviors.socomec_facets_bootstrap_select = {
    attach: function (context, settings) {
      SocomecfacetsSelect(context, settings);
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
   * Back to top btn
   */
  Drupal.behaviors.socomec_back_to_top = {
    attach: function (context, settings) {
      var backToTopBtn = $('#block-backtotop', context);
      if (backToTopBtn.length) {
        var scrollTrigger = 100, // px
          backToTop = function () {
            var scrollTop = $(window).scrollTop();
            if (scrollTop > scrollTrigger) {
              backToTopBtn.addClass('show');
            } else {
              backToTopBtn.removeClass('show');
            }
          };
        backToTop();
        $(window).on('scroll', function () {
          backToTop();
        });
        backToTopBtn.once('socomecBackToTop').on('click', function (e) {
          e.preventDefault();
          $('html,body').animate({
            scrollTop: 0
          }, 700);
        });
      }
    }
  };

  /**
   * Search menu
   */
  Drupal.behaviors.socomec_search = {
    attach: function (context, settings) {
      $( document ).ready(function() {
        $("[data-drupal-link-system-path='search']", context).once('socomecSearchMenu').each(function () {
          var toggleSearch = function(link) {
            var searchBlock = $(".block-soc-search-block");
            var navWrapper = $(".nav-wrapper");
            $(link).toggleClass('close-search icon-search-white');
            searchBlock.toggleClass('d-none');
            $(".we-mega-menu-submenu").removeClass('show');
            if (searchBlock.hasClass('d-none')) {
              if (navWrapper.hasClass('open-menu')) {
                navWrapper.removeClass('open-menu');
              }
            }
            else{
              if (!navWrapper.hasClass('open-menu')) {
                navWrapper.addClass('open-menu');
              }
              $('.block-soc-search-block input[name="search_api_fulltext"]').focus();
            }
            $(document).on('keydown', function(event) {
              if (event.key === "Escape") {
                toggleSearch(link);
              }
            });
          };
          $(this).click(function (e) {
            toggleSearch(this);
            e.preventDefault();
            e.stopPropagation();
          });
        });
      });
    }
  };

  /**
   * Close modal for country selection on mobile
   */
  Drupal.behaviors.socomec_country_close_modal = {
    attach: function (context, settings) {

      var closeBtn = $('#myTabContent .modal-close');

      closeBtn.once().on("click", function (e) {
        closeBtn.closest('.tab-pane').removeClass('active');
        $('.nav-link.active').removeClass('active');
      })
    }
  };

  /**
   * Modal mechanics for facets filters on mobile
   */
  Drupal.behaviors.socomec_facets_modals = {
    attach: function (context, settings) {

      var openBtn = $('.facet-mobile-modal-open .modal-open');
      var closeBtn = $('.facet-mobile-modal-close .modal-close');

      openBtn.once().on("click", function (e) {
        $('.modal-facets-mobile').addClass('active');
        SocomecfacetsSelect(context, settings);
      });
      closeBtn.once().on("click", function (e) {
        $('.modal-facets-mobile').removeClass('active');
        SocomecfacetsSelect(context, settings);
      });
    }
  };

  /**
   * Modal with youtube video
   */
  Drupal.behaviors.socomec_modal_videos = {
    attach: function (context, settings) {

      $(document).ready(function() {
        let videoSrc;
        let getVideo;
        // let videoTitle;
        // let videoDesc;
        // When we click to open modal, get videoId and set videoSrc
        $('.video-launcher').click(function() {
           getVideo = $('.src').html();
          // let videoid = $('.video-iframe').data("src").match(/(?:https?:\/{2})?(?:w{3}\.)?youtu(?:be)?\.(?:com|be)(?:\/watch\?v=|\/)([^\s&]+)/);
          // videoSrc = "https://www.youtube.com/embed/"+videoid[1];
          // videoTitle = $(this).parent().find('.videoTitle').text();
          // videoDesc = $(this).parent().find('.videoDescription').text();
        });
        // When modal is open, we autoplay video
        $('.modal-resource').on('show.bs.modal', function (e) {
          $(".video-iframe", this).attr('src',getVideo + "?autoplay=1&showinfo=0&modestbranding=1" );
          // $('#ModalLongTitle', this).text(videoTitle);
          // $('#ModalVideoDescription', this).text(videoDesc);
        });
        // When modal is close, we stop video
        $('.modal-resource').on('hidden.bs.modal', function (e) {
          $(".video-iframe", this).attr('src','');
          // $('#ModalLongTitle', this).text('');
          // $('#ModalVideoDescription', this).text('');
        });
      });
    }
  };

  /**
   * Close automatically jumpto dropdown
   */
  Drupal.behaviors.socomec_jumpto_closing = {
    attach: function (context, settings) {

      var jumptoLink = $('a[href^="#"]:not([href="#"])');

      jumptoLink.once().on("click", function (e) {
        $('#product-jumpto').removeClass('show');
      });
    }
  };

  /**
   * Close automatically other menu on mobile
   */
  Drupal.behaviors.socomec_menu_interactions = {
    attach: function (context, settings) {

      var btnMainMenu = $('#btnMainNav');
      var btnSearchMenu = $('a[data-drupal-link-system-path="search"]');

      var menuSearch = $('.block-soc-search-block');
      var menuMainMenu = $('#navbarSupportedContent');

      btnMainMenu.once().on("click", function (e) {
        menuSearch.addClass('d-none');
        btnSearchMenu.removeClass('close-search');
      });
      btnSearchMenu.once().on("click", function (e) {
        menuMainMenu.removeClass('show');
        btnMainMenu.attr("aria-expanded","false");
      });
    }
  };

  /**
  * Ajax btn bookmarks and wishlist
  */
  Drupal.behaviors.socomec_content_list_ajax_btn = {
    attach: function (context, settings) {
      Drupal.behaviors.socomec_content_list_ajax_btn.updateRenderNavigation();
      var elements = $('a[data-soc-content-list-ajax="1"]');
      $(elements, context).once('socomec_content_list_ajax_btn').each(function () {
        $(this).click(function (e) {
          var element = $(this);
          var href = element.attr('href');
          element.addClass( "item-pending" );
          $.ajax({
            url: href,
            type: 'GET',
            dataType: 'json',
            success: function (output, statut) {
              element.removeClass( "item-pending" );
              if (output == "true") {
                Drupal.behaviors.socomec_content_list_ajax_btn.updateRenderNavigation();
                element.addClass("soc-list-is-active");
                element.addClass("item-added").delay(5000).queue(function(){
                  element.removeClass("item-added").dequeue();
                });
              }
            }
          });
          e.preventDefault();
          e.stopPropagation();
        });
      });
    },
    updateRenderNavigation: function () {
      var wishlist = $.cookie("socomec_wishlist");
      var i = 0;
      if(typeof wishlist !== 'undefined' && wishlist != null) {
        $.each(JSON.parse(wishlist), function (key, value) {
          i++;
        });
      }
      if (i > 0) {
        $('.menu--header-visitors .ico-favorite').addClass('soc-list-is-active');
        $('.menu--header-visitors .ico-favorite').attr("data-soc-list", i);
      }
      var bookmark = $.cookie("socomec_bookmark");
      var j = 0;
      if(typeof bookmark !== 'undefined' && bookmark != null) {
        $.each(JSON.parse(bookmark), function (key, value) {
          j++;
        });
      }
      if (j > 0) {
        $('.menu--header-visitors .ico-bookmark-star-white').addClass('soc-list-is-active');
        $('.menu--header-visitors .ico-bookmark-star-white').attr("data-soc-list", i);
      }
    }
  };

})(jQuery, Drupal);
