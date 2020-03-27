Feature: Search

  @api @cit @javascript @search
  # ./vendor/bin/phing behat:run -Dbehat.tags=search
  Scenario: Search Settings Backoffice
    # Je me connect en webmaster
    Given I am logged in as a user with the "webmaster" role
    # J'accepte les cookies
    And I accept all cookies compliance
    When I visit "/admin/config/socomec/soc_search/settings"
    # Je test et je contribue le formulaire Backoffice de configuration du search
    Then I should see "Page title"
    And I fill in "Page title" with "TEST EN PAGE title"
    Then I should see "Page title with searched text"
    And I fill in "Page title with searched text" with "TEST Page title with searched text @total results for @word"
    Then I should see "Page title without result"
    And I fill in "Page title without result" with "TEST Page title without result"
    Then I should see "Breadcrumb title"
    And I fill in "Breadcrumb title" with "TEST Breadcrumb title"
    Then I should see "Breadcrumb title with searched text"
    And I fill in "Breadcrumb title with searched text" with "TEST Breadcrumb title with searched text @word"
    Then I should see "Placeholder"
    And I fill in "Placeholder" with "TEST Placeholder"
    Then I should see "Maximum number of links in Top Search."
    Then I should see "Top Search Title block"
    Then I should see "Top Search settings"
    Then I should see "Maximum number of links in Quick Links."
    Then I should see "Quicklink Title block"
    Then I should see "Quick Links settings"
    And I press "Save configuration"
    # Je test le front search en anglais sur une recherche non existante "TestingSearchNoResult"
    And I visit "/en"
    And I click the ".container-nav > .menu--header-visitors .ico-search-white" element
    Then I should see an "[placeholder='TEST Placeholder']" element
    And I fill in "edit-search-api-fulltext" with "TestingSearchNoResult"
    And I submit the form with id "views-exposed-form-global-search-page-1"
    Then I should see "TEST Breadcrumb title with searched text TestingSearchNoResult"
    Then I should see "TEST Page title without result"
    # J'ajoute des traductions sur le formulaire Backoffice FR
    And I visit "/admin/config/socomec/soc_search/settings/translate/fr/add"
    And I fill in "Title" with "TEST FR PAGE title"
    And I fill in "Placeholder" with "TEST FR Placeholder"
    And I fill in "Breadcrumb title" with "TEST FR Breadcrumb"
    And I fill in "Breadcrumb title with searched text" with "TEST FR Breadcrumb title with searched text results for @word"
    And I fill in "Page title with searched text" with "TEST FR Title searched text @total results for @word"
    And I fill in "Page title without result" with "TEST FR Page title without result"
    And I press "Save translation"
    # Je test le front search en français sur une recherche non existante "TestingSearchNoResult"
    And I visit "/fr"
    And I click the ".container-nav > .menu--header-visitors .ico-search-white" element
    Then I should see an "[placeholder='TEST FR Placeholder']" element
    And I fill in "edit-search-api-fulltext" with "TestingSearchNoResult"
    And I submit the form with id "views-exposed-form-global-search-page-1"
    Then I should see "TEST FR Breadcrumb title with searched text results for TestingSearchNoResult"
    Then I should see "TEST FR Page title without result"
    # Creation des contenus de test
    # event /!\ Le module datetime_range ne fonctionne pas sur les tests.
    And event content:
      | Language | title                                            | field_event_teaser                                  | field_event_place                                 | field_event_type | field_event_thematic | moderation_state | status |
      | English  | TestingSearchTitle TestingSearchTitleEvent Title | TestingSearchTeaser TestingSearchTeaserEvent teaser | TestingSearchPlace TestingSearchPlaceEvent place  | Conference       | Energy               | published        | 1      |
    # landing_page genre une Resource
    And landing_page content:
      | Language | title                                                   | field_premium_content_type | moderation_state | status |
      | English  | TestingSearchTitle TestingSearchTitlelandingPage Title  | White paper                | published        | 1      |
    # news
    And news content:
      | Language | title                                           | field_news_type  | field_news_theme | field_teaser                                       | moderation_state | status |
      | English  | TestingSearchTitle TestingSearchTitleNews Title | Corporate        | Building         | TestingSearchTeaser TestingSearchTeaserNews teaser | published        | 1      |
    # product
    And product content:
      | Language | title                                               | field_product_teaser                                  | field_protection_level | field_product_status | moderation_state | status |
      | English  | TestingSearchTitle TestingSearchTitleProduct Title  | TestingSearchTeaser TestingSearchTeaserProduct teaser | Ultimate               | New                  | published        | 1      |
    # product_reference
    And product_reference content:
      | Language | title                                                 | field_teaser                                            | field_reference_ref | moderation_state | status |
      | English  | TestingSearchTitle TestingSearchTitleReference Title  | TestingSearchTeaser TestingSearchTeaserReference teaser | TestReference       | published        | 1      |
    And I am logged in as a user with the "administrator" role
    And I visit "/admin/config/search/search-api/index/global_search"
    And I press "Index now"
    Then I am an anonymous user
    # Testing event result
    And I visit "/en"
    And I accept all cookies compliance
    And I click the ".container-nav > .menu--header-visitors .ico-search-white" element
    And I fill in "edit-search-api-fulltext" with "TestingSearchTitleEvent"
    And I submit the form with id "views-exposed-form-global-search-page-1"
    Then I should see "TEST Breadcrumb title with searched text TestingSearchTitleEvent"
    Then I should see "TEST Page title with searched text 1 results for TestingSearchTitleEvent"
    Then I should see "TYPE" in the ".block-facet-blockcontent-type .facet-title" element
    Then I should see "EVENT" in the ".block-facet-blockcontent-type" element
    Then I should see "EVENT THEMATICS" in the ".block-facet-blockthematics .facet-title" element
    Then I should see "Energy" in the ".block-facet-blockthematics" element
    Then I should see "EVENT TYPE" in the ".block-facet-blockevent-type .facet-title" element
    Then I should see "Conference" in the ".block-facet-blockevent-type" element
    Then I should see "EVENT" in the ".field--name-bundle-fieldnode" element
    Then I should see "Conference" in the ".field--name-field-event-type" element
    Then I should see "TestingSearchTitle TestingSearchTitleEvent Title"
    Then I should see "TestingSearchPlace TestingSearchPlaceEvent place"
    # Testing la landing page n'est pas indexé mais la Resource généré oui
    And I visit "/en"
    And I click the ".container-nav > .menu--header-visitors .ico-search-white" element
    And I fill in "edit-search-api-fulltext" with "TestingSearchTitlelandingPage"
    And I submit the form with id "views-exposed-form-global-search-page-1"
    Then I should see "TEST Breadcrumb title with searched text TestingSearchTitlelandingPage"
    Then I should see "TEST Page title with searched text 1 results for TestingSearchTitlelandingPage"
    Then I should see "TYPE" in the ".block-facet-blockcontent-type .facet-title" element
    Then I should see "Resource" in the ".block-facet-blockcontent-type" element
    Then I should see "White paper" in the ".field--name-field-res-resource-type" element
    Then I should see "EN" in the ".field--name-extra-field-language-resource" element
    Then I should not see "Landing page - Premium" in the ".block-facet-blockcontent-type" element
    Then I should see "TYPE OF RESOURCE" in the ".block-facet-blocktype-of-resource .facet-title" element
    Then I should see "TestingSearchTitle TestingSearchTitlelandingPage Title"
    # Testing news result
    And I visit "/en"
    And I click the ".container-nav > .menu--header-visitors .ico-search-white" element
    And I fill in "edit-search-api-fulltext" with "TestingSearchTitleNews"
    And I submit the form with id "views-exposed-form-global-search-page-1"
    Then I should see "TEST Breadcrumb title with searched text TestingSearchTitleNews"
    Then I should see "TEST Page title with searched text 1 results for TestingSearchTitleNews"
    Then I should see "TYPE" in the ".block-facet-blockcontent-type .facet-title" element
    Then I should see "News" in the ".block-facet-blockcontent-type" element
    Then I should see "NEWS THEME" in the ".block-facet-blocknews-theme .facet-title" element
    Then I should see "Building" in the ".block-facet-blocknews-theme" element
    Then I should see "NEWS TYPE" in the ".block-facet-blocknews-type .facet-title" element
    Then I should see "Corporate" in the ".block-facet-blocknews-type" element
    Then I should see "News" in the ".field--name-bundle-fieldnode" element
    Then I should see "Building" in the ".field--name-field-news-theme" element
    Then I should see "TestingSearchTitle TestingSearchTitleNews Title"
     # Testing product result
    And I visit "/en"
    And I click the ".container-nav > .menu--header-visitors .ico-search-white" element
    And I fill in "edit-search-api-fulltext" with "TestingSearchTitleProduct"
    And I submit the form with id "views-exposed-form-global-search-page-1"
    Then I should see "TEST Breadcrumb title with searched text TestingSearchTitleProduct"
    Then I should see "TEST Page title with searched text 1 results for TestingSearchTitleProduct"
    Then I should see "TYPE" in the ".block-facet-blockcontent-type .facet-title" element
    Then I should see "Product" in the ".block-facet-blockcontent-type" element
    Then I should see "PROTECTION LEVEL" in the ".block-facet-blockprotection-level .facet-title" element
    Then I should see "Ultimate" in the ".block-facet-blockprotection-level" element
    Then I should see "Product" in the ".field--name-bundle-fieldnode" element
    Then I should see "New" in the ".field--type-entity-reference" element
    Then I should see "TestingSearchTitle TestingSearchTitleProduct Title"
    Then I should see "TestingSearchTeaser TestingSearchTeaserProduct teaser"
    # Testing product_reference result
    And I visit "/en"
    And I click the ".container-nav > .menu--header-visitors .ico-search-white" element
    And I fill in "edit-search-api-fulltext" with "TestingSearchTitleReference"
    And I submit the form with id "views-exposed-form-global-search-page-1"
    Then I should see "TEST Breadcrumb title with searched text TestingSearchTitleReference"
    Then I should see "TEST Page title with searched text 1 results for TestingSearchTitleReference"
    Then I should see "TYPE" in the ".block-facet-blockcontent-type .facet-title" element
    Then I should see "Reference" in the ".block-facet-blockcontent-type" element
    Then I should see "Reference" in the ".field--name-bundle-fieldnode" element
    Then I should see "TestReference" in the ".field--name-field-reference-ref" element
    Then I should see "TestingSearchTitle TestingSearchTitleReference Title"
    Then I should see "TestingSearchTeaser TestingSearchTeaserReference teaser"
    # Testing Translation Backoffice
    And I visit "/fr"
    And I click the ".container-nav > .menu--header-visitors .ico-search-white" element
    And I fill in "edit-search-api-fulltext" with "TestingSearchTitle"
    And I submit the form with id "views-exposed-form-global-search-page-1"
    Then I should see "TEST FR Breadcrumb title with searched text results for TestingSearchTitle"
    Then I should see "TEST FR Title searched text 5 results for TestingSearchTitle"


