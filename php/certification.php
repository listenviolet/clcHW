<?php
	session_start(); 
	require('../fpdf181/fpdf.php');
	$code=$_SESSION["code"];
	$username=$_SESSION["username"];
	$classname=$_SESSION["classname"];
	$hwname=$_SESSION["hwname"];

	class PDF extends FPDF
    {
        function Header()
        {
            $this->SetFont('Arial','B',20);
            $this->Cell(80);
            $this->Cell(30,20,'Certificaion',0,0,'C');
            $this->Ln(20);
        }
    }

  // Instanciation of inherited class
  $pdf = new PDF();
  $pdf->AddPage();
  $pdf->SetFont('Times','B',14);
	$show_1="Dear ".$username." ,";
	$show_2="your file(s) for class : ".$classname." , homework : ".$hwname.", was(were) uploaded.";
	$show_3="Your certificaion code is ".$_SESSION["code"].".";
  	
	$pdf->Cell(0,10,$show_1,0,1);
	$pdf->Cell(0,10,$show_2,0,1);
	$pdf->Cell(0,10,$show_3,0,1);
  $pdf->Output();
?>