Feature: Events
  #./vendor/bin/phing behat:run -Dbehat.tags=events
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
    # ./vendor/bin/phing behat:run -Dbehat.tags=events_detail
    # See the example event.
    # ticket(s) SOCSOB-445
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
    # ./vendor/bin/phing behat:run -Dbehat.tags=events_lp
    # Check if the filter working with the last element.
    # ticket(s) SOCSOB-752
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

  @api @cit @javascript @events @events_listing
    # ./vendor/bin/phing behat:run -Dbehat.tags=events_listing
    # L’EVENT promu à venir s'affiche dans le Hero de la listing EVENT.
    # ticket(s) SOCSOB-755
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
    # ./vendor/bin/phing behat:run -Dbehat.tags=events_permission_add
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
    # ./vendor/bin/phing behat:run -Dbehat.tags=events_permission_edit
    # ticket(s) SOCSOB-806
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
    # ./vendor/bin/phing behat:run -Dbehat.tags=events_permission_revision
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
    # ./vendor/bin/phing behat:run -Dbehat.tags=events_permission_add_term
    # ticket(s) SOCSOB-806
  Scenario Outline: The webmaster role can add a new term.
    Examples:
      | role          | message                                    |
      | webmaster     | Add term                                   |
      | contributor   | Access denied                              |
    Given I am logged in as a "<role>"
    And I accept all cookies compliance
    And I visit "/admin/structure/taxonomy/manage/event_thematic/overview"
    Then I should see the text "<message>"


  @api @cit @javascript @events @events_addcalendar
    # ./vendor/bin/phing behat:run -Dbehat.tags=events_addcalendar
    # Check if the filter working with the first element.
    # ticket(s) SOCSOB-806
  Scenario: Events check for calendar items
    Given I visit "/"
    And I accept all cookies compliance
    When I visit "/event/mytestevent"
    Then I should see "Add to Calendar"
    And I click the ".atcb-link" element
    Then I should see "Google Calendar" in the ".atcb-list" element
    Then I should see "iCalendar" in the ".atcb-list" element
    Then I should see "Outlook Online" in the ".atcb-list" element
    Then I should see "Yahoo! Calendar" in the ".atcb-list" element

  @api @cit @javascript @events @events_cta_multiligne
    # ./vendor/bin/phing behat:run -Dbehat.tags=events_cta_multiligne
    # Check if the cta external link and download rendering correctly and multiligne rendering
    # ticket(s) SOCSOB-445
  Scenario: Events check for external link and download link
    Given I am logged in as a user with the "administrator" role
    When I visit "admin/content"
    And I click "Edit" in the "MyTestEvent" row
    And I click the "#field-event-content-add-more-wrapper li.dropbutton-toggle button" element
    And I press the "field_event_content_model_text_add_more" button
    And I wait 4 seconds
    And I fill in "field_event_content[0][subform][field_title][0][value]" with "Text title"
    And I fill in "field_event_cta_external_link[0][uri]" with "http://google.com"
    And I fill in "field_event_cta_external_link[0][title]" with "GO TO LINK"
    And I select "New window (_blank)" from "field_event_cta_external_link[0][options][attributes][target]"
    And I fill in "field_event_cta_download[0][uri]" with "http://google.com"
    And I fill in "field_event_cta_download[0][title]" with "GO TO DWNLD"
    And I select "New window (_blank)" from "field_event_cta_download[0][options][attributes][target]"
    And I press the "edit-submit" button
    Then I visit "/event/mytestevent"
    And I should see an ".multiline-content" element
    And I should see an ".paragraph--type--model-text" element
    And I should see an ".field--name-field-event-cta-download" element
    And I should see an ".field--name-field-event-cta-external-link" element
    And I wait 10 seconds
