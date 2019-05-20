<?php defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * @packge        CodeIgniter
 * @subpackage        Libraries
 * @category        Libraries
 * @author        Ardianta Pargo
 * @license        
 * @link        https://github.com/ardianta/codeigniter-dompdf
 */
use Dompdf\Dompdf;
class Pdf extends Dompdf{
    /**
     * @var 
     */
    public $filename;
    public function __construct(){
        parent::__construct();
       // $this->set_option("isPhpEnabled", true);
       $this->set_option("isRemoteEnabled", true);
       
        $this->filename = "users.pdf";
        $this->filename = "relations.pdf";
        $this->filename = "relationgroups.pdf";
        $this->filename = "custpricinggroups.pdf";
        $this->filename = "memberships.pdf";
    }
    /**
     * @access    protected
     * @return    
     */
    protected function ci()
    {
        return get_instance();
    }
    /**
     * @access    public
     * @param    
     * @param    
     * @return   
     */
    public function load_view($view, $data = array()){
        
        $html = $this->ci()->load->view($view, $data, TRUE);
        //echo $html;
        //echo getcwd() . "/assets/app/users/avatar/avatar_1.jpg";
        //die();
        $this->load_html($html);

        // Render the PDF

        $this->render();
        $fontMetrics = $this->getFontMetrics();
        //$font = $fontMetrics->getFont('helvetica');
        $font = $fontMetrics->getFont('sans-serif');
        
        //var_dump($this->pdf);
        //die();

        $this->get_canvas()->page_text(510, 818, "Page : {PAGE_NUM} of {PAGE_COUNT}", $font, 10, array(255,255,255));
        $this->stream($this->filename, array("Attachment" => false));

        // Output the generated PDF to Browser
        //$font = Font_Metrics::get_font("helvetica", "bold");
        //$font = $fontMetrics::get_font("helvetica", "bold");
        //$font = $this->fontMetrics->getFont('helvetica');
        

        
    }
}