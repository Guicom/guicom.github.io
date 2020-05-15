Feature: Reference_product
  #vendor/bin/phing behat:run -Dbehat.tags=product_reference

  Background:
    And I am logged in as a user with the "administrator" role
    And product_reference content:
      | language | title        | field_teaser      | field_reference_extid | field_reference_ref  | status | moderation_state |
      | English  | behatRefProd | behat teaser test | R_41903013            | 27003011             | 1      | published        |


  @api @cit @javascript @product_reference @product_reference_page
 # vendor/bin/phing behat:run -Dbehat.tags=product_reference_page
  Scenario: Reference page
    Given I am an anonymous user
    When I visit "/behatRefProd"
    And I accept all cookies compliance
    Then I should see a "body.node--type-product-reference" element
    Then I should see the breadcrumb link "Home"
    And I should see "behatRefProd"
    And I should see "behat teaser test"
    And I should see "27003011"


  @api @cit @javascript @product_reference @product_reference_cta
 # vendor/bin/phing behat:run -Dbehat.tags=product_reference_cta
  Scenario: Reference cta
    And I am logged in as a user with the "administrator" role
    And I go to "admin/content"
    And I click "Edit" in the "behatRefProd" row
    And I click the "a[href='#edit-group-webmastering']" element
    And I wait 1 seconds
    And I click the "#edit-field-product-cta-1-add-more-add-more-button-link" element
    And I wait 1 seconds
    And I select "Label: Product reference - Add to my bom" from "field_product_cta_1[0][subform][field_link_paragraph]"
    And I click the "#edit-field-product-cta-2-add-more-add-more-button-link" element
    And I wait 1 seconds
    And I select "Label: Product reference - Find a dealer" from "field_product_cta_2[0][subform][field_link_paragraph]"
    And I click the "#edit-field-product-cta-3-add-more-add-more-button-link" element
    And I wait 1 seconds
    And I select "Label: Product reference - Ask an expert" from "field_product_cta_3[0][subform][field_link_paragraph]"
    And I click the "#edit-field-product-cta-4-add-more-add-more-button-link" element
    And I wait 1 seconds
    And I select "Label: Product reference - Ready to buy" from "field_product_cta_4[0][subform][field_link_paragraph]"
    And I wait 1 seconds
    And I press "edit-submit"
    And I go to "admin/content"
    And I click "behatRefProd" in the "behatRefProd" row

  @api @cit @javascript @product_reference @product_reference_multiligne
 # vendor/bin/phing behat:run -Dbehat.tags=product_reference_multiligne
  Scenario: Reference multiligne
    And I am logged in as a user with the "administrator" role
    And I go to "admin/content"
    And I click "Edit" in the "behatRefProd" row
    And I click the "a[href='#edit-group-webmastering']" element
    And I click the "#edit-field-reference-content li.dropbutton-toggle button" element
    And I press the "edit-field-reference-content-add-more-add-more-button-model-text" button
    And I wait 4 seconds
    And I fill in "field_reference_content[0][subform][field_title][0][value]" with "Text title"
    And I wait 2 seconds
    And I press "edit-submit"
    And I go to "admin/content"
    And I click "behatRefProd" in the "behatRefProd" row

  @api @cit @javascript @product_reference @product_reference_cad
 # vendor/bin/phing behat:run -Dbehat.tags=product_reference_cad
  Scenario: Reference cad
    Given I am an anonymous user
    When I visit "/behatRefProd"
    And I accept all cookies compliance
    Then I should see a "body.node--type-product-reference" element
    And I should see "behatRefProd"
    And I click the ".cta-simple" element
    And I wait 5 seconds
    And I click the "a[href='/en/traceparts/login/27003011/33']" element
    And I wait 5 seconds
    And I click the "a[href='/en/traceparts/register/27003011/33']" element
    And I wait 5 seconds
    And I fill in "email" with "testbehat@test.fr"
    And I fill in "company" with "test behat company"
    And I select "France" from "country"
    And I fill in "zipcode" with "67500"
    And I press "edit-submit"
    And I wait 5 seconds
    And I should see a ".submitted-page" element
    And I wait 5 seconds
    When I visit "/behatRefProd"

  @api @cit @javascript @product_reference @product_reference_characteristic
 # vendor/bin/phing behat:run -Dbehat.tags=product_reference_characteristic
  Scenario: Reference characteristic
    And I am logged in as a user with the "administrator" role
    And I go to "admin/content"
    And I click "Edit" in the "behatRefProd" row
    And I fill in "field_characteristics[0][value]" with "{\"ETIM\":{\"1\":{\"id\":\"DC_R_CLASSEETIM\",\"type\":\"LISTE\",\"value\":[\"EC000216\"],\"libelleDossier\":\"ETIM\",\"label\":\"Classe ETIM\",\"order\":1}}}"
    And I press "edit-submit"
    And I go to "admin/content"
    And I click "behatRefProd" in the "behatRefProd" row
    And I should see a ".product-reference-characteristics" element

  @api @cit @javascript @product_reference @product_reference_nocadmodel
 # vendor/bin/phing behat:run -Dbehat.tags=product_reference_nocadmodel
  Scenario: Reference no cad model
    And I am logged in as a user with the "administrator" role
    And I go to "admin/content"
    And I click "Edit" in the "behatRefProd" row
    And I fill in "field_reference_ref[0][value]" with "666"
    And I press "edit-submit"
    And I go to "admin/content"
    And I click "behatRefProd" in the "behatRefProd" row
    And I should see a "img.default-img-product-reference" element
