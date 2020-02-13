Feature: Events

  @api @cit @javascript @events
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

  @api @cit @javascript @events
     # L’EVENT promu à venir s'affiche dans le Hero de la listing EVENT.
  Scenario Outline: Bloc Hero for the events page.
    When I visit "/events"
    Then I should see an "<element>" element
    Then I should see "<text>"
    Examples:
      | element          | text                                                                               |
      | body.path-events | Iterative approaches to establish a new normal that has evolved from generation x. |

  @api @cit @javascript @events
     # Verifier les permissions pour ajouter un event.
    # ticket(s) SOCSOB-806
  Scenario Outline: Check permissions for the specific roles.
    Examples:
      | role          | message                                    |
      | webmaster     | Create Event                               |
      | contributor   | Create Event                               |
      | authenticated | You are not authorized to access this page |
    Given I am logged in as a "<role>"
    And I visit "/node/add/event"
    Then I should see the text "<message>"

  @api @cit @javascript @events
     # Verifier les permissions pour modifier un event.
    # ticket(s) SOCSOB-806
  Scenario Outline: Check permissions for the specific roles.
    Examples:
      | role          | message                                    |
      | webmaster     | Event #2                                   |
      | contributor   | Event #2                                   |
      | authenticated | You are not authorized to access this page |
    Given I am logged in as a "<role>"
    And I visit "/node/53/edit"
    Then I should see the text "<message>"

  @api @cit @javascript @events
     # Verifier la permission revision pour le CT event.
    # ticket(s) SOCSOB-806
  Scenario Outline: Check permissions for the specific roles.
    Examples:
      | role          | message                                    |
      | webmaster     | revision                                   |
      | contributor   | Access denied |
      | authenticated | You are not authorized to access this page |
    Given I am logged in as a "<role>"
    And I visit "/node/53/revisions"
    Then I should see the text "<message>"

  @api @cit @javascript @events
  Scenario Outline: The webmaster role can add a new term.
    Examples:
      | role          | message                                    |
      | webmaster     | Add term                                   |
      | contributor   | Access denied                                   |
      | authenticated | You are not authorized to access this page |
    Given I am logged in as a "<role>"
    And I visit "/admin/structure/taxonomy/manage/event_thematic/overview"
    Then I should see the text "<message>"


  # @todo: ajouter une brique pour voir le bouton add to calendar
  @api @cit @javascript @events @wip
    # Check if the filter working with the first element.
  Scenario: Events check for calendar items
    Given I visit "/event/event-2"
    #Then I should not see "Energy Storage International 2020"
    Then I should see "Add to Calendar"
    #Then I should see "Google Calendar" in the "u.atcb-list" element
    #Then I should see "iCalendar" in the ".atcb-list" element
    #Then I should see "Outlook Online" in the ".atcb-list" element
    #Then I should see "Yahoo! Calendar" in the ".atcb-list" element
