Feature: Default
  @api @cit @javascript
  In order to test out the basics

  Scenario: Homepage is reachable and contains all elements
    Given I am not logged in
    When I am on the homepage
    Then I should see an "body.path-frontpage" element

  @api
  Scenario: Drush is working
    Given I run drush "status"
    Then drush output should contain "socomec"
