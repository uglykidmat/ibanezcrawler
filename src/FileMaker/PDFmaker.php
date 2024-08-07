<?php

namespace App\FileMaker;

//dd(__DIR__);
//require (__DIR__ . '/FPDF/fpdf.php');
use App\FileMaker\FPDF\FPDF as FPDF;

class PDFmaker extends FPDF
{
    private $FPDFtest;

    public function __construct()
    {
        $this->FPDFtest = new FPDF();
        //$this->FPDFtest = 'new FPDF()';
    }

    public function log()
    {
        dd($this->FPDFtest);
    }
}