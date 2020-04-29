@api @cit @javascript
Feature: Products
  In order to test out the products

  @api @cit @javascript @product @product_detail
  Scenario: Detail product
    Given product_reference content:
      | language | title                    | status | moderation_state |
      | English  | 22003016-SIRCO MV 3X160A | 1      | published        |
    And optional_related_content content:
      | language | title   | status | moderation_state | field_orc_text |
      | English  | My ORC  | 1      | published        | ORC text       |
    And product content:
      | language | title             | status | field_product_family | moderation_state | field_product_reference   | field_associated_products | field_product_orc_content | field_product_teaser |
      | English  | My other product  | 1      | My Product Family    | published        | 22003016-SIRCO MV 3X160A  |                           |                           | Teaser               |
      | English  | My test product   | 1      | My Product Family    | published        | 22003016-SIRCO MV 3X160A  | My other product          | My ORC                    | Teaser               |
    And I am logged in as a user with the "administrator" role
    And I go to "admin/content"
    And I click "Edit" in the "My test product" row
    And I fill in "field_json_product_data[0][value]" with "{\"Marketing\":{\"group_name\":\"Marketing\",\"value\":{\"DC_P_FUNCTIONS\":{\"id\":\"DC_P_FUNCTIONS\",\"type\":\"TXTLONG\",\"value\":\"Value\"},\"DC_P_UNIQUE_VALUE_PROPOSAL\":{\"id\":\"DC_P_UNIQUE_VALUE_PROPOSAL\",\"type\":\"TXTLONG\",\"value\":\"Value\"}}}}"
    And I click the "a[href='#edit-group-webmastering']" element
    And I wait 1 seconds
    And I click the "#edit-group-multiline" element
    And I wait 1 seconds
    And I click the "#edit-field-product-multiline li.dropbutton-toggle button" element
    And I press the "field_product_multiline_model_text_add_more" button
    And I fill in "field_product_multiline[0][subform][field_title][0][value]" with "Text"
    And I press "edit-submit"
    And I wait 120 seconds
    And I go to "admin/content"
    And I click "Edit" in the "22003016-SIRCO MV 3X160A" row
    And I set the dummy json data on the reference
    Then I am an anonymous user
    And I visit "/"
    And I wait 1 seconds
    And I accept all cookies compliance
    And I wait 1 seconds
    When I visit "/my-product-family/my-test-product"
    And I wait 120 seconds
    Then I should see an "body.node--type-product" element
    Then I should see an "#product-info-section" element
    Then I should see an "#product-reference-section" element
    Then I should see an "#product-avantage-section" element
    Then I should see an "#product-multiline-section" element
    Then I should see an "#product-associate-section" element
    Then I should see an "#assistance-section" element
    Then I should see an "#product-orc-section" element
    Then I click the "#product-reference-table tr td a" element
    And I should see an ".node--type-product-reference" element

  # ./vendor/bin/phing behat:run -Dbehat.tags=product-news
#  @product-news @api @cit @javascript
#  Scenario: New behaviour
#    Given users:
#      | name              | mail                | roles     | password |
#      | webmaster_sofiene | webmaster@gmail.com | webmaster | admin    |
#    And I am logged in as "webmaster_sofiene"
#    When I visit "/node/10/edit"
#    And I click the ".horizontal-tab-button-2 a" element
#    And I click the ".field--type-entity-reference.field--name-update-new-statut input" element
#    And I fill in "update_new_statut[form][inline_entity_form][update_timestamp][0][value][date]" with date "now" in the format "Y-m-d"
#    And I fill in "update_new_statut[form][inline_entity_form][update_timestamp][0][value][time]" with date "+10 second" in the format "G:i:s a"
#    And I select "New" from "update_new_statut[form][inline_entity_form][field_product_new]"
#    And I click the ".field--name-update-new-statut .ief-entity-submit " element
#    And I click the "#edit-submit" element
#    And I should see an ".field-name-field-product-new" element
#    When I visit "/energy-storage-solution"
#    Then I should see an ".product-wrapper li:first-child .field-name-update-new-statut" element
#    And I wait 10 seconds
#    Then I run drush "cron"
#    When I visit "/energy-storage-solution/sirco-vm"
#    Then I should not see an ".field-name-field-product-new" element
#    When I visit "/energy-storage-solution"
#    Then I should not see an ".product-wrapper li:first-child .field-name-update-new-statut" element
