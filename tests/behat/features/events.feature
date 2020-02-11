Feature: Events

  @api @cit @javascript @events
  Test if the page events is visible
  # ./vendor/bin/phing behat:run -Dbehat.tags=events
  # See the example news features.
  Scenario: Events detail
    Given I visit "/event/energy-storage-international-2020"
    Then I should see a "body.node--type-event" element

  @api @cit @javascript @events
    # Check if the filter working with the last element.
  Scenario: Events Landing page
    Given I visit "/events"
    And I click the "select[data-drupal-facet-id='event_type_taxonomy_term_name'] option:last-child" element
    And I wait 2 seconds
    Then I should see "Event #2" in the ".view-id-events" element

  @api @cit @javascript @events
    # Check if the filter working with the first element.
  Scenario: Events Landing page
    Given I visit "/events"
    And I click the "select[data-drupal-facet-id='event_type_taxonomy_term_name'] option:last-child" element
    And I wait 2 seconds
    #Then I should not see "Energy Storage International 2020"
    Then I should not see "Conference"
    Then I should not see "Energy Storage International 2020" in the ".view-id-events" element

     # L’EVENT promu à venir s'affiche dans le Hero de la listing EVENT.
  Scenario Outline: Bloc Hero for the events page.
    When I visit "/events"
    Then I should see an "<element>" element
    Then I should see "<text>"
    Examples:
      | element          | text                                                                               |
      | body.path-events | Iterative approaches to establish a new normal that has evolved from generation x. |
