<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['csvFile'])) {
    $file = $_FILES['csvFile']['tmp_name'];

    if (($handle = fopen($file, 'r')) !== false) {
        $jsonData = [];
        $seenQuestions = [];

        while (($data = fgetcsv($handle)) !== false) {
            if (count($data) >= 6) {
                $question = trim($data[1]);

                // যদি একই প্রশ্ন আগেই যুক্ত হয়ে থাকে তবে এটি বাদ দেওয়া হবে
                if (!in_array($question, $seenQuestions)) {
                    $jsonData[] = [
                        "question" => $question,
                        "answer1" => $data[2],
                        "answer2" => $data[3],
                        "answer3" => $data[4],
                        "answer4" => $data[5],
                        "correct" => $data[6],
                    ];
                    $seenQuestions[] = $question; // প্রশ্ন সংরক্ষণ করা হচ্ছে
                }
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
