Feature: Family
  @api @cit @javascript @family
  In order to test out the family

  Scenario: Detail family
    When I visit "/products/energy-storage-solution/storage-converters/grid-modular-indoor"
    Then I click the "#popup-buttons .agree-button" element
    Then I should see an "body.taxonomy-family" element
    Then I should see an "#product-reference-section" element

