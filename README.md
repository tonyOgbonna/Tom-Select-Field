# Tom Select Field

## Introduction

Tom Select Field is a Drupal module that integrates the Tom Select JavaScript library with Drupal's form API to provide an enhanced select widget for various field types.

## Requirements

- Drupal 9.3+, 10, or 11
- Tom Select library (https://tom-select.js.org/)

## Installation

1. Download and install the module using Composer:
   ```
   composer require drupal/tom_select_field
   ```

2. Download the Tom Select library:
   - Download the latest release from https://github.com/orchidjs/tom-select/releases
   - Extract the files and place them in `/libraries/tom-select/` so that the file structure is:
     ```
     /libraries/tom-select/dist/js/tom-select.complete.min.js
     /libraries/tom-select/dist/css/tom-select.default.min.css
     ```

3. Enable the module:
   ```
   drush en tom_select_field
   ```

## Usage

1. Go to the form display settings for any content type or entity that has select fields, entity reference fields, or list fields.
2. For each field you want to enhance with Tom Select, click the gear icon to access the field widget settings.
3. Check the "Use Tom Select" option and configure the Tom Select settings as needed.
4. Save the form display settings.

## Configuration Options

The module provides the following configuration options for each field:

- **Use Tom Select**: Enable/disable Tom Select for this field.
- **Allow creating new items**: Allow users to create items that are not in the list of options.
- **Create on blur**: When the user exits the field, create a new option if the input is not empty.
- **Maximum number of items**: The maximum number of items the user can select.
- **Persist selection**: If disabled, items created by the user will not show up as available options once they are unselected.
- **Open on focus**: Show the dropdown immediately when the control receives focus.
- **Hide selected**: If enabled, the items that are currently selected will not be shown in the dropdown list of available options.
- **Allow empty option**: If enabled, empty options will be treated as normal options instead of being ignored.
- **Show remove button**: Adds a button next to each selected item to remove it.
- **Placeholder**: The text to display when no items are selected.

## Maintainers

- Tony Ogbonns - https://www.drupal.org/u/togbonna

## License

This project is licensed under the GNU General Public License v2.0 or later.
