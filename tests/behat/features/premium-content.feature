Feature: Premium Content
  In order to test out premium contents

  # ./vendor/bin/phing behat:run -Dbehat.tags=premium_content

  @api @cit @javascript @premium_content @premium_content_landing_page
  Scenario: Pardot form
    Given landing_page content:
      | language | title                                                               | field_pardot_form_url                            | status | moderation_state |
      | English  | Comment garantir la continuite dalimentation des blocs operatoires  | https://go.socomec.com/l/86922/2019-08-21/62nvkw | 1      | published        |
    And I am not logged in
    And I visit "/"
    And I accept all cookies compliance
    When I visit "/landing-page-premium/whitepaper/comment-garantir-la-continuite-dalimentation-des-blocs-operatoires"
    And I accept all cookies compliance
    And I wait 2 seconds
    Then I click the ".ancher-button a" element
    Given I switch to the iframe "pardot-iframe"
    And I wait 2 seconds
    And I click the "#86922_82642pi_86922_82642_866570_866570 + label" element
    And I fill in "86922_82644pi_86922_82644" with "Essoltani"
    And I fill in "86922_82646pi_86922_82646" with "Guillaume"
    And I fill in "86922_82648pi_86922_82648" with "Developer"
    And I fill in "86922_82650pi_86922_82650" with "Actency"
    And I select "France" from "86922_82652pi_86922_82652"
    And I fill in "86922_82656pi_86922_82656" with "guillaume.essoltani@actency.fr"
#    And I click the ".submit input" element
    And I switch to the main windows

  @api @cit @javascript @premium_content @premium_content_thank_you_page
  Scenario: Thank you page
    #Given thank_you_page content:
    #  | language | title                                                               | status |
    #  | English  | Comment garantir la continuite dalimentation des blocs operatoires  | 1      |
    Given I am not logged in
    And I visit "/"
    And I accept all cookies compliance
    When I am on "/comment-garantir-la-continuite-dalimentation-des-blocs-operatoires/thank-you"
    Then the response status code should be 403
