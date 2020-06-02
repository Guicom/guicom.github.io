Feature: Moderation acces

  @api @cit @admin_moderation @javascript
  Test of access to the administration page of the moderation states of a content by the administer
  # ./vendor/bin/phing behat:run -Dbehat.tags=admin_moderation
  Scenario: Test of access to the administration page of the moderation states of a content by the administer
    Given users:
      | name          | mail                 | roles         | password |
      | admin_sofiene | sofienaari@gmail.com | administrator | admin    |
    And I am logged in as "admin_sofiene"
    And I visit "/admin/config/workflow/workflows"
    Then I should see the text "Workflows"
    Then I should see the text "Editorial"
    Then I should see the text "Edit"
    And I visit "/admin/config/workflow/workflows/manage/editorial"
    Then I should see the "form" element with the "id" attribute set to "workflow-edit-form" in the "content" region
    Then I should see the text "Draft"
    Then I should see the text "Ready"
    Then I should see the text "Published"
    Then I should see the text "Archived"
    Then I should see the "input" element with the "id" attribute set to "edit-submit" in the "content" region
    Then I should see the "a" element with the "id" attribute set to "edit-delete" in the "content" region

  @api @cit @javascript @webmaster_moderation
  # ./vendor/bin/phing behat:run -Dbehat.tags=webmaster_moderation
  Scenario: Test of access to the administration page of the moderation states of a content by the administer
    Given users:
      | name              | mail                | roles     | password |
      | webmaster_sofiene | webmaster@gmail.com | webmaster | admin    |
    And I am logged in as "webmaster_sofiene"
    And I visit "/node/add/page"
    And I accept all cookies compliance
    # Title
    And I fill in "title[0][value]" with "Basic page"
    # Subtitle
    And I wait 2 seconds
    And I fill in "field_hero[0][subform][field_subtitle][0][value]" with "My subtitle"
    # Teaser
    And I fill in wysiwyg on field "field_hero[0][subform][field_teaser][0][value]" with "My teaser"
    # Image
    And I press the "field_image-media-library-open-button-field_hero-0-subform" button
    And I attach the file "images/example.jpg" to "files[upload]"
    And I wait for AJAX to finish
    And I fill in "media[0][fields][field_media_image][0][alt]" with "Image alt"
    And I click the ".ui-dialog button + button" element
    # Body
    And I fill in wysiwyg on field "body[0][value]" with "My description"
    # Multiline
    And I fill in "field_table[0][subform][field_title][0][value]" with "My title"
    And I fill in wysiwyg on field "field_table[0][subform][field_text][0][value]" with "My text"
    # Content moderation
    Then I should see the "select" element with the "id" attribute set to "edit-moderation-state-0-state" in the "content" region
    Then I press "Save"
    And I wait 5 seconds
    Then I should see "Moderation state Draft"
    Then I should see "Change to"
    Then I should see "Ready"
    Then I press "Apply"
    And I wait 5 seconds
    Then I should see "Published"
