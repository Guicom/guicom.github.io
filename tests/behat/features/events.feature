Feature: Events

  @api @cit @javascript @events
  Test if the page events is visible
  # vendor/bin/phing behat:run -Dbehat.tags=events
  # See the example news features.
  Scenario: Events detail
    Given I visit "/event/energy-storage-international-2020"
    Then I should see a "body.node--type-event" element

