<?php
use Behat\Testwork\Tester\Result\TestResult;
use Behat\Mink\Driver\Selenium2Driver;
use Drupal\DrupalExtension\Context\RawDrupalContext;

/**
 * Class ScreenshotContext
 */
class ScreenshotContext extends RawDrupalContext {
  protected $scenarioTitle = null;
  protected static $wsendUser = null;

  /**
   * @BeforeScenario
   */
  public function resizeWindow() {
    if ($this->isJavascript()) {
      $this->getSession()->start();
      $this->getSession()->resizeWindow(1920, 1080, 'current');
    }
  }

  /**
   * @BeforeScenario
   */
  public function cacheScenarioName($event) {
    // it's only to have a clean screenshot name later
    $this->scenarioTitle = $event->getScenario()->getTitle();
  }

  /**
   * @AfterStep
   */
  public function takeScreenshotAfterFailedStep($event) {
    if ($event->getTestResult()->getResultCode() !== TestResult::FAILED) {
      return;
    }

    $this->takeAScreenshot();
  }

  /**
   * @Then take a screenshot
   */
  public function takeAScreenshot() {
    if (!$this->isJavascript()) {
      print "Screenshot cannot be taken from non javascript scenario.\n";

      return;
    }

    $screenshot = $this->getSession()->getDriver()->getScreenshot();

    $filename = $this->getScreenshotFilename();
    file_put_contents($filename, $screenshot);

    $url = $this->getScreenshotUrl($filename);

    print sprintf("Screenshot is available :\n%s", $url);
  }

  /**
   * Returns the screenshot URL from wsend.
   *
   * @param $filename
   *
   * @return mixed
   */
  protected function getScreenshotUrl($filename) {
    if (!self::$wsendUser) {
      self::$wsendUser = $this->getWsendUser();
    }

    exec(sprintf(
      'curl -F "uid=%s" -F "filehandle=@%s" %s 2>/dev/null',
      self::$wsendUser,
      $filename,
      'https://wsend.net/upload_cli'
    ), $output, $return);

    return $output[0];
  }

  /**
   * Creates a Wsend anonymous user.
   *
   * @return mixed
   */
  protected function getWsendUser() {
    // create a wsend anonymous user
    $curl = curl_init('https://wsend.net/createunreg');
    curl_setopt($curl, CURLOPT_POSTFIELDS, 'start=1');
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

    $wsendUser = curl_exec($curl);
    curl_close($curl);

    return $wsendUser;
  }

  /**
   * Returns a screenshot filename.
   *
   * @return string
   */
  protected function getScreenshotFilename() {
    $filename = $this->scenarioTitle;
    $filename = preg_replace("#[^a-zA-Z0-9\._-]#", '_', $filename);

    return sprintf('%s/%s.png', sys_get_temp_dir(), $filename);
  }

  /**
   * Check if we are in a javascript session.
   *
   * @return bool
   */
  protected function isJavascript() {
    return $this->getSession()->getDriver() instanceof Selenium2Driver;
  }
}
