Feature: Menu Products entry

@api @cit @menu_products_entry @javascript
Test of access to the menu on "Products" menu entry
#vendor/bin/phing behat:run -Dbehat.tags=menu_products_entry

Scenario: Test of access to the menu "Products" entry
	Given I am an anonymous user
	When  I go to "<front>"
  And I wait for AJAX to finish
  And I accept all cookies compliance
  And I wait 1 seconds
  Then I should not see an "#popup-buttons .agree-button" element
  And I click the "[href='/products']" element
  Then I should see an "[data-icon='energy-storage']" element
  And I should see an "[data-icon='power-conversion']" element
  And I should see an "[data-icon='metering-monitoring']" element
  And I should see an "[data-icon='switching']" element


