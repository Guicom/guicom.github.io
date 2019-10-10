<?php

use Drupal\DrupalExtension\Context\RawDrupalContext;

/**
 * Class SessionContext
 */
class SessionContext extends RawDrupalContext {

  /**
   *  @Given I select the other session
   */
  public function iSelectTheOtherSession() {
    $page = $this->getSession()->getPage();

    $labels = $page->findAll('css', 'div.form-type-radio');

    /** @var \Behat\Mink\Element\Element $label */
    foreach ($labels as $label) {
      $text = $label->getText();

      if (!strstr($text, "Session actuelle.")) {
        $label->click();
      }
    }
  }

}
