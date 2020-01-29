Feature: Products
  @api @cit @javascript
  In order to test out the products

  Scenario: Detail product
    When I visit "/energy-storage-solution/sirco-vm"
    Then I should see an "body.node--type-product" element
    Then I should see an "#product-info-section" element
    Then I should see an "#product-reference-section" element
    Then I should see an "#product-avantage-section" element
    Then I should see an "#product-multiline-section" element
    Then I should see an "#product-associate-section" element
    Then I should see an "#assistance-section" element
    Then I should see an "#product-orc-section" element

