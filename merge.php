<?php
use setasign\Fpdi\Fpdi;
require_once('fdpf\fpdf.php');
require_once('fdpi\src\autoload.php');



//create your class to merge pdfs
class MergePdf extends Fpdi
{
    public $pdffiles = array();

    public function setFiles($pdffiles)
    {
        $this->pdffiles = $pdffiles;
    }
    //function to merge pdf files using fpdf and fpdi.
    public function merge()
    {
        foreach($this->pdffiles AS $file) {
            $pdfCount = $this->setSourceFile($file);
            for ($pdfNo = 1; $pdfNo <= $pdfCount; $pdfNo++) {
                $pdfId = $this->ImportPage($pdfNo);
                $temp = $this->getTemplatesize($pdfId);
                $this->AddPage($temp['orientation'], $temp);
                $this->useImportedPage($pdfId);
            }
        }
    }
}

$Outputpdf = new MergePdf();
//we gave absolute path because sometimes the libraries can't detect the path. Please use your path here.
$name = $_GET['folderName'];
$items = array();
$file_data = scandir($name);
foreach($file_data as $file)
{
    if($file === '.' or $file === '..')
    {
        continue;
    }
        else
    {
        $items[] = $name. '/' . $file;

}
}

$Outputpdf->setFiles($items);

$Outputpdf->merge();

//I: The output pdf will run on the browser
//D: The output will download a merged pdf file
//F: The output will save the file to a particular path.
//We select the default I to run the output on the browser.

$Outputpdf->Output('I', 'Merged PDF.pdf');

?>