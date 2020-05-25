<?php

namespace Drupal\soc_pdf_content\Service;

use Drupal\node\NodeInterface;
use Drupal\soc_pdf\Service\SocPdfTCPDF;

class ProductReferencePdf {

  /**
   * product reference generatePdf
   */
  public static function generatePdf(NodeInterface $node) {
    $pdf = new SocPdfTCPDF();
    // set document information
    $title = $node->getTitle();
    $pdf->prepareHeader($title);
    $url = $node->toUrl()->setAbsolute()->toString();
    $pdf->setUrl($url);
    $pdf->AddPage();
    $pdf->SetFont('helvetica', 'B', 20);
    $pdf->SetTextColor(0, 79, 159);
    // Title
    $pdf->Ln(10);
    $pdf->writeHTML($title, true, false, true, false, '');
    $pdf->Ln(2);
    if ($node->hasField('field_reference_ref')) {
      $reference = $node->get('field_reference_ref')->getValue();
      if (!empty($reference[0]['value'])) {
        $pdf->SetFont('helvetica', 'B', 16);
        $pdf->SetTextColor(35, 44, 119);
        $pdf->writeHTML($reference[0]['value'], true, false, true, false, '');
        $pdf->Ln(2);
      }
    }
    if ($node->hasField('field_teaser')) {
      $teaser = $node->get('field_teaser')->getValue();
      if (!empty($teaser[0]['value'])) {
        $pdf->SetFont('helvetica', '', 12);
        $pdf->SetTextColor(0, 0, 0);
        $pdf->writeHTML($teaser[0]['value'], true, false, true, false, '');
        $pdf->Ln(10);
      }
    }
    if ($node->hasField('field_characteristics')) {
      $characteristics = $node->get('field_characteristics')->getValue();
    }
    if (!empty($characteristics[0]['value'])) {
      $decodeCharacteristics = json_decode($characteristics[0]['value'], TRUE);
    }
    if (!empty($decodeCharacteristics) && is_array($decodeCharacteristics)) {
      foreach ($decodeCharacteristics as $groupLabel => $groupCharacteristics) {
        $pdf->SetFont('helvetica', '', 14);
        $pdf->SetTextColor(0, 112, 192);
        $pdf->Cell(0, 0, $groupLabel, 0, 0, 'L', 0, '', 0);
        $pdf->Ln(8);
        $ouput = "";
        $i = 0;
        foreach ($groupCharacteristics as $key => $values) {
          $ouput .= '<tr>';
          if ($i === 0) {
            $ouput .= '<th style="border-bottom: 1px solid #DDDDDD;border-top: 1px solid #FFCC00;"><strong>' . $values['label'] . '</strong></th>';
            $ouput .= '<td style="border-bottom: 1px solid #DDDDDD;border-top: 1px solid #FFCC00;background-color:#f2f6fa;">';
          }
          else {
            $ouput .= '<th style="border-bottom: 1px solid #DDDDDD;"><strong>' . $values['label'] . '</strong></th>';
            $ouput .= '<td style="border-bottom: 1px solid #DDDDDD;background-color:#F2F6FA;">';
          }
          $i++;
          $outputValue = "";
          if (is_array($values['value'])) {
            foreach ($values['value'] as $value) {
              if (count($values['value']) > 1) {
                if (!empty($outputValue)) {
                  if (!empty($value)) {
                    $outputValue .= '<br/>';
                  }
                }
              }
              $outputValue .= $value;
            }
          }
          else {
            $outputValue .= $values['value'];
          }
          $ouput .= $outputValue;
          $ouput .= '</td>';
          $ouput .= '</tr>';
        }

        if (!empty($ouput)) {
          $render = '<table  border="0" cellpadding="10" cellspacing="0">';
          $render .= $ouput;
          $render .= '</table>';
          $pdf->SetFont('helvetica', '', 12);
          $pdf->SetTextColor(74, 74, 71);
          $pdf->writeHTML($render, true, false, false, false, 'L');
        }
      }
      $pdf->Ln(5);
    }
    $clean_string = \Drupal::service('pathauto.alias_cleaner')->cleanString($title);
    return $pdf->getResponse($clean_string, $pdf->Output('S'));
  }

}



