Feature: News

  @api @cit @news @javascript
  Test if the page news is visible
 # vendor/bin/phing behat:run -Dbehat.tags=news
  Scenario: News detail
    Given I visit "/news/socomec-certifie-iso-14001-en-alsace"
    Then I should see a "body.node--type-news" element

  @api @cit @news @javascript
 # vendor/bin/phing behat:run -Dbehat.tags=news
  Scenario: News Landing page
    Given I visit "/news"
    And I click the "select[data-drupal-facet-id='news_theme_taxonomy_term_name'] option:last-child" element
    And I wait 2 seconds
    Then I should see "Socomec certifié ISO"
    Then I should not see "Stockage d'énergie mobile : une nouvelle solution"
    Then I should see "Iterative approaches to establish a new normal that has evolved from"
