<?php

use Behat\Gherkin\Node\TableNode;
use Drupal\Component\Utility\UrlHelper;
use Drupal\DrupalExtension\Context\RawDrupalContext;
use Drupal\file\Entity\File;
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

  protected $currentScenario;

  /**
   * @BeforeScenario
   *
   * @param BeforeScenarioScopeAlias $scope
   *
   */
  public function setUpTestEnvironment($scope)
  {
    $this->currentScenario = $scope->getScenario();
  }

  /**
   * @AfterStep
   *
   * @param AfterStepScopeAlias $scope
   *
   * @throws \Behat\Mink\Exception\DriverException
   * @throws \Behat\Mink\Exception\UnsupportedDriverActionException
   */
  public function afterStep($scope)
  {
    //if test has failed, and is not an api test, get screenshot
    if(!$scope->getTestResult()->isPassed())
    {
      //create filename string
      $featureFolder = str_replace(' ', '', $scope->getFeature()->getTitle());

      $scenarioName = $this->currentScenario->getTitle();
      $fileName = str_replace(' ', '', $scenarioName) . '.png';

      //create screenshots directory if it doesn't exist
      print (__DIR__ . '/../../report/html/assets/screenshots/' . $featureFolder);
      if (!file_exists(__DIR__ . '/../../report/html/assets/screenshots/' . $featureFolder)) {
        mkdir(__DIR__ . '/../../report/html/assets/screenshots/' . $featureFolder, 0777, true);
      }

      $screenshot = $this->getSession()->getDriver()->getScreenshot();
      file_put_contents(__DIR__ . '/../../report/html/assets/screenshots/' . $featureFolder . '/' . $fileName, $screenshot);
      print ('Error screenshot : ' . $featureFolder . '/' . $fileName);
    }
  }

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
   * Click some text in element
   *
   * @When /^I click on the text "([^"]*)" in "([^"]*)" element$/
   */
  public function iClickOnTheTextInElement($text, $selector)
  {
    $page = $this->getSession()->getPage();
    $elements = $page->findAll('css', $selector);
    $error = TRUE;
    if (!empty($elements) && is_array($elements)) {
      /** @var \Behat\Mink\Element\NodeElement $element */
      foreach ($elements as $key => $element) {
        if (!empty($element)) {
          $value = $element->getText();
          if (!empty($value)) {
            if (strcmp($value, $text) === 0) {
              $error = FALSE;
              $element->click();
              break;
            }
          }
        }
      }
      if ($error) {
        throw new Exception ("Element $text not found in $selector.");
      }
    }
    else {
      throw new Exception ("Selector $selector not found.");
    }
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

  /**
   * Set dummy JSON data on a reference.
   *
   * @Given I set the dummy json data on the reference
   */
  public function setReferenceDummyJsonData() {

    // get ID of reference
    $path = UrlHelper::parse($this->getSession()->getCurrentUrl());
    $arrayUrl = explode('/', $path['path']);
    array_pop($arrayUrl); // remove the "edit" at the end
    $nid = end($arrayUrl);

    $node = Node::load($nid);
    $jsonData = json_encode([
      "Name" => "22003016-SIRCO MV 3X160A",
      "Reference" => "22003016",
      "WEIGHT" => "0,82",
      "Nombre de pôle" => "2 P",
      "Format de boitier" => "Format de boitier A",
      "Test champs vide" => "",
    ]);
    $node->set('field_reference_json_table', $jsonData);
    try {
      $node->save();
    } catch (Exception $e) {
    }
  }

  /**
   * @Then The file :arg1 exist
   */
  public function theFileExist($file) {
    $filename = __DIR__ . '/../../../../data/' . $file;
    if (!file_exists($filename)) {
      throw new Exception("The file doesn't exist");
    }
  }

  /**
   * @Then /^I bookmark the resource "([^"]*)"$/
   */
  public function iBookmarkTheResource($title) {
    $query = \Drupal::EntityQuery('node');
    $query->condition('type', 'resource');
    $query->condition('title', $title);
    $results = $query->execute();
    if ($nodes = Node::loadMultiple($results)) {
      /** @var \Drupal\node\Entity\Node $node */
      $node = reset($nodes);
      /** @var \Drupal\soc_bookmarks\Service\Manager\BookmarkManager $bookmarksManager */
      $bookmarksManager = \Drupal::service('soc_bookmarks.bookmark_manager');
      $bookmarksManager->add($node->id());
      try {
        $bookmarksManager->updateCookie();
      }
      catch (\Exception $e) {
        \Drupal::logger('soc_bookmarks')->error($e->getMessage());
      }
    }
  }

  /**
   * @Then /^I should see the resource "([^"]*)" in my bookmarks$/
   */
  public function iShouldSeeTheResourceInMyBookmarks($title) {
    /** @var \Drupal\soc_bookmarks\Service\Manager\BookmarkManager $bookmarksManager */
    $bookmarksManager = \Drupal::service('soc_bookmarks.bookmark_manager');
    if ($bookmarksManager->checkByTitle($title)) {
      return TRUE;
    }
    return FALSE;
  }

  /**
   * @Then I fill in wysiwyg on field :locator with :value
   */
  public function iFillInWysiwygOnFieldWith($locator, $value) {
    $el = $this->getSession()->getPage()->findField($locator);

    if (empty($el)) {
      throw new ExpectationException('Could not find WYSIWYG with locator: ' . $locator, $this->getSession());
    }

    $fieldId = $el->getAttribute('id');

    if (empty($fieldId)) {
      throw new Exception('Could not find an id for field with locator: ' . $locator);
    }

    $this->getSession()
      ->executeScript("CKEDITOR.instances[\"$fieldId\"].setData(\"$value\");");
  }

  /**
   * @Then I should see the breadcrumb link :arg1
   */
  public function iShouldSeeTheBreadcrumbLink($arg1)
  {
    // get the breadcrumb
    /**
     * @var Behat\Mink\Element\NodeElement $breadcrumb
     */
    $breadcrumb = $this->getSession()->getPage()->find('css', 'div.block-system-breadcrumb-block');

    // this does not work for URLs
    $link = $breadcrumb->findLink($arg1);
    if ($link) {
      return;
    }

    // filter by url
    $link = $breadcrumb->findAll('css', "a[href=\"{$arg1}\"]");
    if ($link) {
      return;
    }

    // filter by url
    $page = $this->getSession()->getPage();
    $active = $page->find('css', 'li.breadcrumb-item.active');
    if ($active->getText() == $arg1) {
      return;
    }
    $active = $page->find('css', 'li.breadcrumb-item.active + li.breadcrumb-item.active');
    if ($active->getText() == $arg1) {
      return;
    }

    throw new \Exception(
      sprintf("Expected link %s not found in breadcrumb on page %s",
        $arg1,
        $this->getSession()->getCurrentUrl())
    );
  }

}
