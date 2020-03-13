/**
 * @file
 * open eu_cookie_compliance popup
 */

(function ($) {
  'use strict';
  Drupal.behaviors.soc_ecc = {
    attach: function (context, settings) {
      $( document ).ready(function() {
        if(!Drupal.behaviors.soc_ecc.click_set){
          $( ".display-ecc-popup" ).click(function(event) {
            event.preventDefault();
            Drupal.eu_cookie_compliance.createPopup(drupalSettings.eu_cookie_compliance.popup_html_info);
            Drupal.eu_cookie_compliance.initPopup();
            Drupal.behaviors.socomec_eu_cookie_compliance.openCategories();
          });
          Drupal.behaviors.soc_ecc.click_set = true;
        }
      });
    }
  };
})(jQuery);
