<?php 
namespace App\Controllers;

class MpdfController extends BaseController
{
    
    public function index()
    {
        //$sheet = $this->quitacao_model->get_discharge();
        
        $mpdf = new mPDF();
        $html = $this->load->view('welcome_message',[],true,$sheet);
        $mpdf->WriteHTML($html);
        $mpdf->Output(); 
    }
    
}
