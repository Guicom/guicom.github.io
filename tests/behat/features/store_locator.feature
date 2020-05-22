Feature: [STORE_LOCATOR] Tests Behat

  @api @cit @javascript @store_locator @store_locator_data_model
  Checking store_locator
  # vendor/bin/phing behat:run -Dbehat.tags=store_locator_data_model
  # ticket JIRA: SOCSOB-927
  # Testing content datas with webmaster role
  Scenario: contenu_location backoffice content datas
    Given I am logged in as a user with the "webmaster" role
    And I accept all cookies compliance
    And I visit "/en/node/add/contenu_location"
    # Title is mandatory
    Then I should see "Title"
    Then I should see an "input#edit-title-0-value[required='required']" element
    # Language selector
    Then I should see "Language"
    Then I should see an "select#edit-langcode-0-value" element
    # Continent of Sales is mandatory
    Then I should see "Continent of Sales"
    Then I should see an "select#edit-field-location-continent[required='required']" element
    # Area of Sales is mandatory
    Then I should see "Area of Sales"
    Then I should see an "select#edit-field-location-area[required='required']" element
    # Subarea Of Sales
    Then I should see "Subarea Of Sales"
    Then I should see an "select#edit-field-location-subarea" element
    Then I should not see an "select#edit-field-location-subarea[required='required']" element
    # Activity is mandatory
    Then I should see "Activity"
    Then I should see an "select#edit-field-location-activity[required='required']" element
    # Company is mandatory
    Then I should see "Company"
    Then I should see an "input#edit-field-location-company-0-value[required='required']" element
    # Name Contact
    Then I should see "Name Contact"
    Then I should see an "input#edit-field-location-name-contact-0-value" element
    Then I should not see an "input#edit-field-location-name-contact-0-value[required='required']" element
    # Firstname
    Then I should see "Firstname"
    Then I should see an "input#edit-field-location-firstname-0-value" element
    Then I should not see an "input#edit-field-location-firstname-0-value[required='required']" element
    # ADDRESS is mandatory
    Then I should see "ADDRESS"
    Then I should see an ".form-item-field-location-address-0-address-country-code > label.form-required" element
    # Telephone is mandatory
    Then I should see "Telephone"
    Then I should see an "input#edit-field-location-telephone-0-value[required='required']" element
    # Fax
    Then I should see "Fax"
    Then I should see an "input#edit-field-location-fax-0-value" element
    Then I should not see an "input#edit-field-location-fax-0-value[required='required']" element
    # Website is not mandatory
    Then I should see "Website"
    Then I should see an "input#edit-field-location-website-0-uri" element
    Then I should not see an "input#edit-field-location-website-0-uri[required='required']" element
    # Type is mandatory
    Then I should see "Type"
    Then I should see an "input#edit-field-location-type-0-target-id[required='required']" element
    # Email is mandatory
    Then I should see "Email"
    Then I should see an "input#edit-field-location-email-0-value" element
    # Website is mandatory if type = Reseller
    And I fill in "Type" with "Reseller"
    And I wait for AJAX to finish
    Then I should see "Website"
    Then I should see an ".field--name-field-location-website[required='required']" element

  @api @cit @javascript @store_locator @store_locator_contrib
  # vendor/bin/phing behat:run -Dbehat.tags=store_locator_contrib
  # ticket JIRA: SOCSOB-502
  # Testing webmaster contribution
  Scenario: contenu_location backoffice contribution
    Given I am logged in as a user with the "webmaster" role
    And I accept all cookies compliance
    And I visit "/en/node/add/contenu_location"
    And I fill in "Title" with "Title content location"
    And I select "Europe" from "Continent of Sales"
    And I select "-France" from "Area of Sales"
    And I select "Energy storage" from "Activity"
    And I fill in "Company" with "Company content location"
    And I select "France" from "Country"
    And I wait for AJAX to finish
    And I fill in "Street address" with "Street address content location"
    And I fill in "Postal code" with "67000"
    And I fill in "City" with "Strasbourg"
    And I fill in "Telephone" with "0600000000"
    And I fill in "Website" with "http://www.google.fr"
    And I fill in "Type" with "Reseller"
    And I press "Save"
    Then I should see "Edit"
    And I click Edit
    Then I should see "Title content location"
    Then I should see "View"
    Then I should see "Delete"
    Then I should see "Revisions"
    Then I should see "Translate"
    Then I should not see "Current state : Published"

  @api @cit @javascript @store_locator @store_locator_listing
  # vendor/bin/phing behat:run -Dbehat.tags=store_locator_listing
  # ticket JIRA: SOCSOB-839
  # Testing Front
  Scenario: contenu_location Front list
    Given I am logged in as a user with the "webmaster" role
    And I accept all cookies compliance
    # We create en location_type terme
    And location_type terms:
      | name         | weight |
      | TestingType  | 10     |
    And I visit "/en/node/add/contenu_location"
    And I fill in "Title" with "Test locator 1"
    And I select "Europe" from "Continent of Sales"
    And I select "-France" from "Area of Sales"
    And I select "--Alsace" from "Subarea Of Sales"
    And I select "Energy storage" from "Activity"
    And I fill in "Company" with "Test company 1"
    And I fill in "Name Contact" with "Test name contact 1"
    And I fill in "Firstname" with "Test Firstname 1"
    And I select "France" from "Country"
    And I wait for AJAX to finish
    And I fill in "Street address" with "45 avenue de colmar"
    And I fill in "Postal code" with "67000"
    And I fill in "City" with "Strasbourg"
    And I fill in "Telephone" with "0600000000"
    And I fill in "Website" with "http://www.google.fr"
    And I fill in "Type" with "TestingType"
    And I fill in "Email" with "email@gmail.com"
    And I press "Save"
    And I am logged in as a user with the "administrator" role
    And I visit "/admin/config/search/search-api/index/location"
    And I press "Index now"
    Then I am an anonymous user
    # Testing event result
    And I visit "/en/where-to-buy?f%5B0%5D=type_store_locator%3ATestingType"
    And I accept all cookies compliance
    Then I should see "1 contact"
    Then I should see "Test company 1" in the ".field--name-field-location-company" element
    Then I should see "ENERGY STORAGE" in the ".field--type-entity-reference" element
    Then I should see "0600000000" in the ".field--type-telephone" element
    Then I should see "Send a Mail" in the "a[href='mailto:email@gmail.com']" element
    Then I should see "TestingType" in the ".field--name-field-location-type" element
    Then I should see "45 avenue de colmar 67000 Strasbourg"
    Then I should see "Access plan" in the "a[href='https://google.com/maps?q=45%20avenue%20de%20colmar%20Strasbourg%2067000%20FR']" element

  @api @cit @javascript @store_locator @store_locator_import
  # vendor/bin/phing behat:run -Dbehat.tags=store_locator_import
  # ticket JIRA: SOCSOB-839
  # Testing Export
  Scenario: Testing export
    Given I am logged in as a user with the "administrator" role
    And I accept all cookies compliance
    # We create en location_type terme
    And I visit "/en/admin/config/socomec/sales_locations/import_csv_file"
    When I attach the file "csv/export-sales-locations-test.csv" to "edit-file-csv-upload"
    And I press "Submit"
    And I visit "/admin/config/search/search-api/index/location"
    And I press "Index now"
    Then I am an anonymous user
    # Testing event result
    And I visit "/en/where-to-buy?f%5B0%5D=type_store_locator%3ATestingExportType"
    And I accept all cookies compliance
    Then I should see "3 contacts"
    Then I should see "TEST IMPORT 1 COMPANY"
    Then I should see "TEST IMPORT 2 COMPANY"
    Then I should see "TEST IMPORT 3 COMPANY"
