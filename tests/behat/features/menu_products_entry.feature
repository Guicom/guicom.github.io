Feature: Menu Products entry
  Test of access to the menu on "Products" menu entry
  #vendor/bin/phing behat:run -Dbehat.tags=menu_products_entry

  @api @cit @menu_products_entry @javascript
  Scenario: Test of access to the menu "Products" entry
    Given I am an anonymous user
    When  I go to "/"
    And I accept all cookies compliance
    And I click the ".we-mega-menu-ul > li[data-id='taxonomy_menu.menu_link.family.1'] > a" element
    Then I should see "My Product Family"
  #  Then I should see an "[data-icon='energy-storage']" element
  #  And I should see an "[data-icon='power-conversion']" element
  #  And I should see an "[data-icon='metering-monitoring']" element
  #  And I should see an "[data-icon='switching']" element


