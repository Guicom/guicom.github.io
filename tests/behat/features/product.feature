@api @cit @javascript
Feature: Products
  In order to test out the products

  @product-detail @api @cit @javascript
  Scenario: Detail product
    Given product content:
      | language | title            | status | field_product_family | moderation_state |
      | English  | My test product  | 1      | My Product Family    | published        |
    And I visit "/"
    And I accept all cookies compliance
    When I visit "/my-product-family/my-test-product"
    Then I should see an "body.node--type-product" element
    Then I should see an "#product-info-section" element
    Then I should see an "#product-reference-section" element
    Then I should see an "#product-avantage-section" element
    Then I should see an "#product-multiline-section" element
    Then I should see an "#product-associate-section" element
    Then I should see an "#assistance-section" element
    Then I should see an "#product-orc-section" element
    Then I click the "#product-reference-table tr td" element
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
