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
    And I fill in "field_body[0][subform][field_figure_1_text_line_1][0][value]" with "Text for my keynumber"
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
    And I fill in "field_body[4][subform][field_link][0][uri]" with "<front>"
    And I press the "field_image-media-library-open-button-field_body-4-subform" button
    And I attach the file "images/example.jpg" to "files[upload]"
    And I wait for AJAX to finish
    And I fill in "media[0][fields][field_media_image][0][alt]" with "Image alt"
    And I click the ".ui-dialog button + button" element
    # ADD Testimony
    And I click the "#edit-field-body-wrapper .paragraphs-add-wrapper li.dropbutton-toggle button" element
    And I press the "field_body_model_testimony_add_more" button
    And I fill in "field_body[5][subform][field_company][0][value]" with "Testimony Company"
    And I fill in "field_body[5][subform][field_nom][0][value]" with "Testimony Name"
    And I fill in "field_body[5][subform][field_position][0][value]" with "Testimony position"
    And I fill in wysiwyg on field "field_body[5][subform][field_text][0][value]" with "Content Testimony"
    And I press the "field_image_2-media-library-open-button-field_body-5-subform" button
    And I attach the file "images/example.jpg" to "files[upload]"
    And I wait for AJAX to finish
    And I fill in "media[0][fields][field_media_image][0][alt]" with "Image alt"
    And I click the ".ui-dialog button + button" element
    And I press the "field_image_1-media-library-open-button-field_body-5-subform" button
    And I attach the file "images/example.jpg" to "files[upload]"
    And I wait for AJAX to finish
    And I fill in "media[0][fields][field_media_image][0][alt]" with "Image alt"
    And I click the ".ui-dialog button + button" element
    # ADD Video embed
    And I click the "#edit-field-body-wrapper .paragraphs-add-wrapper li.dropbutton-toggle button" element
    And I press the "field_body_video_embed_add_more" button
    And I fill in "field_body[6][subform][field_title][0][value]" with "Video title"
    And I press the "field_video_embed_video-media-library-open-button-field_body-6-subform" button
    And I fill in "url" with "https://www.youtube.com/watch?v=-87DSwL7jpg"
    And I click the ".media-library-add-form-oembed-submit" element
    And I wait for AJAX to finish
    And I click the ".ui-dialog button + button" element
    # ADD Video & text
    And I click the "#edit-field-body-wrapper .paragraphs-add-wrapper li.dropbutton-toggle button" element
    And I press the "field_body_video_text_add_more" button
    And I fill in "field_body[7][subform][field_title][0][value]" with "Video & text title"
    And I fill in wysiwyg on field "field_body[7][subform][field_text][0][value]" with "Text video"
    And I press the "field_video_embed_video-media-library-open-button-field_body-7-subform" button
    And I fill in "url" with "https://www.youtube.com/watch?v=-87DSwL7jpg"
    And I click the ".media-library-add-form-oembed-submit" element
    And I wait for AJAX to finish
    And I click the ".ui-dialog button + button" element
    #Save node
    And I press "edit-submit"
    And I go to "news/test-news"
    Then I should see "33"
    Then I should see "TEXT FOR MY KEYNUMBER"
    Then I should see an ".paragraph--type--model-cta .socomec-cta-btn .socomec-cta-title" element
    Then I should see "My CTA text"
    Then I should see "Luke, I am your father"
    Then I should see "Darth Vader"
    Then I should see "Dark Lord of The Sith"
    Then I should see "Title text model"
    Then I should see "Content text model"
    Then I should see "Image title"
    Then I should see "Image legend"
    Then I should see "Testimony Name"
    Then I should see "Testimony position"
    Then I should see "Testimony Company"
    Then I should see "Content Testimony"
    Then I should see "Video title"
    Then I should see "Video & text title"
    Then I should see "Text video"
    Then I should see "Text video"
    Then I should see an ".paragraph--type-video-text .embed-responsive.embed-responsive-16by9" element
    Then I should see an ".paragraph--type--video-embed .embed-responsive.embed-responsive-16by9" element




