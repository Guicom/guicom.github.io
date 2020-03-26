Feature: News
  #vendor/bin/phing behat:run -Dbehat.tags=news

  Background:
    And I am logged in as a user with the "administrator" role
    And news content:
      | language | title                                 | status | moderation_state |
      | English  | Socomec certified ISO-14001 in Alsace | 1      | published        |
    And I visit "/admin/config/search/search-api/index/news"
    And I press "Index now"
    And I visit "/admin/content"
    And I wait 60 seconds

  @api @cit @news @javascript @news_detail
  Test if the page news is visible
 # vendor/bin/phing behat:run -Dbehat.tags=news_detail
  Scenario: News detail
    Given I am an anonymous user
    When I visit "/news/socomec-certified-iso-14001-alsace"
    And I accept all cookies compliance
    Then I should see a "body.node--type-news" element

  @api @cit @news @javascript @news_lp
 # vendor/bin/phing behat:run -Dbehat.tags=news_lp
  Scenario: News Landing page
    Given I am an anonymous user
    When I visit "/news"
    And I accept all cookies compliance
    And I wait 2 seconds
    Then I should see "Socomec certified ISO"
