<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['csvFile'])) {
    $file = $_FILES['csvFile']['tmp_name'];
    $startIndex = isset($_POST['startIndex']) ? (int)$_POST['startIndex'] : 1;
    $endIndex = isset($_POST['endIndex']) ? (int)$_POST['endIndex'] : PHP_INT_MAX;

    if (($handle = fopen($file, 'r')) !== false) {
        $jsonData = [];
        $currentIndex = $startIndex;

        while (($data = fgetcsv($handle)) !== false) {
            if (count($data) >= 6 && $currentIndex <= $endIndex) {
                $jsonData[] = [
                    "question" => "{$currentIndex}.{$data[1]}",
                    "answer1" => $data[2],
                    "answer2" => $data[3],
                    "answer3" => $data[4],
                    "answer4" => $data[5],
                    "correct" => $data[6],
                ];
                $currentIndex++;
            }
        }
        fclose($handle);

        header('Content-Type: application/json');
        header('Content-Disposition: attachment; filename="converted.json"');
        echo json_encode($jsonData, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
        exit;
    } else {
        echo "Failed to open the uploaded file.";
    }
} else {
    echo "Invalid request.";
}
?>
