<?php

namespace Drupal\tom_select_field\Plugin\Field\FieldWidget;

use Drupal\Core\Field\FieldItemListInterface;
use Drupal\Core\Field\WidgetBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Field\Plugin\Field\FieldWidget\OptionsSelectWidget;

/**
 * Plugin implementation of the 'tom_select' widget.
 *
 * @FieldWidget(
 *   id = "tom_select",
 *   label = @Translation("Tom Select"),
 *   field_types = {
 *     "list_string",
 *     "list_integer",
 *     "list_float",
 *     "entity_reference",
 *   },
 *   multiple_values = TRUE
 * )
 */
class TomSelectWidget extends OptionsSelectWidget {

  /**
   * {@inheritdoc}
   */
  public static function defaultSettings() {
    return [
      'create' => FALSE,
      'create_on_blur' => FALSE,
      'max_items' => NULL,
      'persist' => TRUE,
      'open_on_focus' => TRUE,
      'hide_selected' => TRUE,
      'allow_empty_option' => FALSE,
      'remove_button' => FALSE,
      'placeholder' => '',
    ] + parent::defaultSettings();
  }

  /**
   * {@inheritdoc}
   */
  public function settingsForm(array $form, FormStateInterface $form_state) {
    $element = parent::settingsForm($form, $form_state);
    
    $element['create'] = [
      '#type' => 'checkbox',
      '#title' => $this->t('Allow creating new items'),
      '#default_value' => $this->getSetting('create'),
      '#description' => $this->t('Allow users to create items that are not in the list of options.'),
    ];
    
    $element['create_on_blur'] = [
      '#type' => 'checkbox',
      '#title' => $this->t('Create on blur'),
      '#default_value' => $this->getSetting('create_on_blur'),
      '#description' => $this->t('When the user exits the field, create a new option if the input is not empty.'),
      '#states' => [
        'visible' => [
          ':input[name="fields[' . $this->fieldDefinition->getName() . '][settings_edit_form][settings][create]"]' => ['checked' => TRUE],
        ],
      ],
    ];
    
    $element['max_items'] = [
      '#type' => 'number',
      '#title' => $this->t('Maximum number of items'),
      '#default_value' => $this->getSetting('max_items'),
      '#description' => $this->t('The maximum number of items the user can select. Leave empty for unlimited.'),
      '#min' => 1,
    ];
    
    $element['persist'] = [
      '#type' => 'checkbox',
      '#title' => $this->t('Persist selection'),
      '#default_value' => $this->getSetting('persist'),
      '#description' => $this->t('If false, items created by the user will not show up as available options once they are unselected.'),
    ];
    
    $element['open_on_focus'] = [
      '#type' => 'checkbox',
      '#title' => $this->t('Open on focus'),
      '#default_value' => $this->getSetting('open_on_focus'),
      '#description' => $this->t('Show the dropdown immediately when the control receives focus.'),
    ];
    
    $element['hide_selected'] = [
      '#type' => 'checkbox',
      '#title' => $this->t('Hide selected'),
      '#default_value' => $this->getSetting('hide_selected'),
      '#description' => $this->t('If true, the items that are currently selected will not be shown in the dropdown list of available options.'),
    ];
    
    $element['allow_empty_option'] = [
      '#type' => 'checkbox',
      '#title' => $this->t('Allow empty option'),
      '#default_value' => $this->getSetting('allow_empty_option'),
      '#description' => $this->t('If true, empty options will be treated as normal options instead of being ignored.'),
    ];
    
    $element['remove_button'] = [
      '#type' => 'checkbox',
      '#title' => $this->t('Show remove button'),
      '#default_value' => $this->getSetting('remove_button'),
      '#description' => $this->t('Add a button to each selected item to remove it.'),
    ];
    
    $element['placeholder'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Placeholder'),
      '#default_value' => $this->getSetting('placeholder'),
      '#description' => $this->t('The placeholder text to display when nothing is selected.'),
    ];
    
    return $element;
  }

  /**
   * {@inheritdoc}
   */
  public function settingsSummary() {
    $summary = parent::settingsSummary();
    
    if ($this->getSetting('create')) {
      $summary[] = $this->t('Allow creating new items: Yes');
      if ($this->getSetting('create_on_blur')) {
        $summary[] = $this->t('Create on blur: Yes');
      }
    }
    
    if ($this->getSetting('max_items')) {
      $summary[] = $this->t('Maximum items: @max', ['@max' => $this->getSetting('max_items')]);
    }
    
    $summary[] = $this->t('Persist selection: @persist', ['@persist' => $this->getSetting('persist') ? 'Yes' : 'No']);
    $summary[] = $this->t('Open on focus: @open', ['@open' => $this->getSetting('open_on_focus') ? 'Yes' : 'No']);
    $summary[] = $this->t('Hide selected: @hide', ['@hide' => $this->getSetting('hide_selected') ? 'Yes' : 'No']);
    
    if ($this->getSetting('allow_empty_option')) {
      $summary[] = $this->t('Allow empty option: Yes');
    }
    
    if ($this->getSetting('remove_button')) {
      $summary[] = $this->t('Show remove button: Yes');
    }
    
    if (!empty($this->getSetting('placeholder'))) {
      $summary[] = $this->t('Placeholder: @placeholder', ['@placeholder' => $this->getSetting('placeholder')]);
    }
    
    return $summary;
  }

  /**
   * {@inheritdoc}
   */
  public function formElement(FieldItemListInterface $items, $delta, array $element, array &$form, FormStateInterface $form_state) {
    $element = parent::formElement($items, $delta, $element, $form, $form_state);
    
    // Add the library.
    $element['#attached']['library'][] = 'tom_select_field/tom_select_field';
    
    // Add settings for the JavaScript.
    $tom_select_settings = [
      'create' => $this->getSetting('create'),
      'createOnBlur' => $this->getSetting('create_on_blur'),
      'persist' => $this->getSetting('persist'),
      'openOnFocus' => $this->getSetting('open_on_focus'),
      'hideSelected' => $this->getSetting('hide_selected'),
      'allowEmptyOption' => $this->getSetting('allow_empty_option'),
    ];
    
    // Add placeholder if set
    if (!empty($this->getSetting('placeholder'))) {
      $tom_select_settings['placeholder'] = $this->getSetting('placeholder');
    }
    
    if (!empty($this->getSetting('max_items'))) {
      $tom_select_settings['maxItems'] = (int) $this->getSetting('max_items');
    }
    
    // Add plugins
    $tom_select_settings['plugins'] = [];
    
    // Add remove_button plugin if enabled
    if ($this->getSetting('remove_button')) {
      $tom_select_settings['plugins']['remove_button'] = [];
    }
    
    // Add a class to identify the element for the JavaScript.
    $element['#attributes']['class'][] = 'tom-select-field';
    $element['#attributes']['data-tom-select-settings'] = json_encode($tom_select_settings);
    
    return $element;
  }

}
