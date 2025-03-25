/**
 * @file
 * JavaScript behaviors for Tom Select integration.
 */

(function ($, Drupal, once) {
  'use strict';

  /**
   * Initialize Tom Select on select elements.
   *
   * @type {Drupal~behavior}
   */
  Drupal.behaviors.tomSelectField = {
    attach: function (context, settings) {
      once('tom-select-field', '.tom-select-field', context).forEach(function (element) {
        // Get settings from data attribute.
        let tomSelectSettings = {};
        try {
          tomSelectSettings = JSON.parse(element.getAttribute('data-tom-select-settings') || '{}');
        } catch (e) {
          console.error('Invalid Tom Select settings:', e);
        }

        // Initialize Tom Select.
        try {
          new TomSelect(element, tomSelectSettings);
        } catch (e) {
          console.error('Error initializing Tom Select:', e, element);
        }
      });
    }
  };

})(jQuery, Drupal, once);
