Feature: Footer
  #vendor/bin/phing behat:run -Dbehat.tags=footer
  In order to test out the global layout footer

  @api @cit @javascript @footer
    # Ticket jira : SOCSOB-245
    # vendor/bin/phing behat:run -Dbehat.tags=footer
    # See the footer element.
  Scenario: Global layout footer
    Given I am an anonymous user
    Then I go to "/"
    And I accept all cookies compliance
    # global footer
    And I should see an ".site-footer" element
    # menu placement
    Then I should see an "#block-socomec-footer" element
    And I should see "Products" in the "#block-socomec-footer" element
    And I should see an ".col-stack" element
    # social menu block
    Then I should see an "#block-socialmenufooter" element
    And I should see an ".field--name-field-image" element
    And I should see an "a[href='https://www.linkedin.com/company/socomec/']" element
    And I should see an "a[href='https://www.facebook.com/SocomecGroup/']" element
    And I should see an "a[href='https://twitter.com/socomec_group']" element
    And I should see an "a[href='https://www.youtube.com/channel/UCqYisxCze5VfEqjCymYWPxw']" element
    # global sub footer
    And I should see an ".site-footer__bottom" element
    And I should see an ".region-footer-fifth" element
    # language selector block
    Then I should see an "#block-dropdownlanguage" element
    And I click the ".dropdown-language-item" element
    And I should see "FR"
    And I should see "IT"
    And I should see "EN-US"
    And I should see "DE"
    And I should see "PL"
    # sub footer menu block
    Then I should see an "#block-socomec-footer-legal-mentions" element
    And I should see "Legal mention" in the "#block-socomec-footer-legal-mentions" element
    And I should see "Data privacy" in the "#block-socomec-footer-legal-mentions" element
    And I should see "Terms and conditions" in the "#block-socomec-footer-legal-mentions" element
    And I should see "Contact" in the "#block-socomec-footer-legal-mentions" element
    # Copyright block
    Then I should see an "#block-copyright" element
    And I should see "All rights reserved" in the "#block-copyright" element

    # backtotop block
    Then I should see an "#block-backtotop" element
    And I should see "TOP" in the ".field--name-field-link" element
    And I click the ".field--name-field-link" element
    And I wait 10 seconds
