Feature: Moderation acces

  @api @cit @admin_moderation
  Test of access to the administration page of the moderation states of a content by the administer
 # vendor/bin/phing behat:run -Dbehat.tags=admin_moderation
  Scenario: Test of access to the administration page of the moderation states of a content by the administer
    Given users:
      | name          | mail                     | roles         | password |
      | admin_sofiene | sofiene.chaari@gmail.com | administrator | admin    |
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