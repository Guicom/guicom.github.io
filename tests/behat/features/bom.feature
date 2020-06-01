Feature: [BOM] Tests Behat

  @api @cit @bom @javascript
  Checking Bom functionality
  #  vendor/bin/phing behat:run -Dbehat.tags=bom
  #  ticket JIRA: SOCSOB-547
  Scenario: BOM - Backoffice administration
    Given I am logged in as a user with the "administrator" role
    And I accept all cookies compliance
    And I visit "/admin/config/socomec/wishlist/settings"
    Then I should see "ENGLISH Page title"
    And I press "Settings"
    Then I should see "Wishlist lifetime"
    Then I should see "PDF disclaimer"
    And I fill in "edit-page-title-en" with "TEST BOM PAGE TITLE"
    And I fill in "edit-pdf-disclaimer" with "TEST BOM PDF TEASER"
    And I press the "Save configuration" button
    Then I should see "The configuration options have been saved."
    And I visit "/my-wishlist"
    Then I should see "TEST BOM PAGE TITLE"
    #And I visit "/wishlist/export/pdf"

  @api @cit @bom @javascript
  #  vendor/bin/phing behat:run -Dbehat.tags=bom
  #  ticket JIRA: SOCSOB-547
  Scenario: BOM - Front
    Given I am logged in as a user with the "administrator" role
    And I accept all cookies compliance
    And product_reference content:
      | Language | title                     | field_extid       | field_product_status | field_reference_name   | field_reference_ref   | status |
      | English  | Product reference test 01 | EXTID_TEST_REF_01 | Discontinued         | Test reference name 01 | Test reference ref 01 | 1      |
      | English  | Product reference test 02 | EXTID_TEST_REF_02 | Discontinued         | Test reference name 02 | Test reference ref 02 | 1      |
      | English  | Product reference test 03 | EXTID_TEST_REF_03 | Discontinued         | Test reference name 03 | Test reference ref 03 | 1      |
    Then I am an anonymous user
    And I accept all cookies compliance
    Then I visit "wishlist/add/EXTID_TEST_REF_01"
    And I visit "wishlist/add/EXTID_TEST_REF_02"
    And I visit "wishlist/add/EXTID_TEST_REF_03"
    Then I visit "/my-wishlist"
    And I should see "You are not connected. Please, Sign in or create an account to keep your selections"
    And I should see an "#DataTables_Table_0_filter" element
    And I should see "Actions"
    And I should see "Ask for pricing"
    And I should see "img"
    And I should see "Model"
    And I should see "Reference"
    And I should see "Main specifications"
    And I should see "Qty"
    And I should see "Product reference test 01"
    And I should see "Product reference test 02"
    And I should see "Product reference test 03"
    And I should see "Test reference ref 01"
    And I should see "Test reference ref 02"
    And I should see "Test reference ref 03"
    And I should see "Product reference test 01"
    And I should see "Product reference test 02"
    And I should see "Product reference test 03"
    And I fill in "edit-quantity-extid-test-ref-01" with "1"
    And I fill in "edit-quantity-extid-test-ref-01" with "15"
    And I wait 1 seconds
    Then I visit "/my-wishlist"
    And the "edit-quantity-extid-test-ref-01" field should contain "15"
    And I wait for AJAX to finish
    And I click the "#wishlist_form_content_wrapper .form-item-wishlist-action-extid-test-ref-02-extid-test-ref-02.form-check-label" element
    And I click Actions
    And I should see "Export to an XLS file"
    And I should see "Export to an PDF file"
    And I should see "Export to an CSV file"
    And I should see "Remove selected"
    And I press "Remove selected"
    And I press "Yes"
    And I wait for AJAX to finish
    And I should see "1 item(s) deleted."
    And I should not see "Test reference ref 02"
    And I click the "#wishlist_form_content_wrapper .form-item-select-all-select.form-check-label" element
    And I click Actions
    And I press "Remove selected"
    And I press "Yes"
    And I wait for AJAX to finish
    And I should see "2 item(s) deleted."
    And I should not see "Test reference ref 01"
    And I should not see "Test reference ref 03"
