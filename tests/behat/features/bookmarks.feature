Feature: [BOOKMARKS] Tests Behat

  @api @cit @bookmarks @javascript @bookmarks_access
  Access to My Documents Page And header tooltip is set
  # vendor/bin/phing behat:run -Dbehat.tags=bookmarks
  # ticket JIRA: SOCSOB-553
  # CA : I Need link to bookmark page
  # CA : I Need Tooltip on link
  Scenario: Bookmarks - Header link
    Given I am an anonymous user
    And I visit "/en"
    And I accept all cookies compliance
    # I Need link to bookmark page
    Then I should see an ".container-nav > .menu--header-visitors .ico-bookmark-star-white[data-drupal-link-system-path='my-documents']" element
    # Bookmark page exist
    And I visit "/en/my-documents"
    Then I should see "MY DOCUMENTS"
    # I Need Tooltip on link
    Then I should see an ".container-nav > .menu--header-visitors .ico-bookmark-star-white[data-toggle='tooltip']" element
    Then I should see an ".container-nav > .menu--header-visitors .ico-bookmark-star-white[data-html='true']" element
    Then I should see an ".container-nav > .menu--header-visitors .ico-bookmark-star-white[data-original-title='My documents']" element
    Then I should see an ".container-nav > .menu--header-visitors .ico-bookmark-star-white[data-placement='bottom']" element

  @api @cit @bookmarks @javascript @bookmarks_consult
  Consult my documents page Testing Settings and Access
  # vendor/bin/phing behat:run -Dbehat.tags=bookmarks
  # ticket JIRA: SOCSOB-555
  # CA : Setting bookmark page with webmaster role
  # CA : I Need Access to bookmark page
  Scenario: Bookmarks - Page
    And I am logged in as a user with the "webmaster" role
    And I visit "/en/admin/config/socomec/bookmark/settings"
    And I accept all cookies compliance
    Then I should see "Bookmark settings"
    Then I should see "Translate bookmarks settings"
    Then I should see "Page title"
    Then I should see "Message without result"
    And I press "Settings"
    Then I should see "Bookmark lifetime"
    # Setting bookmark page title
    And I fill in "edit-bookmark-page-title" with "My documents test"
    And I press the "Save configuration" button
    And I visit "/en/my-documents"
    Then I should see "MY DOCUMENTS TEST"
    # Setting translation bookmark page title
    And I visit "/admin/config/socomec/bookmark/settings/translate/fr/add"
    And I fill in "Page title" with "My documents French"
    And I press the "Save translation" button
    And I visit "/fr/my-documents"
    Then I should see "MY DOCUMENTS FRENCH"

  @api @cit @bookmarks @javascript @bookmarks_add
  Consult my documents page Testing Settings and Access
  # vendor/bin/phing behat:run -Dbehat.tags=bookmarks
  # ticket JIRA: SOCSOB-555
  # CA : Setting bookmark page with webmaster role
  # CA : I Need Access to bookmark page
  Scenario: Bookmarks - Page
    Given I am logged in as a user with the "webmaster" role
    And I accept all cookies compliance
    And resource content:
      | Language | title            | field_res_original_title | field_res_reference | field_res_resource_type | field_res_downloadable | status | moderation_state |
      | English  | ResourceTitle1   | ResourceTitle1           | TEST_REF_01         | Brochure                | 1                      | 1      | published        |
      | English  | ResourceTitle2   | ResourceTitle2           | TEST_REF_02         | Brochure                | 1                      | 1      | published        |
    And I go to "admin/content"
    And I click "Edit" in the "ResourceTitle1" row
    And I fill in "field_res_remote_file_url[0][url]" with "https://en.unesco.org/inclusivepolicylab/sites/default/files/dummy-pdf_2.pdf"
    And I press "Save"
    And I wait 2 seconds
    And I go to "admin/content"
    And I click "Edit" in the "ResourceTitle2" row
    And I fill in "field_res_remote_file_url[0][url]" with "http://www.africau.edu/images/default/sample.pdf"
    And I press "Save"
    And I wait 2 seconds
    And I bookmark the resource "ResourceTitle1"
    And I bookmark the resource "ResourceTitle2"
    And I visit "/en/my-documents"
    Then I should see "ResourceTitle1"
    And I should see "ResourceTitle2"
