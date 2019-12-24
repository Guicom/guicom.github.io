<?php

namespace Drupal\soc_wishlist\Service\Manager;

use Drupal\node\Entity\Node;
use Drupal\soc_wishlist\Service\Manager\WishlistManager;
use Drupal\soc_wishlist\Service\WishlistTCPDF;
use Symfony\Component\HttpFoundation\Response;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Cell\DataType;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use TCPDF;

class WishlistExport {

  /**
   * filename constant.
   */
  const WISHLIST_FILENAME = 'wishlist';

  /** @var $wishlistManager */
  protected $wishlistManager;

  /** @var $type */
  protected $type;

  /** @var $fpdf */
  protected $fpdf;
  /**
   * WishlistExport constructor.
   */
  public function __construct(WishlistManager $wishlistManager, string $type) {
    $this->wishlistManager = $wishlistManager;
    $this->type = $type;
  }

  /**
   * WishlistExport manage export
   */
  public function export() {
    $items = $this->wishlistManager->loadSavedItems();
    if (!empty($items)) {
      switch ($this->type) {
        case 'csv':
          return $this->exportCSV($items);
          break;
        case 'xls':
          return $this->exportXLS($items);
          break;
        case 'xlsx':
          return $this->exportXLSX($items);
          break;
        case 'pdf':
          return $this->exportPDF($items);
          break;
      }
    }
    return false;
  }

  /**
   * WishlistExport CSV
   *
   * @param $items
   * @param $datas
   *
   * @return \Symfony\Component\HttpFoundation\Response
   */
  protected function exportCSV($items) {
    $response = new Response();
    $response->headers->set('Pragma', 'no-cache');
    $response->headers->set('Expires', '0');
    $response->headers->set('Content-type', 'text/csv');
    $response->headers->set('Content-Disposition', 'attachment; filename=' . self::WISHLIST_FILENAME . '.csv');

    $csvData = [
      implode(';', ['reference', 'description', 'quantity']),
    ];

    //write data in the CSV format
    foreach ($items as $item) {
      if ($item['node'] instanceof Node) {
        $csvData[] = implode(';', [
          $item['node']->get('field_reference_ref')->value,
          $item['node']->getTitle(),
          $item['quantity']
        ]);
      }
    }

    $content = implode(PHP_EOL, $csvData);
    $response->setContent($content);
    return $response;
  }

  /**
   * WishlistExport XLS
   *
   * @param $items
   * @param $datas
   *
   * @return \Symfony\Component\HttpFoundation\Response
   * @throws \PhpOffice\PhpSpreadsheet\Exception
   * @throws \PhpOffice\PhpSpreadsheet\Writer\Exception
   */
  protected function exportXLS($items) {
    $response = new Response();
    $response->headers->set('Pragma', 'no-cache');
    $response->headers->set('Expires', '0');
    $response->headers->set('Content-Type', 'application/vnd.ms-excel');
    $response->headers->set('Content-Disposition', 'attachment; filename=' . self::WISHLIST_FILENAME . '.xls');

    $spreadsheet = new Spreadsheet();

    //Set metadata.
    $spreadsheet->getProperties()
      ->setCreator('Socomec')
      ->setLastModifiedBy('Socomec')
      ->setTitle("Wishlist")
      ->setLastModifiedBy('Socomec');

    // Get the active sheet.
    $spreadsheet->setActiveSheetIndex(0);
    $worksheet = $spreadsheet->getActiveSheet();

    //Rename sheet
    $worksheet->setTitle('Wishlist');

    /*
    * TITLES
    */
    $worksheet->getCell('A1')->setValue('reference');
    $worksheet->getCell('B1')->setValue('description');
    $worksheet->getCell('C1')->setValue('quantity');

    $inc = 2;
    foreach ($items as $item) {
      if ($item['node'] instanceof Node) {
        $worksheet->setCellValue('A' . $inc, $item['node']->get('field_reference_ref')->value);
        $worksheet->setCellValue('B' . $inc, $item['node']->getTitle());
        $worksheet->setCellValue('C' . $inc, $item['quantity']);
        $inc++;
      }
    }

    // Get the writer and export in memory.
    $writer = IOFactory::createWriter($spreadsheet, 'Xls');
    ob_start();
    $writer->save('php://output');
    $content = ob_get_clean();

    // Memory cleanup.
    $spreadsheet->disconnectWorksheets();
    unset($spreadsheet);


    $response->setContent($content);
    return $response;
  }

  /**
   * WishlistExport XLSX
   *
   * @param $items
   * @param $datas
   *
   * @return \Symfony\Component\HttpFoundation\Response
   * @throws \PhpOffice\PhpSpreadsheet\Exception
   * @throws \PhpOffice\PhpSpreadsheet\Writer\Exception
   */
  protected function exportXLSX($items) {
    $response = new Response();
    $response->headers->set('Pragma', 'no-cache');
    $response->headers->set('Expires', '0');
    $response->headers->set('Content-Type', 'application/vnd.ms-excel');
    $response->headers->set('Content-Disposition', 'attachment; filename=' . self::WISHLIST_FILENAME . '.xlsx');

    $spreadsheet = new Spreadsheet();

    //Set metadata.
    $spreadsheet->getProperties()
      ->setCreator('Socomec')
      ->setLastModifiedBy('Socomec')
      ->setTitle("Wishlist")
      ->setLastModifiedBy('Socomec');

    // Get the active sheet.
    $spreadsheet->setActiveSheetIndex(0);
    $worksheet = $spreadsheet->getActiveSheet();

    //Rename sheet
    $worksheet->setTitle('Wishlist');

    /*
    * TITLES
    */
    $worksheet->getCell('A1')->setValue('reference');
    $worksheet->getCell('B1')->setValue('description');
    $worksheet->getCell('C1')->setValue('quantity');
    $inc = 2;
    foreach ($items as $item) {
      if ($item['node'] instanceof Node) {
        $worksheet->setCellValue('A' . $inc, $item['node']->get('field_reference_ref')->value);
        $worksheet->setCellValue('B' . $inc, $item['node']->getTitle());
        $worksheet->setCellValue('C' . $inc, $item['quantity']);
        $inc++;
      }
    }

    // Get the writer and export in memory.
    $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
    ob_start();
    $writer->save('php://output');
    $content = ob_get_clean();

    // Memory cleanup.
    $spreadsheet->disconnectWorksheets();
    unset($spreadsheet);


    $response->setContent($content);
    return $response;
  }

  /**
   * WishlistExport PDF
   *
   * @param $items
   * @param $datas
   *
   * @return \Symfony\Component\HttpFoundation\Response
   * @throws \PhpOffice\PhpSpreadsheet\Exception
   * @throws \PhpOffice\PhpSpreadsheet\Writer\Exception
   */
  protected function exportPDF($items) {
    $response = new Response();
    $response->headers->set('Pragma', 'no-cache');
    $response->headers->set('Expires', '0');
    $response->headers->set('Content-Type', 'application/pdf');
    $response->headers->set('Content-Disposition', 'attachment; filename=' . self::WISHLIST_FILENAME . '.pdf');

    $pdf = new WishlistTCPDF();

    // set document information
    $title = t('Wishlist') . ' - '. date('Y-m-d');
    $pdf->SetCreator('Socomec');
    $pdf->SetAuthor('Socomec');
    $pdf->SetTitle($title);

    $logo = theme_get_setting('logo.url');
    $host = \Drupal::request()->getSchemeAndHttpHost();


    $urlLogo = $host.$logo;
    $pdf->SetHeaderData($urlLogo, PDF_HEADER_LOGO_WIDTH, $title);

    // set header and footer fonts
    $pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
    $pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

    // set default monospaced font
    $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

    // set margins
    $pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
    $pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
    $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

    // set auto page breaks
    $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

    // set image scale factor
    $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

    $pdf->AddPage();
    $pdf->SetFont('helveticaI', '', 12);
    $text = '<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Aenean tortor lacus, cursus vitae vehicula eu, tempus non libero. Maecenas tincidunt pretium sem, quis mattis ipsum euismod in. Curabitur mollis magna at blandit suscipit. Donec congue dolor ac hendrerit iaculis. Mauris ligula diam, tincidunt nec ligula sed, aliquet tempor justo. Aenean ac finibus massa. Phasellus ultrices rhoncus dui euismod placerat. Mauris in ornare dolor, sed iaculis sapien. Nulla scelerisque mauris non imperdiet pharetra. Nulla ut purus interdum, malesuada urna et, fringilla tellus. Vestibulum luctus sagittis rhoncus. Curabitur facilisis egestas felis ac egestas. Sed porttitor interdum lectus sed rhoncus. Integer maximus, massa eget facilisis tincidunt, lectus odio condimentum velit, ut dignissim nisl nunc et nibh. Etiam congue neque ac egestas auctor. Sed venenatis ut dui eget dapibus.</p>';
    $pdf->writeHTML($text, true, false, false, false, 'J');
    $pdf->Ln(10);

    $pdf->Ln();
    $pdf->SetFont('helveticaI', '', 12);

    $tbl = '<table cellspacing="0" cellpadding="1" border="1">';
    $tbl .= '<tr>';
    $tbl .= '<th>reference</th>';
    $tbl .= '<th>description</th>';
    $tbl .= '<th>quantity</th>';
    $tbl .= '</tr>';
    foreach($items as $item) {
      $tbl .= '<tr>';
      $tbl .= '<td>' . $item['node']->get('field_reference_ref')->value . '</td>';
      $tbl .= '<td>' . $item['node']->getTitle() . '</td>';
      $tbl .= '<td>' . $item['quantity'].'</td>';
      $tbl .= '</tr>';
    }
    $tbl .= '</table>';

    $pdf->writeHTML($tbl, true, false, false, false, '');
    ob_start();
    $response->setContent($pdf->Output('S'));
    ob_end_flush();
    return $response;
  }
}



