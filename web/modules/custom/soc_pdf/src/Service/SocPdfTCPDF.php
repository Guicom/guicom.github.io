<?php


namespace Drupal\soc_pdf\Service;

use Symfony\Component\HttpFoundation\Response;
use TCPDF;

class SocPdfTCPDF extends TCPDF{

  protected $url;

  /**
   * Prepare header
   */
  public function Header() {
    // Logo
    //$host = \Drupal::request()->getSchemeAndHttpHost();
    //$logo = theme_get_setting('logo.url');
    //$urlLogo = $host.$logo;
    $module_handler = \Drupal::service('module_handler');
    $module_path = $module_handler->getModule('soc_pdf')->getPath();
    $this->Image($module_path.'/images/logo-socomec.png', 10, 10, 50,
      '', 'PNG', '', 'M', false, 300, '',
      false, false, 0, false,
      false, false);
    // Set font
    $this->SetFont('helvetica', 'B', 10);
    $this->SetTextColor(0, 79, 159);
    // Title
    $this->Cell(0, 15, $this->title, 0, false, 'R',
      0, '',0, false, 'M', 'M');
  }

  // Page footer
  public function Footer() {
    // Position at 20 mm from bottom
    $this->SetY(-15);
    // Set font
    $this->SetFont('helvetica', 'I', 8);
    $today = date("Y-m-d H:i:s");
    $this->MultiCell(30, 5, $today, 0, 'L', 0, 0,
      '', '', true);
    $this->MultiCell(160, 5,
      $this->getAliasNumPage().'/'.$this->getAliasNbPages(),
      0, 'R', 0, 0, '', '', true);
    if ($url = $this->getUrl()) {
      $this->Ln(5);
      $this->Cell(0, 5, $url, 0, 'R', 0, 0,
        '', '', true);
    }
  }

  public function getUrl() {
    if (!empty($this->url)) {
      return $this->url;
    }
    return FALSE;
  }

  public function setUrl($url) {
    $this->url = $url;
  }

  /**
   * @param $title
   */
  public function prepareHeader($title, bool $displayTitle = TRUE, $creator = "Socomec", $author = "Socomec") {
    $this->SetCreator($creator);
    $this->SetAuthor($author);
    $this->SetTitle($title);

    $logo = theme_get_setting('logo.url');
    $host = \Drupal::request()->getSchemeAndHttpHost();


    $urlLogo = $host.$logo;
    $this->SetHeaderData($urlLogo, PDF_HEADER_LOGO_WIDTH, $title);

    // set header and footer fonts
    $this->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
    $this->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

    // set default monospaced font
    $this->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

    // set margins
    $this->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
    $this->SetHeaderMargin(PDF_MARGIN_HEADER);
    $this->SetFooterMargin(PDF_MARGIN_FOOTER);

    // set auto page breaks
    $this->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

    // set image scale factor
    $this->setImageScale(PDF_IMAGE_SCALE_RATIO);
  }

  /**
   * @param $filename
   * @param $content
   *
   * @return \Symfony\Component\HttpFoundation\Response
   */
  public function getResponse($filename, $content) {
    $response = new Response();
    $response->headers->set('Pragma', 'no-cache');
    $response->headers->set('Expires', '0');
    $response->headers->set('Content-Type', 'application/pdf');
    $response->headers->set('Content-Disposition', 'attachment; filename=' . $filename . '.pdf');
    ob_start();
    $response->setContent($content);
    ob_end_flush();
    return $response;
  }
}
