Feature: Events
  #vendor/bin/phing behat:run -Dbehat.tags=events

  Background:
    And I am logged in as a user with the "administrator" role
    And event_type terms:
      | language | name        |
      | English  | MyEventType |
    And event content:
      | language | title       | status | field_event_country | field_event_place | field_event_type | field_event_teaser | field_add_to_calendar | moderation_state |
      | English  | MyTestEvent | 1      | FR                  | Zenith Strasbourg | MyEventType      | Nice event         | 1                     | published        |
    And I go to "admin/content"
    # needed for next step
    And I click "Edit" in the "MyTestEvent" row
    And I set the event date
    And I visit "/admin/config/search/search-api/index/events"
    And I press "Index now"

  @api @cit @javascript @events @events_detail
  # ./vendor/bin/phing behat:run -Dbehat.tags=events
  # See the example event.
  Scenario: Events detail
    Given I visit "/"
    And I accept all cookies compliance
    When I visit "/event/mytestevent"
    Then I should see a "body.node--type-event" element
    And I should see "MyTestEvent"
    Then I should see the breadcrumb link "Home"
    Then I should see the breadcrumb link "Event"
    Then I should see the breadcrumb link "MyTestEvent"

  @api @cit @javascript @events @events_lp
    # Check if the filter working with the last element.
  Scenario: Events Landing page
    #Then I am an anonymous user
    And I visit "/"
    And I accept all cookies compliance
    When I visit "/events"
    Then I should see "MyTestEvent" in the ".view-id-events" element
    And I wait 2 seconds
    Then I visit "/events?f%5B0%5D=event_type_taxonomy_term_name%3AExhibition"
    And I wait 2 seconds
    Then I should not see "MyTestEvent" in the ".view-id-events" element

  @api @cit @javascript @events
     # L’EVENT promu à venir s'affiche dans le Hero de la listing EVENT.
  Scenario Outline: Bloc Hero for the events page.
    Given I visit "/"
    And I accept all cookies compliance
    When I visit "/events"
    Then I should see an "<element>" element
    Then I should see "<text>"
    Examples:
      | element          | text                                                                               |
      | body.path-events | Iterative approaches to establish a new normal that has evolved from generation x. |

  @api @cit @javascript @events @events_permission @events_permission_add
     # Verifier les permissions pour ajouter un event.
    # ticket(s) SOCSOB-806
  Scenario Outline: Check add permissions for the specific roles.
    Examples:
      | role          | message                                    |
      | webmaster     | Create Event                               |
      | contributor   | Create Event                               |
      | authenticated | You are not authorized to access this page |
    Given I am logged in as a "<role>"
    And I accept all cookies compliance
    And I visit "/node/add/event"
    Then I should see the text "<message>"

  @api @cit @javascript @events @events_permission @events_permission_edit
  Scenario Outline: Check edit permissions for the specific roles.
    Examples:
      | role          | row                      | message                  |
      | webmaster     | MyTestEvent              | MyTestEvent              |
      #| contributor   | MyContributorTestEvent   | MyContributorTestEvent   |
    Given I am logged in as a user with the "<role>" role
    And event content:
      | language | title | status | field_event_country | field_event_place | field_event_type | field_event_teaser | moderation_state |
      | English  | <row> | 1      | FR                  | Zenith Strasbourg | MyEventType      | Nice event         | published        |
    When I go to "admin/content"
    And I click "Edit" in the "<row>" row
    Then I should see the text "<message>"

  @api @cit @javascript @events @events_permission @events_permission_revision
     # Verifier la permission revision pour le CT event.
    # ticket(s) SOCSOB-806
  Scenario Outline: Check revision permissions for the specific roles.
    Examples:
      | role          | row                    | message          |
      #| webmaster     | MyTestEvent            | Revisions        |
      #| contributor   | MyContributorTestEvent | Revisions        |
    Given I am logged in as a "<role>"
    And I accept all cookies compliance
    And I click "Edit" in the "<row>" row
    Then I should see the text "<message>"

  @api @cit @javascript @events @events_permission @event_permission_add_term
  Scenario Outline: The webmaster role can add a new term.
    Examples:
      | role          | message                                    |
      | webmaster     | Add term                                   |
      | contributor   | Access denied                              |
    Given I am logged in as a "<role>"
    And I accept all cookies compliance
    And I visit "/admin/structure/taxonomy/manage/event_thematic/overview"
    Then I should see the text "<message>"


  # @todo: ajouter une brique pour voir le bouton add to calendar
  @api @cit @javascript @events @wip
    # Check if the filter working with the first element.
  Scenario: Events check for calendar items
    Given I visit "/"
    And I accept all cookies compliance
    When I visit "/event/mytestevent"
    Then I should see "Add to Calendar"
    #Then I should see "Google Calendar" in the "u.atcb-list" element
    #Then I should see "iCalendar" in the ".atcb-list" element
    #Then I should see "Outlook Online" in the ".atcb-list" element
    #Then I should see "Yahoo! Calendar" in the ".atcb-list" element
