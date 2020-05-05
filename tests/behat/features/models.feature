@api @cit @javascript
Feature: Models
  In order to test all the models

  Background:
#    Given media items with file in the "field_media_image" field:
#      | name                           | bundle      | field_media_image  |
#      | TEST Media image               | image       | images/example.jpg   |
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
    #Add Image
    And I click the "#edit-field-body-wrapper .paragraphs-add-wrapper li.dropbutton-toggle button" element
    And I press the "field_body_model_image_add_more" button
    And I fill in "field_body[4][subform][field_title][0][value]" with "Image title"
    And I fill in "field_body[4][subform][field_legend][0][value]" with "Image legend"
    And I press the "field_image-media-library-open-button-field_body-4-subform" button
    And I attach the file "images/example.jpg" to "files[upload]"
    And I wait for AJAX to finish
    And I fill in "media[0][fields][field_media_image][0][alt]" with "Image alt"
    And I click the ".ui-dialog .button--primary" element
    And I click the ".ui-dialog-buttonset .media-library-select" element
    # ADD Testimony
#    And I click the "#edit-field-body-wrapper .paragraphs-add-wrapper li.dropbutton-toggle button" element
#    And I press the "field_body_model_testimony_add_more" button
#    And I fill in "field_body[2][subform][field_title][0][value]" with "Title text model"
#    And I fill in "field_body[2][subform][field_text][0][value]" with "Content text model"
    #Save node
    And I press "edit-submit"




