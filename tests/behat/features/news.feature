Feature: News

  @api @cit @news @javascript
  Test if the page news is visible
 # vendor/bin/phing behat:run -Dbehat.tags=news
  Scenario: News detail
    Given I visit "/news/socomec-certifie-iso-14001-en-alsace"
    Then I should see a "blockquote" element
    Then I should see a ".paragraph--type--model-text ol" element

  @api @cit @news @javascript @wip
 # vendor/bin/phing behat:run -Dbehat.tags=news
  Scenario: News Landing page
    Given I visit "/news"
    And I click the "select[data-drupal-facet-id='news_theme_taxonomy_term_name'] option:last-child" element
    And I wait 2 seconds
    Then I should see "Socomec certifié ISO"
    Then I should not see "Stockage d'énergie mobile : une nouvelle solution"

