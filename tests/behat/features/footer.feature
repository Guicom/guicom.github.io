@api @cit @javascript
Feature: Footer
  In order to test out the global layout footer

  Background:
    And I am logged in as a user with the "administrator" role

  @api @cit @javascript @footer
    # vendor/bin/phing behat:run -Dbehat.tags=footer
    # See the footer element.
  Scenario: Global layout footer
    And I go to "/"
    # global footer
    And I should see an ".site-footer" element
    # menu placement
    And I should see an "#block-socomec-footer" element
    # social menu block
    And I should see an "#block-socialmenufooter" element
    # global sub footer
    And I should see an ".site-footer__bottom" element
    # language selector block
    And I should see an "#block-dropdownlanguage" element
    # sub footer menu block
    And I should see an "#block-socomec-footer-legal-mentions" element
    # Copyright block
    And I should see an "#block-copyright" element
    # backtotop block
    And I should see an "#block-backtotop" element
    And I wait 10 seconds
