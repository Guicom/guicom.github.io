Feature: News
  #vendor/bin/phing behat:run -Dbehat.tags=news

  Background:
    Given news content:
      | language | title                                 | status |
      | English  | Socomec certified ISO-14001 in Alsace | 1      |

  @api @cit @news @javascript
  Test if the page news is visible
 # vendor/bin/phing behat:run -Dbehat.tags=news
  Scenario: News detail
    When I visit "/news/socomec-certified-iso-14001-in-alsace"
    And I accept all cookies compliance
    Then I should see a "body.node--type-news" element

  @api @cit @news @javascript
 # vendor/bin/phing behat:run -Dbehat.tags=news
  Scenario: News Landing page
    When I visit "/news"
    And I accept all cookies compliance
    And I click the "select[data-drupal-facet-id='news_theme_taxonomy_term_name'] option:last-child" element
    And I wait 2 seconds
    Then I should see "Socomec certified ISO"
