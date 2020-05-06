Feature: Family
  In order to test out the family

  Background:
    Given "family" terms:
      | name                 | parent              | description          | format     | language |
      | Famille niveau un    | <root>              | term one description | plain_text | en       |
      | Famille niveau deux  | Famille niveau un   | term one description | plain_text | en       |
      | Famille niveau trois | Famille niveau deux | term one description | plain_text | en       |
    Given product content:
      | language | title            | status | field_product_family | mderation_state |
      | English  | My test product  | 1      | Famille niveau un, Famille niveau deux, Famille niveau trois | published |

  @api @cit @javascript @family
  Scenario: Family page
    Given I visit "/"
    And I am logged in as a user with the "administrator" role
    And I accept all cookies compliance
    When I visit "/famille-niveau-un/famille-niveau-deux/famille-niveau-trois"
    Then I should see an "body.taxonomy-family" element
    Then I should see an "#product-reference-section" element
    Then I should see the breadcrumb link "Famille niveau un"
    Then I should see the breadcrumb link "Famille niveau deux"
    Then I should see the breadcrumb link "Famille niveau trois"
    When I visit "/famille-niveau-un/famille-niveau-deux/famille-niveau-trois/my-test-product"
    Then I should see the breadcrumb link "Famille niveau un"
#    Then I should see the breadcrumb link "Famille niveau deux"
#    Then I should see the breadcrumb link "Famille niveau trois"
    Then I should see the breadcrumb link "My test product"

