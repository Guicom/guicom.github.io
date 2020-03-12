Feature: Menu Products entry
  Test of access to the menu on "Products" menu entry
  #vendor/bin/phing behat:run -Dbehat.tags=menu_products_entry

  Background:
    Given family terms:
      | language | name              |
      | English  | My Product Family |
    And product content:
      | language | title            | status | field_product_family |
      | English  | My test product  | 1      | My Product Family    |

  @api @cit @menu_products_entry @javascript
  Scenario: Test of access to the menu "Products" entry
    Given I am an anonymous user
      When I visit "/"
      And I accept all cookies compliance
    When  I go to "/energy-storage-solution/sirco-vm"
      And I accept all cookies compliance
      And I wait for AJAX to finish
      And I accept all cookies compliance
      And I wait 1 seconds
      And I click the ".we-mega-menu-ul .product > a" element
  #  Then I should see an "[data-icon='energy-storage']" element
  #  And I should see an "[data-icon='power-conversion']" element
  #  And I should see an "[data-icon='metering-monitoring']" element
  #  And I should see an "[data-icon='switching']" element


