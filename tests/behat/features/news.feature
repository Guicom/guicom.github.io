Feature: News

  @api @cit @news @javascript
  Test if the page news is visible
 # vendor/bin/phing behat:run -Dbehat.tags=news
  Scenario: News detail
    Given I visit "/news/socomec-certifie-iso-14001-en-alsace"
    Then I should see a "blockquote" element
    Then I should see a ".paragraph--type--model-text ol" element


