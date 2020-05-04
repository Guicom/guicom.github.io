Feature: Default
  @api @cit @javascript
  In order to test out the header

  Scenario: Check all the header element
    Given I am not logged in
    When I am on the homepage
    Then I should see an "#header .branding .navbar-brand img" element
    Then I should see an "#header .block-we-megamenu .navbar-expand-lg" element
    Then I should see an "#header .menu--header-visitors .ico-search-white" element
    Then I should see an "#header .menu--header-visitors .ico-bookmark-star-white" element
    Then I should see an "#header .menu--header-visitors .ico-favorite" element
    Then I should see an "#header .menu--header-visitors .ico-user" element
