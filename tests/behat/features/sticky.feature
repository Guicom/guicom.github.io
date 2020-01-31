Feature: [STICKY/SHARE] Tests Behat

  @api @cit @sticky @javascript
  Checking if the share's buttons are visible
#  vendor/bin/phing behat:run -Dbehat.tags=sticky
#  ticket JIRA: SOCSOB-789
  Scenario: SHARE tools
    Given I visit "/news/efficiently-unleash-cross-media-value-proposition-organically-grow"
    And I click the ".eu-cookie-compliance-default-button" element
    And I wait 2 seconds
#  Lien Mail ouvre vers outil de mail interne (comportement standard AddToAny)
    Then I should see a ".a2a_button_email.share-button" element
#  Lien “Lien de page” permettant de copier le lien de la page (comportement standard AddToAny)
    Then I should see a ".a2a_button_copy_link.share-button" element
#  Lien “Print” permettant d’imprimer la page web (comportement standard AddToAny)
    Then I should see a ".a2a_button_print.share-button" element


