# Soc Content module

## Purpose

The goal of this module is to help developers to create or update contents on a website where contents are also being contributed by webmasters.

To do so, we are using deployment hooks.

The code robustness is taken care of by the manager services provided by this module.

The deployment hooks are created in `soc_content.install`.

## Examples

### Taxonomy term

#### Invoke taxonomy term manager

```php
/** @var \Drupal\soc_content\Service\TaxonomyTermContent $termManager */
$termManager = \Drupal::service('soc_content.taxonomy_term');
```

#### Create a taxonomy term

```php
$newTerm = $termManager->createTerm([
  'name' => 'My new term',
  'vid' => 'my_existing_vocabulary',
]);
```

#### Update a taxonomy term

```php
$myExistingTermUuid = 'aaaa-bbbb-cccc-dddd';
$updatedTerm = $termManager->updateTerm($myExistingTermUuid, [
  'name' => 'My updated term',
  'field_to_update' => 'My updated field',
  'vid' => 'my_existing_vocabulary',
]);
```

### Block content

#### Invoke block content manager

```php
/** @var \Drupal\soc_content\Service\BlockContentContent $blockContentManager */
$blockContentManager = \Drupal::service('soc_content.block_content');
```

#### Create a block content

```php
$blockUuid = 'aaaa-bbbb-cccc-dddd';
$newBlockContent = $blockContentManager->createTerm($blockUuid, [
  'name' => 'My new block content',
  'vid' => 'my_existing_vocabulary',
]);
```

#### Update a block content

```php
$myExistingBlockContentUuid = 'aaaa-bbbb-cccc-dddd';
$updatedBlockContent = $blockContentManager->updateBlockContent($myExistingBlockContentUuid, [
  'name' => 'My updated block content',
  'field_to_update' => 'My updated field',
  'vid' => 'my_existing_vocabulary',
]);
```

## More info

* https://befused.com/drupal/site-deployment-module
