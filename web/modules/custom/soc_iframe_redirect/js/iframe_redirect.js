/**
 * @file
 * Redirect content displayed in an iframe to full page content.
 */

(function ($) {
  'use strict';
  Drupal.behaviors.iframe_redirect = {
    attach: function (context, settings) {
      if (top.location!== self.location) {
        top.location = self.location.href;
      }
    }
  };
})(jQuery);
