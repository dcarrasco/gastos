<html xmlns:x=\"urn:schemas-microsoft-com:office:excel\">
<?php
$this->output->set_header("Content-type: application/excel");
$this->output->set_header("Content-Disposition: attachment; filename=exceldata.xls");
$this->output->set_header("Pragma: no-cache");
$this->output->set_header("Expires: 0");

$this->output->set_header("<!--[if gte mso 9]>");
$this->output->set_header("<xml>");
$this->output->set_header("<x:ExcelWorkbook>");
$this->output->set_header("<x:ExcelWorksheets>");
$this->output->set_header("<x:ExcelWorksheet>");
//this line names the worksheet
$this->output->set_header("<x:Name>gridlineTest</x:Name>");
$this->output->set_header("<x:WorksheetOptions>");
//these 2 lines are what works the magic
$this->output->set_header("<x:Panes>");
$this->output->set_header("</x:Panes>");
$this->output->set_header("</x:WorksheetOptions>");
$this->output->set_header("</x:ExcelWorksheet>");
$this->output->set_header("</x:ExcelWorksheets>");
$this->output->set_header("</x:ExcelWorkbook>");
$this->output->set_header("</xml>");
$this->output->set_header("<![endif]-->");
?>
<body>