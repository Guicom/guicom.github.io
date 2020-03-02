<?php

use Behat\Mink\Driver\Selenium2Driver;
use Drupal\Core\Url;
use Drupal\DrupalExtension\Context\RawDrupalContext;
use Drupal\node\Entity\Node;

/**
 * Class FeatureContext
 */
class FeatureContext extends RawDrupalContext {
  /**
   * @var $output
   *   Command line output.
   */
  protected $output;

  /**
   * @Given I accept all cookies compliance
   */
  public function iSetCookieCompliance() {
    $this->getSession()->setCookie("cookie-agreed", 2);
    $categorie = urlencode('["required","statistics","preferences","targeting"]');
    $this->getSession()->setCookie("cookie-agreed-categories", $categorie);
    $this->getSession()->reload();
  }


  /**
   * Switches to the main window
   *
   * @Given /^I switch to the main windows$/
   */
  public function switchToWindow(){
    $this->getSession()->switchToWindow();
  }
  /**
   * Switches focus to an iframe.
   *
   * @Given /^I switch (?:away from|to) the iframe "([^"]*)"$/
   * @param string $iframe_name
   */
  public function iSwitchToTheIframe($iframe_name) {
    if ($iframe_name) {
      $this->getSession()->switchToIFrame($iframe_name);
    } else {
      $this->getSession()->switchToIFrame();
    }
  }

  /**
   * @When /^I check the "([^"]*)" radio button$/
   */
  public function iCheckTheRadioButton($radioLabel) {
    $radioButton = $this->getSession()->getPage()->findField($radioLabel);
    if (null === $radioButton) {
      throw new Exception("Cannot find radio button ".$radioLabel);
    }
    $value = $radioButton->getAttribute("value");
    $this->getSession()->getDriver()->click($radioButton->getXPath());
  }

  /**
   * @Then /^I want to see the URL$/
   *
   * @throws \Exception
   */
  public function iWantToSeeTheURL() {
    try {
      $url = $this->getSession()->getCurrentUrl();
      var_dump($url);
    } catch (Exception $e) {
      throw new Exception($e);
    }
  }

  /**
   * @Then /^I scroll to the top$/
   *
   * @throws \Exception
   */
  public function iScrollToTheTop() {
    $this->getSession()->executeScript('window.scrollTo(0,0);');
  }

  /**
   * @Then /^I want to see the page content$/
   *
   * @throws \Exception
   */
  public function iWantToSeeThePageContent() {
    try {
      $html = $this->getSession()->getPage()->getHtml();
      print($html);
    } catch (Exception $e) {
      throw new Exception($e);
    }
  }

  /**
   * @Given /^I wait (\d+) seconds$/
   */
  public function iWaitSeconds($seconds) {
    sleep($seconds);
  }


  /**
   * @Given I set cookie :name :value
   */
  public function iSetCookie($name, $value) {
    $this->getSession()->setCookie($name, $value);
  }

  /**
   * @Then I enable xdebug
   */
  public function iEnableXdebug() {
    $cookie = new \Symfony\Component\BrowserKit\Cookie('XDEBUG_SESSION', 'phpstorm');
    $this->getSession()->setRequestHeader('Cookie', (string)$cookie);
  }

  /**
   * @Then I click the back button of the navigator
   */
  public function iClickTheBackButtonInNavigator() {
    $this->getSession()->getDriver()->back();
  }

  /**
   * @Given I click the :arg1 element
   */
  public function iClickTheElement($selector) {
    $page = $this->getSession()->getPage();
    $element = $page->find('css', $selector);

    if (empty($element)) {
      throw new Exception("No html element found for the selector ('$selector')");
    }

    $element->click();
  }

  /**
   * @Given I select the first element in :arg1 list
   */
  public function iSelectTheFirstElement($selector) {
    $page = $this->getSession()->getPage();

    $options = $page->findAll('css', "#$selector option");

    /** @var \Behat\Mink\Element\NodeElement $option */
    foreach ($options as $option) {
      if (strcmp($option->getValue(), "_none") != 0) {
        $page->selectFieldOption($selector, $option->getValue());
        return;
      }
    }

    throw new Exception("Unable to find a non empty value.");
  }

  /**
   * Click some text
   *
   * @When /^I click on the text "([^"]*)"$/
   */
  public function iClickOnTheText($text)
  {
    $session = $this->getSession();
    $element = $session->getPage()->find(
      'xpath',
      $session->getSelectorsHandler()->selectorToXpath('xpath', '*//*[text()="'. $text .'"]')
    );
    if (null === $element) {
      throw new \InvalidArgumentException(sprintf('Cannot find text: "%s"', $text));
    }

    $element->click();
  }

  /**
   * @Then /^the selectbox "([^"]*)" should have a list containing:$/
   */
  public function shouldHaveAListContaining($element, \Behat\Gherkin\Node\PyStringNode $list)
  {
    $page = $this->getSession()->getPage();
    $validStrings = $list->getStrings();

    $elements = $page->findAll('css', "#$element option");

    $option_none = 0;

    /** @var \Behat\Mink\Element\NodeElement $element */
    foreach ($elements as $element) {
      $value = $element->getValue();
      if (strcmp($value, '_none') == 0) {
        $option_none = 1;
        continue;
      }

      if (!in_array($element->getValue(), $validStrings)) {
        throw new Exception ("Element $value not found.");
      }
    }

    if ((sizeof($elements) - $option_none) < sizeof($validStrings)) {
      throw new Exception ("Expected options are missing in the select list.");
    }
    elseif ((sizeof($elements) - $option_none) > sizeof($validStrings)) {
      throw new Exception ("There are more options than expected in the select list.");
    }
  }

  /**
   * Wait for AJAX to finish.
   *
   * @see \Drupal\FunctionalJavascriptTests\JSWebAssert::assertWaitOnAjaxRequest()
   *
   * @Given I wait max :arg1 seconds for AJAX to finish
   */
  public function iWaitForAjaxToFinish($seconds) {
    $condition = <<<JS
    (function() {
      function isAjaxing(instance) {
        return instance && instance.ajaxing === true;
      }
      var d7_not_ajaxing = true;
      if (typeof Drupal !== 'undefined' && typeof Drupal.ajax !== 'undefined' && typeof Drupal.ajax.instances === 'undefined') {
        for(var i in Drupal.ajax) { if (isAjaxing(Drupal.ajax[i])) { d7_not_ajaxing = false; } }
      }
      var d8_not_ajaxing = (typeof Drupal === 'undefined' || typeof Drupal.ajax === 'undefined' || typeof Drupal.ajax.instances === 'undefined' || !Drupal.ajax.instances.some(isAjaxing))
      return (
        // Assert no AJAX request is running (via jQuery or Drupal) and no
        // animation is running.
        (typeof jQuery === 'undefined' || (jQuery.active === 0 && jQuery(':animated').length === 0)) &&
        d7_not_ajaxing && d8_not_ajaxing
      );
    }());
JS;
    $result = $this->getSession()->wait($seconds * 1000, $condition);
    if (!$result) {
      throw new \RuntimeException('Unable to complete AJAX request.');
    }
  }


  /**
   * @Then I submit the form with id :arg1
   */
  public function iSubmitTheFormWithId($id_form)
  {
      $node = $this->getSession()->getPage()->findById($id_form);
      if ($node) {
        $this->getSession()->executeScript('jQuery("#'.$id_form.'").submit();');
      }
      else {
        throw new \RuntimeException('form with id '.$id_form.' not found');
    }
  }

  /**
   *  @Given I reset the session
   */
  public function iResetTheSession() {
    $this->getSession()->reset();
  }

/**
 * @Then /^the option "([^"]*)" from select "([^"]*)" is selected$/
 */
  public function theOptionFromSelectIsSelected($optionValue, $select) {
    $selectField = $this->getSession()->getPage()->find('css', $select);
    if (NULL === $selectField) {
      throw new \Exception(sprintf('The select "%s" was not found in the page %s', $select, $this->getSession()
        ->getCurrentUrl()));
    }

    $optionField = $selectField->find('xpath', "//option[@selected='selected']");
    if (NULL === $optionField) {
      throw new \Exception(sprintf('No option is selected in the %s select in the page %s', $select, $this->getSession()
        ->getCurrentUrl()));
    }

    if ($optionField->getValue() != $optionValue) {
      throw new \Exception(sprintf('The option "%s" was not selected in the page %s, %s was selected', $optionValue, $this->getSession()
        ->getCurrentUrl(), $optionField->getValue()));
    }
  }

  /**
   * @Then I scroll :selector into view
   */
  public function scrollIntoView($elementId) {
    $function = <<<JS
(function(){
  var elem = document.getElementById("$elementId");
  elem.scrollIntoView({block: "start"});
})()
JS;
    try {
      $this->getSession()->executeScript($function);
    }
    catch(Exception $e) {
      throw new \Exception("ScrollIntoView failed");
    }
  }

  /**
   * Fills in specified field with date
   * Example: When I fill in "field_ID" with date "now"
   * Example: When I fill in "field_ID" with date "-7 days"
   * Example: When I fill in "field_ID" with date "+7 days"
   * Example: When I fill in "field_ID" with date "-/+0 weeks"
   * Example: When I fill in "field_ID" with date "-/+0 years".
   *
   * @When /^(?:|I )fill in "(?P<field>(?:[^"]|\\")*)" with date "(?P<value>(?:[^"]|\\")*)"$/
   */
  public function fillDateField($field, $value) {
    $newDate = strtotime("$value");

    $dateToSet = date("d/m/Y", $newDate);
    $this->getSession()->getPage()->fillField($field, $dateToSet);
  }

  /**
   * Fills in specified field with date
   * Example: When I fill in "field_ID" with date "now" in the format "m/d/Y"
   * Example: When I fill in "field_ID" with date "-7 days" in the format "m/d/Y"
   * Example: When I fill in "field_ID" with date "+7 days" in the format "m/d/Y"
   * Example: When I fill in "field_ID" with date "-/+0 weeks" in the format "m/d/Y"
   * Example: When I fill in "field_ID" with date "-/+0 years" in the format "m/d/Y"
   *
   * @When /^(?:|I )fill in "(?P<field>(?:[^"]|\\")*)" with date "(?P<value>(?:[^"]|\\")*)" in the format "(?P<format>(?:[^"]|\\")*)"$/
   */
  public function fillDateFieldFormat($field, $value, $format)
  {
    $newDate = strtotime("$value");

    $dateToSet = date($format, $newDate);
    $this->getSession()->getPage()->fillField($field, $dateToSet);
  }

}
