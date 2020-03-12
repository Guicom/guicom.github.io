Feature: Family
  In order to test out the family

  Background:
    Given family terms:
      | language | name              |
      | English  | My Product Family |
    And product content:
      | language | title            | status | field_product_family |
      | English  | My test product  | 1      | My Product Family    |

  @api @cit @javascript @family
  Scenario: Family page
    Given I visit "/"
    And I accept all cookies compliance
    When I visit "/my-product-family"
    Then I should see an "body.taxonomy-family" element
    Then I should see an "#product-reference-section" element

