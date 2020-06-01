<?php

namespace Drupal\soc_pdf_content\Controller;

use Drupal\Core\Access\AccessResult;
use Drupal\Core\Controller\ControllerBase;
use Drupal\node\NodeInterface;
use Drupal\soc_pdf_content\Service\ProductReferencePdf;
/**
 * Class SocPdfController.
 */
class SocPdfController extends ControllerBase {

   /**
   * Generatepdf.
   *
   * @return string
   *   Return Hello string.
   */
  public function generatePdf(NodeInterface $node) {
    $bundle = $node->getType();
    switch ($bundle) {
      case "product_reference":
        return ProductReferencePdf::generatePdf($node);
        break;
    }
    return [];
  }

  /**
   * Checks access for this controller.
   */
  public function access(NodeInterface $node) {
    $types = [
      "product_reference"
    ];
    if (in_array($node->getType(), $types)) {
      return AccessResult::allowed();
    }
    return AccessResult::forbidden();
  }
}
