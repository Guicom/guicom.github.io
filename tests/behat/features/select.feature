Feature: [SELECT] Tests Behat

  @api @cit @select @javascript
  Checking bootstrap select on filter
  #  vendor/bin/phing behat:run -Dbehat.tags=select
  #  ticket JIRA: SOCSOB-662
  Scenario: News bootstrap-select
    Given I am logged in as a user with the "administrator" role
    And I accept all cookies compliance
    And news content:
      | Language | title     | field_news_type | field_news_theme | field_news_audience  | field_news_mommentum |field_teaser | moderation_state | status |
      | English  | News test | News            | Industry         | USERS                | Setup                | Teaser test | published        | 1      |
    And I visit "admin/config/search/search-api/index/news"
    And I press "Index now"
    Then I visit "/news"
    Then I should see "News test"
    #  Je vois les classes .bootstrap-select sur l'element Type
    Then I should see "Filter by"
    Then I should see a ".facet-inline-position-title #block-newstypetaxonomytermname .bootstrap-select" element
    Then I should see "TYPES"
    #  Je vois les classes .mCustomScrollbar qui applique la scroll bar custom sur l'element Type
    Then I should see a ".facet-inline-position-title #block-newstypetaxonomytermname .mCustomScrollbar " element
    #  Je vois les classes .bootstrap-select sur l'element Thematique
    Then I should see a ".facet-inline-position-title #block-newsthemetaxonomytermname .bootstrap-select" element
    Then I should see "THEMATICS"
     #  Je vois les classes .mCustomScrollbar qui applique la scroll bar custom sur l'element Thematique
    Then I should see a ".facet-inline-position-title #block-newsthemetaxonomytermname .mCustomScrollbar " element

  @api @cit @select @javascript
  #  vendor/bin/phing behat:run -Dbehat.tags=select
  #  ticket JIRA: SOCSOB-662
  Scenario: Resource-center bootstrap-select
    Given I am logged in as a user with the "administrator" role
    And I accept all cookies compliance
    And resource content:
      | Language | field_res_reference | title         | field_res_original_title | field_res_resource_type | field_product_family    | moderation_state | status |
      | English  | TEST00001           | Resource test | Resource test            | Brochure                | Energy storage solution | published        | 1      |
    And I visit "admin/config/search/search-api/index/resources"
    And I press "Index now"
    Then I visit "/resource-center"
    Then I should see "Resource test"
    #  Je vois la section Filter by
    Then I should see "Filter by"
    #  Je vois la section RANGE
    Then I should see "RANGE"
    Then I should see "Filter by"
    #  Je vois les classes .facet-list-display-select sur l'element Product Family
    Then I should see a ".facet-list-display-select #block-familyterms .facet-title .filter-option" element
    Then I should see a ".facet-list-display-select #block-familyterms .facets-widget-links" element
    Then I should see "Product Family"
    #  Je vois les classes .mCustomScrollbar qui applique la scroll bar custom sur l'element Product Family
    Then I should see a ".facet-list-display-select #block-familyterms .mCustomScrollbar " element
    #  Je vois la section CHARACTERISTICS
    Then I should see "CHARACTERISTICS"
    #  Je vois les classes .bootstrap-select sur l'element Type
    Then I should see a ".facet-bloc-position-title.facet-full-width #block-typeofresourceterms .bootstrap-select" element
    Then I should see "Type All"
    #  Je vois les classes .mCustomScrollbar qui applique la scroll bar custom sur l'element Type
    Then I should see a ".facet-bloc-position-title.facet-full-width #block-language .mCustomScrollbar " element
    #  Je vois les classes .bootstrap-select sur l'element Language
    Then I should see "Language All"
    #  Je vois les classes .mCustomScrollbar qui applique la scroll bar custom sur l'element Type
    Then I should see a ".facet-bloc-position-title.facet-full-width #block-language .mCustomScrollbar " element
