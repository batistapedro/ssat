<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
    // Incluimos el archivo fpdf
require_once APPPATH."/third_party/fpdf/fpdf.php";

//Extendemos la clase Pdf de la clase fpdf para que herede todas sus variables y funciones
class Pdf extends FPDF
{
    public function __construct()
    {
        parent::__construct();
      }
        // El encabezado del PDF
    public function Header()
    {
        //$this->Image('./public/img/bannerpdf.jpg',10,8,190);
        $this->SetFont('Arial','B',14);
        $this->SetTextColor(0,0,0);
        $this->Cell(30,9,$this->Image('./public/img/Logofondo.jpg',7,10,38),'LTR',0,'C');
        $this->Cell(180,9,'SOLICITUDES DE SOPORTE Y ASISTENCIA TECNICA','LTR',0,'C');
        $this->SetFont('Arial','',10);
        $this->Cell(70,9,'Codigo : FB-111-FM-0117/15',1,1,'L');
        $this->Cell(30,9,'','LBR',0,'C');
        $this->Cell(180,9,'','LBR',0,'C');
        $this->Cell(70,9,'Fecha de Vigencia : 01/06/2015',1,1,'L');

        $this->Ln(2);
    }
       // El pie del pdf

   public function Footer()
   {
       $this->SetY(-10);
       $this->SetFont('Arial','',8);
       $this->Cell(33,10,utf8_decode('Actualizacion Nº: 00'),0,0,'L');
       $this->Cell(246,10,'Pagina '.$this->PageNo(),0,1,'R');

   }

}
?>
