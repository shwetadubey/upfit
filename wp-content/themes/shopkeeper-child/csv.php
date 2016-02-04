<?php
/*
 * Template Name: csv import
 */
header('Content-Type: text/csv');
$d=date('d_m_Y');
header('Content-Disposition: attachment; filename="Questionnaire_reports_'.$d.'.csv"');
if (isset($_POST['csv'])) {
    $csv = $_POST['csv'];
    echo $csv;
}
?>