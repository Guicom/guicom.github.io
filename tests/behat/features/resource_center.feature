Feature: [Resource center] Tests Behat
  #vendor/bin/phing behat:run -Dbehat.tags=resource_center
  Background:
    Given I am logged in as a user with the "administrator" role
    And resource_type terms:
      | language | name        |
      | English  | MyResourceType |
    And product content:
      | language | title                    | status | moderation_state |
      | English  | TestBehatProductTitle    | 1      | published        |
      | English  | TestBehatProductTwoTitle | 1      | published        |
    And product_reference content:
      | language | title                             | field_reference_ref             | status | moderation_state |
      | English  | TestBehatProductReferenceTitle    | TestBehatProductReferenceRef    | 1      | published        |
      | English  | TestBehatProductReferenceTwoTitle | TestBehatProductReferenceTwoRef | 1      | published        |
    And resource content:
      | language | title                     | field_res_original_title          | field_res_reference     | field_res_resource_type | field_res_product_ref    | field_res_product_reference_ref   | status | moderation_state |
      | English  | TestBehatResourceTitle    | TestBehatResourceOriginalTitle    | TestBehatResourceRef    | MyResourceType          | TestBehatProductTitle    | TestBehatProductReferenceTitle    | 1      | published        |
      | English  | TestBehatResourceTwoTitle | TestBehatResourceTwoOriginalTitle | TestBehatResourceTwoRef | MyResourceType          | TestBehatProductTwoTitle | TestBehatProductReferenceTwoTitle | 1      | published        |
    Then I visit "/admin/config/search/search-api/index/resources"
    And I press "Index now"

  @api @cit @resource_center @javascript
  Checking Bom functionality
  #  vendor/bin/phing behat:run -Dbehat.tags=resource_center
  #  ticket JIRA: SOCSOB-1007
  Scenario: [Resource center] Link a resource to product or reference level
    Given I am an anonymous user
    When I visit "/en/resource-center"
    And I accept all cookies compliance
     # Testing Referenced resource content
    Then I should see "TestBehatResourceOriginalTitle"
    Then I should see "TestBehatResourceTwoOriginalTitle"
    # Testing product facets.
    Then I click the "#block-referencetoproduct button" element
    And I should see "TestBehatProductTitle"
    And I should see "TestBehatProductTwoTitle"
    # Testing product facets action.
    And I click on the text "TestBehatProductTitle" in "#block-referencetoproduct ul.dropdown-menu > li > a > span.text" element
    And I should see "TestBehatResourceOriginalTitle"
    And I should not see "TestBehatResourceTwoOriginalTitle"
    Then I visit "/en/resource-center"
    And I click the "#block-referencetoproduct button" element
    And I click on the text "TestBehatProductTwoTitle" in "#block-referencetoproduct #mCSB_3 ul.dropdown-menu > li a span.text" element
    And I should see "TestBehatResourceTwoOriginalTitle"
    And I should not see "TestBehatResourceOriginalTitle"
    # Testing product-reference field_reference_ref index
    Then I visit "/en/resource-center"
    And I fill in "query" with "TestBehatProductReferenceRef"
    And I submit the form with id "views-exposed-form-resource-center-page-1"
    And I should see "TestBehatResourceOriginalTitle"
    And I should not see "TestBehatResourceTwoOriginalTitle"
    Then I visit "/en/resource-center"
    And I fill in "query" with "TestBehatProductReferenceTwoRef"
    And I submit the form with id "views-exposed-form-resource-center-page-1"
    And I should see "TestBehatResourceTwoOriginalTitle"
    And I should not see "TestBehatResourceOriginalTitle"

