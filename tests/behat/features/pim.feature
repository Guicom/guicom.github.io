@api @cit @javascript
Feature: PIM

  @api @cit @javascript @pim @pim_dico
  Scenario: Dictionnary
    Given I am logged in as a user with the "administrator" role
    And I go to "test/nextpage/characteristics"
    And I should see "Synchronisation is done"
    And The file "characteristics_dictionary.json" exist

  @api @cit @javascript @pim @pim_elementLinks
  Scenario: ElementsAndLinks
    Given I am logged in as a user with the "administrator" role
    And I go to "test/nextpage/element"
    Then I should see "Elements"

  @api @cit @javascript @pim @pim_descendantLinks
  Scenario: DescendantsAndLinks
    Given I am logged in as a user with the "administrator" role
    And I go to "test/nextpage/descendants"
    Then I should see "Elements"
