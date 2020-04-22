<?php


namespace Drupal\soc_wishlist\Service;

use TCPDF;

class WishlistTCPDF extends TCPDF{

  //Page header
  public function Header() {
    // Logo
    //$host = \Drupal::request()->getSchemeAndHttpHost();
    //$logo = theme_get_setting('logo.url');
    //$urlLogo = $host.$logo;
    $module_handler = \Drupal::service('module_handler');
    $module_path = $module_handler->getModule('soc_wishlist')->getPath();
    $this->Image($module_path.'/images/logo-socomec.png', 10, 10, 50,
      '', 'PNG', '', 'M', false, 300, '',
      false, false, 0, false, false, false);
    // Set font
    $this->SetFont('helvetica', 'B', 20);
    // Title
    $this->Cell(0, 15, $this->title, 0, false, 'R', 0, '',
      0, false, 'M', 'M');
  }

}
