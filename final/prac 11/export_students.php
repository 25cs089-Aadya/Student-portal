<?php
require_once 'auth.php'; require_once 'db.php';
$stmt=$conn->prepare("SELECT student_id,name,username,email,mobile,course,year,gender,status FROM students WHERE role='Student' ORDER BY student_id");
$stmt->execute(); $result=$stmt->get_result();
header('Content-Type: text/csv; charset=UTF-8');
header('Content-Disposition: attachment; filename="student_records.csv"');
$out=fopen('php://output','w');
fwrite($out, "\xEF\xBB\xBF");
fputcsv($out,['ID','Name','Username','Email','Mobile','Course','Year','Gender','Status']);
while($row=$result->fetch_assoc()) {
    // Prevent Excel interpreting user-entered strings as formulas.
    foreach($row as &$cell) {
        $cell=(string)$cell;
        if (preg_match('/^[\s]*[=+@\-]/u',$cell)) $cell="'".$cell;
    }
    unset($cell);
    fputcsv($out,array_values($row));
}
fclose($out);
