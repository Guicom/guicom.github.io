<?php

namespace Drupal\soc_wishlist\Service\Manager;

use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\node\Entity\Node;
use Drupal\soc_wishlist\Service\WishlistTCPDF;
use PhpOffice\PhpSpreadsheet\Writer\Exception;
use Symfony\Component\HttpFoundation\Response;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\IOFactory;

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

  /** @var $settings */
  protected $settings;

  /**
   * WishlistExport constructor.
   *
   * @param \Drupal\soc_wishlist\Service\Manager\WishlistManager $wishlistManager
   * @param \Drupal\Core\Config\ConfigFactoryInterface $configFactory
   */
  public function __construct(WishlistManager $wishlistManager,
                              ConfigFactoryInterface $configFactory) {
    $this->wishlistManager = $wishlistManager;
    $this->settings = $configFactory->getEditable('soc_wishlist.settings');
  }

  /**
   * WishlistExport manage export
   */
  public function export() {
    $tmpItems = $this->wishlistManager->loadSavedItems();
    $exportlist = ($_SESSION['socomec_wishlist_export']) ?? NULL;
    $items = [];
    if (!empty($exportlist)) {
      foreach ($exportlist as $selectedItem) {
        if (isset($tmpItems[$selectedItem])) {
          $items[$selectedItem] = $tmpItems[$selectedItem];
        }
      }
    }
    else{
      $items = $tmpItems;
    }
    if (empty($items)) {
      $items = [];
    }
    switch ($this->getType()) {
      case 'csv':
        return $this->exportCSV($items);
        break;
      case 'xls':
        try {
          return $this->exportXLS($items);
        } catch (Exception $e) {
        } catch (\PhpOffice\PhpSpreadsheet\Exception $e) {
        }
        break;
      case 'xlsx':
        try {
          return $this->exportXLSX($items);
        } catch (Exception $e) {
        } catch (\PhpOffice\PhpSpreadsheet\Exception $e) {
        }
        break;
      case 'pdf':
        try {
          return $this->exportPDF($items);
        } catch (Exception $e) {
        } catch (\PhpOffice\PhpSpreadsheet\Exception $e) {
        }
        break;
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
      implode(';', ['model', 'reference', 'main specification', 'quantity']),
    ];

    //write data in the CSV format
    foreach ($items as $item) {
      if ($item['node'] instanceof Node) {
        $model = ($item['node']->get('field_reference_name')->value) ?? "";
        $reference = ($item['node']->get('field_reference_ref')->value) ?? "";
        $specifications = ($item['node']->getTitle()) ?? "";
        $quantity = ($item['quantity']) ?? "";
        $csvData[] = implode(';', [
          $model,
          $reference,
          $specifications,
          $quantity
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
    $worksheet->getCell('A1')->setValue('Model');
    $worksheet->getCell('B1')->setValue('Reference');
    $worksheet->getCell('C1')->setValue('Main specifications');
    $worksheet->getCell('D1')->setValue('Quantity');

    $inc = 2;
    foreach ($items as $item) {
      if ($item['node'] instanceof Node) {
        $model = ($item['node']->get('field_reference_name')->value) ?? "";
        $reference = ($item['node']->get('field_reference_ref')->value) ?? "";
        $specifications = ($item['node']->getTitle()) ?? "";
        $quantity = ($item['quantity']) ?? "";
        $worksheet->setCellValue('A' . $inc, $model);
        $worksheet->setCellValue('B' . $inc, $reference);
        $worksheet->setCellValue('C' . $inc, $specifications);
        $worksheet->setCellValue('D' . $inc, $quantity);
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
    $worksheet->getCell('A1')->setValue('Model');
    $worksheet->getCell('B1')->setValue('Reference');
    $worksheet->getCell('C1')->setValue('Main specifications');
    $worksheet->getCell('D1')->setValue('Quantity');

    $inc = 2;
    foreach ($items as $item) {
      if ($item['node'] instanceof Node) {
        $model = ($item['node']->get('field_reference_name')->value) ?? "";
        $reference = ($item['node']->get('field_reference_ref')->value) ?? "";
        $specifications = ($item['node']->getTitle()) ?? "";
        $quantity = ($item['quantity']) ?? "";
        $worksheet->setCellValue('A' . $inc, $model);
        $worksheet->setCellValue('B' . $inc, $reference);
        $worksheet->setCellValue('C' . $inc, $specifications);
        $worksheet->setCellValue('D' . $inc, $quantity);
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
    $text = $this->settings->get('pdf_disclaimer');
    $pdf->writeHTML($text, true, false, false, false, 'J');
    $pdf->Ln(10);

    $pdf->Ln();
    $pdf->SetFont('helveticaI', '', 12);

    $tbl = '<table cellspacing="0" cellpadding="1" border="1">';
    $tbl .= '<tr>';
    $tbl .= '<th>' . t('Model') . '</th>';
    $tbl .= '<th>' . t('Reference') . '</th>';
    $tbl .= '<th>' . t('Main specifications') . '</th>';
    $tbl .= '<th>' . t('Quantity') . '</th>';
    $tbl .= '</tr>';
    foreach($items as $item) {
      $model = ($item['node']->get('field_reference_name')->value) ?? "";
      $reference = ($item['node']->get('field_reference_ref')->value) ?? "";
      $specifications = ($item['node']->getTitle()) ?? "";
      $quantity = ($item['quantity']) ?? "";
      $tbl .= '<tr>';
      $tbl .= '<td>' . $model . '</td>';
      $tbl .= '<td>' . $reference . '</td>';
      $tbl .= '<td>' . $specifications . '</td>';
      $tbl .= '<td>' . $quantity . '</td>';
      $tbl .= '</tr>';
    }
    $tbl .= '</table>';

    $pdf->writeHTML($tbl, true, false, false, false, '');
    ob_start();
    $response->setContent($pdf->Output('S'));
    ob_end_flush();
    return $response;
  }

  /**
   * @return mixed
   */
  public function getType() {
    return $this->type;
  }

  /**
   * @param mixed $type
   */
  public function setType($type): void {
    $this->type = $type;
  }
}



