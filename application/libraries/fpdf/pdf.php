<?php
$CI =& get_instance();
$CI->load->library('fpdf/fpdf');
class PDF extends FPDF  
{  
 
function Footer()  
{  
    $this->SetY(-20);  
    $this->SetFont('Arial','B',6);  
	//$this->Image(''.base_url().'css/img/footer.PNG' , 15 ,$this->SetY(-30), 180 , 15,'PNG');
    $this->Cell(50,10,'Fecha emision: '.date('d/m/Y h:m').'',0,0,'L');  
	$this->Cell(50,10,'WEB',0,0,'L');
	$this->Cell(0,10,'Page '.$this->PageNo().'',0,0,'R');
}  
} 
?>
