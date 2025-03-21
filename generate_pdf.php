<?php
require('fpdf/fpdf.php'); 
require('db.php'); 

class PDF extends FPDF {
    function Header() {
        $this->SetFont('Arial', 'B', 12);
        $this->SetFillColor(180, 200, 250);
        
        $this->Cell(10, 10, 'S.No', 1, 0, 'C', true);
        $this->Cell(30, 10, 'Name', 1, 0, 'C', true);
        $this->Cell(35, 10, 'Task Name', 1, 0, 'C', true);
        $this->Cell(85, 10, 'Task Description', 1, 0, 'C', true);
        $this->Cell(30, 10, 'Status', 1, 1, 'C', true);
    }

    function RowWithDifferentHeights($data) {
        $cellWidths = [10, 30, 35, 85, 30]; 
        $maxHeight = 6; 

        $descLines = $this->NbLines($cellWidths[3], $data[3]); 
        $descHeight = $descLines * 6; 
        $rowHeight = max($maxHeight, $descHeight);

        if ($this->GetY() + $rowHeight > 270) {  
            $this->AddPage();
        }

        for ($i = 0; $i < count($data); $i++) {
            if ($i == 3) {
                $x = $this->GetX();
                $y = $this->GetY();
                $this->MultiCell($cellWidths[$i], 6, $data[$i], 1, 'L');
                $this->SetXY($x + $cellWidths[$i], $y);
            } else {
                $this->Cell($cellWidths[$i], $rowHeight, $data[$i], 1, 0, 'L');
            }
        }
        
        $this->Ln($rowHeight);
    }

    function NbLines($w, $txt) {
        $cw = &$this->CurrentFont['cw'];
        if ($w == 0) $w = $this->w - $this->rMargin - $this->x;
        $wmax = ($w - 2 * $this->cMargin) * 1000 / $this->FontSize;
        $s = str_replace("\r", '', $txt);
        $nb = strlen($s);
        if ($nb > 0 && $s[$nb - 1] == "\n") $nb--;
        $sep = -1;
        $i = 0;
        $j = 0;
        $l = 0;
        $nl = 1;
        while ($i < $nb) {
            $c = $s[$i];
            $l += $cw[$c];
            if ($c == ' ') $sep = $i;
            if ($l > $wmax) {
                if ($sep == -1) {
                    if ($i == $j) $i++;
                } else {
                    $i = $sep + 1;
                }
                $sep = -1;
                $j = $i;
                $l = 0;
                $nl++;
            } else {
                $i++;
            }
        }
        return $nl;
    }
}

$pdf = new PDF();
$pdf->AddPage();
$pdf->SetFont('Arial', '', 10);
$pdf->SetFillColor(240, 240, 240);

$search_id = isset($_GET['search_id']) ? $_GET['search_id'] : '';

$query = "SELECT employees.employee_name, tasks.task_name, tasks.task_description, tasks.status 
          FROM tasks 
          JOIN employees ON tasks.employee_id = employees.employee_id";
if ($search_id !== '') {
    $query .= " WHERE employees.employee_id = ?";
}
$stmt = $conn->prepare($query);
if ($search_id !== '') {
    $stmt->bind_param("i", $search_id);
}
$stmt->execute();
$result = $stmt->get_result();

$sno = 1;
while ($row = $result->fetch_assoc()) {
    $pdf->RowWithDifferentHeights([
        $sno, 
        $row['employee_name'], 
        $row['task_name'], 
        $row['task_description'], 
        $row['status']
    ]);
    $sno++;
}

$pdf->Output();
?>