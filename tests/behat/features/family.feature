Feature: Family
  @api @cit @javascript @family
  In order to test out the family

  Scenario: Detail family
    Given I visit "/"
    And I accept all cookies compliance
    When I visit "/energy-storage-solution/storage-converters/grid-modular-indoor"
    Then I should see an "body.taxonomy-family" element
    Then I should see an "#product-reference-section" element

