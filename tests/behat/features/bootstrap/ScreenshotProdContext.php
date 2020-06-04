<?php

use Behat\Behat\Hook\Scope\AfterStepScope as AfterStepScopeAlias;
use Behat\Behat\Hook\Scope\BeforeScenarioScope as BeforeScenarioScopeAlias;
use Drupal\DrupalExtension\Context\RawDrupalContext;

/**
 * Class ScreenshotContext
 */
class ScreenshotProdContext extends RawDrupalContext {
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
   */
  public function afterStep($scope)
  {
    //if test has failed, and is not an api test, get screenshot
    if(!$scope->getTestResult()->isPassed())
    {
      //create filename string

      $featureFolder = preg_replace('/\W/', '', $scope->getFeature()->getTitle());

      $scenarioName = $this->currentScenario->getTitle();
      $fileName = preg_replace('/\W/', '', $scenarioName) . '.png';

      //create screenshots directory if it doesn't exist
      if (!file_exists('results/html/assets/screenshots/' . $featureFolder)) {
        mkdir('results/html/assets/screenshots/' . $featureFolder);
      }

      if ($this->isJavascript()) {
        file_put_contents(
          'results/html/assets/screenshots/' . $featureFolder . '/' . $fileName,
          $this->getSession()->getDriver()->getScreenshot()
        );
      } else {
        $this->driver->takeScreenshot('results/html/assets/screenshots/' . $featureFolder . '/' . $fileName);
      }
    }
  }
}
