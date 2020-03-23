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
function soc_content_update_8001() {
  $newTerm = $termManager->createTerm('My new term', 'my_existing_vocabulary', [
    'field_to_populate' => $field_value,
  ]);
}
```

#### Update a taxonomy term

```php
function soc_content_update_8002() {
  $myExistingTermUuid = 'aaaa-bbbb-cccc-dddd';
  $updatedTerm = $termManager->updateTerm($myExistingTermUuid, [
    'field_to_update' => 'My updated field',
  ]);
}
```

### Block content

#### Invoke block content manager

```php
/** @var \Drupal\soc_content\Service\BlockContentContent $blockContentManager */
$blockContentManager = \Drupal::service('soc_content.block_content');
```

#### Create a block content

```php
function soc_content_update_8001() {
  $blockUuid = 'aaaa-bbbb-cccc-dddd';
  $newBlockContent = $blockContentManager->createBlockContent($type, $info, [
    'uuid' => 'aaaa-bbbb-cccc-dddd', // UUID of existing block to use
    'field_to_set' => 'Value to set',
  ]);
}
```

#### Update a block content

```php
function soc_content_update_8002() {
  $myExistingBlockContentUuid = 'aaaa-bbbb-cccc-dddd';
  $updatedBlockContent = $blockContentManager->updateBlockContent($myExistingBlockContentUuid, [
    'field_to_update' => 'My updated field',
  ]);
}
```

### File / Media

#### Invoke media manager (used for files too)

```php
/** @var \Drupal\soc_content\Service\MediaContent $mediaManager */
$mediaManager = \Drupal::service('soc_content.media');
```

#### Create an image media

```php
function soc_content_update_8001() {
  // file path from content/images folder
  if ($logoImage = $mediaManager->createFile('logo-socomec_blue.png')) {
    $logoMedia = $mediaManager->createMedia($logoImage, 'Logo Socomec Social Footer', 'image');
  }
}
```

### Paragraph

#### Invoke paragraph manager

```php
/** @var \Drupal\soc_content\Service\ParagraphContent $paragraphManager */
$paragraphManager = \Drupal::service('soc_content.paragraph');
```

#### Create a paragraph

```php
function soc_content_update_8001() {
  $linkedInParagraph = $paragraphManager->createParagraph('link', [
    'field_link_icon' => [
      'target_id' => $linkedInIcon->id(),
    ],
    'field_link_link' => 'https://www.linkedin.com/company/socomec/',
  ]);
}
```

## More info

* https://befused.com/drupal/site-deployment-module
