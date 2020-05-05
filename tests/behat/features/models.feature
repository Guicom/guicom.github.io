@api @cit @javascript
Feature: Models
  In oorder to test all the models

  Background:
    Given news content:
      | language | title     | status | moderation_state | field_news_type | field_news_audience | field_news_mommentum | field_teaser | field_news_video | field_country |
      | English  | Test news | 1      | published        | Corporate       | USERS               | Build                | Teaser       | Non              | France        |

  @api @cit @javascript @models
  Scenario: Models testing
    Given I am logged in as a user with the "administrator" role
    And I go to "admin/content"
    And I click "Edit" in the "Test news" row
    # ADD Keyfigure
    And I click the "#edit-field-body-wrapper .paragraphs-add-wrapper li.dropbutton-toggle button" element
    And I press the "field_body_key_figures_add_more" button
    And I fill in "field_body[0][subform][field_figure_1_number][0][value]" with "33"
    And I fill in "field_body[0][subform][field_figure_1_text_line_1][0][value]" with "Text for my kaynumber"
    # ADD CTA
    And I click the "#edit-field-body-wrapper .paragraphs-add-wrapper li.dropbutton-toggle button" element
    And I press the "field_body_model_cta_add_more" button
    And I fill in "field_body[1][subform][field_cta][0][uri]" with "<front>"
    And I fill in "field_body[1][subform][field_cta][0][title]" with "My CTA text"
    # ADD Citation
    And I click the "#edit-field-body-wrapper .paragraphs-add-wrapper li.dropbutton-toggle button" element
    And I press the "field_body_model_citation_add_more" button
    And I fill in "field_body[2][subform][field_citation_content][0][value]" with "Luke, I am your father"
    And I fill in "field_body[2][subform][field_citation_author][0][value]" with "Darth Vader"
    And I fill in "field_body[2][subform][field_citation_author_position][0][value]" with "Dark Lord of The Sith"
    # ADD Text
    And I click the "#edit-field-body-wrapper .paragraphs-add-wrapper li.dropbutton-toggle button" element
    And I press the "field_body_model_text_add_more" button
    And I fill in "field_body[3][subform][field_title][0][value]" with "Title text model"
    And I fill in wysiwyg on field "field_body[3][subform][field_text][0][value]" with "Content text model"
    # ADD Testimony
#    And I click the "#edit-field-body-wrapper .paragraphs-add-wrapper li.dropbutton-toggle button" element
#    And I press the "field_body_model_testimony_add_more" button
#    And I fill in "field_body[2][subform][field_title][0][value]" with "Title text model"
#    And I fill in "field_body[2][subform][field_text][0][value]" with "Content text model"
    #Save node
    And I press "edit-submit"




