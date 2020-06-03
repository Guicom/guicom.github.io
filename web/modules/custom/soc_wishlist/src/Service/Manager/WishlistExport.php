<?php

namespace Drupal\soc_wishlist\Service\Manager;

use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\Core\Logger\LoggerChannelFactoryInterface;
use Drupal\node\Entity\Node;
use Drupal\soc_pdf\Service\SocPdfTCPDF;
use PhpOffice\PhpSpreadsheet\Writer\Exception;
use Symfony\Component\HttpFoundation\Response;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\IOFactory;

class WishlistExport {

  /** filename constant. */
  const WISHLIST_FILENAME = 'wishlist';

  /** @var $wishlistManager */
  protected $wishlistManager;

  /** @var $type */
  protected $type;

  /** @var $fpdf */
  protected $fpdf;

  /** @var $settings */
  protected $settings;

  /** @var $logger */
  protected $logger;

  /**
   * WishlistExport constructor.
   *
   * @param \Drupal\soc_wishlist\Service\Manager\WishlistManager $wishlistManager
   * @param \Drupal\Core\Config\ConfigFactoryInterface $configFactory
   * @param \Drupal\Core\Logger\LoggerChannelFactoryInterface $channel_factory
   */
  public function __construct(WishlistManager $wishlistManager,
                              ConfigFactoryInterface $configFactory,
                              LoggerChannelFactoryInterface $channel_factory
  ) {
    $this->wishlistManager = $wishlistManager;
    $this->settings = $configFactory->getEditable('soc_wishlist.settings');
    $this->logger = $channel_factory->get('soc_wishlist');
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

    if (empty($items)) {
      $items = [];
    }
    switch ($this->getType()) {
      case 'csv':
        return $this->exportCSV($items);
        break;
      case 'xls':
      case 'xlsx':
        try {
          $response = new Response();
          $response->headers->set('Pragma', 'no-cache');
          $response->headers->set('Expires', '0');
          $response->headers->set('Content-Type', 'application/vnd.ms-excel');
          if ($this->getType() == 'xls') {
            $response->headers->set('Content-Disposition', 'attachment; filename=' . self::WISHLIST_FILENAME . '.xls');
            $writerType = 'Xls';
          }
          else {
            $response->headers->set('Content-Disposition', 'attachment; filename=' . self::WISHLIST_FILENAME . '.xlsx');
            $writerType = 'Xlsx';
          }

          return $this->exportXLS($response, $writerType, $items);
        } catch (Exception $e) {
          $this->logger->error($e->getMessage());
        } catch (\PhpOffice\PhpSpreadsheet\Exception $e) {
          $this->logger->error($e->getMessage());
        }
        break;
      case 'pdf':
        try {
          return $this->exportPDF($items);
        } catch (Exception $e) {
          $this->logger->error($e->getMessage());
        } catch (\PhpOffice\PhpSpreadsheet\Exception $e) {
          $this->logger->error($e->getMessage());
        }
        break;
      default:
        break;
    }
    return false;
  }

  /**
   * WishlistExport CSV
   *
   * @param $items
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
    $bom = (chr(0xEF) . chr(0xBB) . chr(0xBF));
    $content = $bom . $content;
    $response->setContent($content);
    return $response;
  }

  /**
   * WishlistExport XLS OR XLSX
   *
   * @param $response
   * @param $writerType
   * @param $items
   *
   * @return \Symfony\Component\HttpFoundation\Response
   * @throws \PhpOffice\PhpSpreadsheet\Exception
   * @throws \PhpOffice\PhpSpreadsheet\Writer\Exception
   */
  protected function exportXLS($response, $writerType, $items) {
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
    $writer = IOFactory::createWriter($spreadsheet, $writerType);
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
   *
   * @return \Symfony\Component\HttpFoundation\Response
   * @throws \PhpOffice\PhpSpreadsheet\Exception
   * @throws \PhpOffice\PhpSpreadsheet\Writer\Exception
   */
  protected function exportPDF($items) {
    $pdf = new SocPdfTCPDF();
    $title = t('Wishlist') . ' - '. date('Y-m-d');
    // set document information
    $pdf->prepareHeader($title);
    $pdf->AddPage();
    $pdf->SetFont('helveticaI', '', 12);
    $text = $this->settings->get('pdf_disclaimer');
    $pdf->writeHTML($text, true, false, false, false, 'J');
    $pdf->Ln(10);
    $pdf->Ln();
    $pdf->SetFont('helveticaI', '', 12);
    $tbl = '<table cellpadding="5" cellspacing="0" border="1">';
    $tbl .= '<tr>';
    $tbl .= '<th>' . t('Model') . '</th>';
    $tbl .= '<th>' . t('Reference') . '</th>';
    $tbl .= '<th>' . t('Main specifications') . '</th>';
    $tbl .= '<th>' . t('Quantity') . '</th>';
    $tbl .= '</tr>';
    foreach ($items as $item) {
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
    return $pdf->getResponse(self::WISHLIST_FILENAME, $pdf->Output('S'));
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



