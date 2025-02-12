<?php
session_set_cookie_params(0);
session_start();
include('config/main.php');
require('fpdf/fpdf.php');
require 'vendor/autoload.php'; 

use Smalot\PdfParser\Parser;

if (isset($_GET['id'])) {
    $plant_id = mysqli_real_escape_string($conn, $_GET['id']);

    $sql = "SELECT * FROM plant_table WHERE id = '$plant_id'";
    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) > 0) {
        $plant = mysqli_fetch_assoc($result);

        $pdf = new FPDF();
        $pdf->AddPage();

        if (!empty($plant['plants_image'])) {
            $imagePath = 'img/Contribute/' . $plant['plants_image'];
            if (file_exists($imagePath)) {
                $pdf->Image($imagePath, 140, 30, 50, 50); 
                $pdf->Ln(10); 
            } else {
                $pdf->Cell(0, 10, "Image file not found.", 0, 1, 'C');
            }
        }

        $pdf->SetFont('Arial', 'B', 16);
        $pdf->Cell(0, 10, 'Plant Details', 0, 1, 'C');
        $pdf->Ln(5);

        $pdf->SetFont('Arial', '', 12);
        $pdf->Cell(40, 10, 'Scientific Name: ', 0, 0);
        $pdf->Cell(0, 10, htmlspecialchars($plant['scientific_name']), 0, 1);

        $pdf->Cell(40, 10, 'Common Name: ', 0, 0);
        $pdf->Cell(0, 10, htmlspecialchars($plant['common_name']), 0, 1);

        if (!empty($plant['family'])) {
            $pdf->Cell(40, 10, 'Family: ', 0, 0);
            $pdf->Cell(0, 10, htmlspecialchars($plant['family']), 0, 1);
        }

        if (!empty($plant['genus'])) {
            $pdf->Cell(40, 10, 'Genus: ', 0, 0);
            $pdf->Cell(0, 10, htmlspecialchars($plant['genus']), 0, 1);
        }

        if (!empty($plant['species'])) {
            $pdf->Cell(40, 10, 'Species: ', 0, 0);
            $pdf->Cell(0, 10, htmlspecialchars($plant['species']), 0, 1);
        }

        if (!empty($plant['description'])) {
            $descriptionFilePath = $plant['description'];
            if (file_exists($descriptionFilePath)) {
                try {
                    $parser = new Parser();
                    $descriptionPdf = $parser->parseFile($descriptionFilePath);
                    $descriptionText = $descriptionPdf->getText();

                    $pdf->Ln(10);
                    $pdf->SetFont('Arial', 'B', 14);
                    $pdf->Cell(0, 10, 'Description:', 0, 1);
                    $pdf->SetFont('Arial', '', 12);
                    $pdf->MultiCell(0, 10, $descriptionText);
                } catch (Exception $e) {
                    $pdf->Ln(10);
                    $pdf->SetFont('Arial', 'I', 10);
                    $pdf->Cell(0, 10, 'Unable to read description file: ' . $e->getMessage(), 0, 1);
                }
            } else {
                $pdf->Ln(10);
                $pdf->SetFont('Arial', 'I', 10);
                $pdf->Cell(0, 10, 'Description file not found.', 0, 1);
            }
        }

        $plantName = preg_replace('/[^a-zA-Z0-9-_]/', '', $plant['scientific_name']);
        $fileName = 'Plant_Details_' . $plantName . '.pdf';

        $pdf->Output('D', $fileName);
    } else {
        echo "Plant not found.";
    }

    mysqli_free_result($result);
    mysqli_close($conn);
} else {
    echo "No plant ID provided.";
}
?>
