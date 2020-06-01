Feature: News
  #vendor/bin/phing behat:run -Dbehat.tags=news

  Background:
    And I am logged in as a user with the "administrator" role
    And news_audience terms:
      | language | name           |
      | English  | MyNewsAudience |
    And news_mommentum terms:
      | language | name            |
      | English  | MyNewsMommentum |
    And news_type terms:
      | language | name        |
      | English  | MyNewsType  |
    And news_theme terms:
      | language | name        |
      | English  | MyNewsTheme |
    And news content:
      | language | title                                 | field_teaser           | field_news_video |field_country | field_news_mommentum | field_news_audience | status | moderation_state |
      | English  | Socomec certified ISO-14001 in Alsace | Test behat news teaser | 0                |France        | MyNewsMommentum      | MyNewsAudience      | 1      | published        |
    And I visit "/admin/config/search/search-api/index/news"
    And I press "Index now"

  @api @cit @news @javascript @news_detail
 # vendor/bin/phing behat:run -Dbehat.tags=news_detail
  Scenario: News detail
    Given I am an anonymous user
    When I visit "/news/socomec-certified-iso-14001-alsace"
    And I accept all cookies compliance
    Then I should see a "body.node--type-news" element
    Then I should see the breadcrumb link "Home"
    #Then I should see the breadcrumb link "News"
    Then I should see the breadcrumb link "Socomec certified ISO-14001 in Alsace"

  @api @cit @news @javascript @news_lp
 # vendor/bin/phing behat:run -Dbehat.tags=news_lp
  Scenario: News Landing page
    Given I am an anonymous user
    When I visit "/news"
    And I accept all cookies compliance
    And I wait 2 seconds
    Then I should see "Socomec certified ISO"
    Then I should see the breadcrumb link "Home"
    #Then I should see the breadcrumb link "News"

  @api @cit @news @javascript @news_detail_content
 # vendor/bin/phing behat:run -Dbehat.tags=news_detail_content
    # check rendering minimal content for a news
    # ticket jira SOCSOB-413
  Scenario: News detail content
    Given I am logged in as a user with the "administrator" role
    And I go to "admin/content"
    And I click "Edit" in the "Socomec certified ISO-14001 in Alsace" row
    And I select "MyNewsType" from "field_news_type"
    And I fill in "field_news_theme[target_id]" with "MyNewsTheme"
    And I press the "field_media_image-media-library-open-button" button
    And I attach the file "images/example.jpg" to "files[upload]"
    And I wait max 10 seconds for AJAX to finish
    And I fill in "media[0][fields][field_media_image][0][alt]" with "Image alt"
    And I click the ".ui-dialog button + button" element
    And I wait max 10 seconds for AJAX to finish
    And I click the "#edit-field-multiline-add-more li.dropbutton-toggle button" element
    And I press the "field_multiline_model_text_add_more" button
    And I wait 4 seconds
    And I fill in "field_multiline[0][subform][field_title][0][value]" with "Text title"
    And I wait 2 seconds
    And I press "edit-submit"
    And I visit "/news/socomec-certified-iso-14001-alsace"
    And I wait 2 seconds
    Then I should see an ".field--name-title" element
    Then I should see an ".field--name-field-news-type" element
    Then I should see an ".field--name-field-news-theme" element
    Then I should see an ".multiline-content" element
    Then I should see an ".paragraph--type--model-text" element
    Then I should see an ".field--name-field-media-image" element
    Then I should see an ".news-date" element
    Then I should see an ".field--name-field-teaser" element

  @api @cit @news @javascript @news_detail_backtoallnews
 # vendor/bin/phing behat:run -Dbehat.tags=news_detail_backtoallnews
    # check the button all news
    # ticket jira SOCSOB-643
  Scenario: News detail backtoallnews
    Given I am an anonymous user
    When I visit "/news/socomec-certified-iso-14001-alsace"
    And I accept all cookies compliance
    Then I should see a "body.node--type-news" element
    And I should see an ".back-to-button" element
    And I wait 3 seconds
    And I click the "a[href='/news']" element
    And I wait 3 seconds
    Then I should see a ".view-news" element
