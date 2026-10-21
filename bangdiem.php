<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Bảng điểm sinh viên</title>
    <style>
        table { border-collapse: collapse; width: 60%; margin: 20px auto; font-family: Arial, sans-serif; }
        th, td { border: 1px solid #dddddd; text-align: center; padding: 10px; }
        th { background-color: #4CAF50; color: white; }
        .dat { color: green; font-weight: bold; }
        .khong-dat { color: red; font-weight: bold; }
    </style>
</head>
<body>

<h2 style="text-align: center;">Bảng Điểm Sinh Viên</h2>

<?php
// 1. Tạo mảng `$students` gồm 3 sinh viên với name, midterm, final[cite: 4].
$students = [
    ["name" => "Nguyễn Văn A", "midterm" => 7.5, "final" => 8.0],
    ["name" => "Trần Thị B <script>", "midterm" => 4.0, "final" => 5.5],
    ["name" => "Lê Văn C", "midterm" => 3.0, "final" => 4.0]
];

// 2. Viết hàm `calculateAverage()` tính điểm trung bình[cite: 4].
function calculateAverage($midterm,$final) {
    return ($midterm +$final) / 2;
}
?>

<table>
    <thead>
        <tr>
            <th>Tên sinh viên</th>
            <th>Điểm giữa kỳ</th>
            <th>Điểm cuối kỳ</th>
            <th>Điểm trung bình</th>
            <th>Kết quả</th>
        </tr>
    </thead>
    <tbody>
        <?php
        // 3. Dùng `foreach` để in bảng HTML[cite: 4].
        foreach ($students as $student) {$average = calculateAverage($student['midterm'],$student['final']);
            
            // 4. Thêm cột kết quả: Đạt nếu điểm trung bình >= 5[cite: 4].
            if ($average >= 5) {$resultText = "Đạt";
                $resultClass = "dat";
            } else {
                $resultText = "Không đạt";
                $resultClass = "khong-dat";
            }
            
            // 5. Mã hóa tên sinh viên bằng `htmlspecialchars()` trước khi in[cite: 4].
            $safe_name = htmlspecialchars($student['name']);
            
            echo "<tr>";
            echo "<td>{$safe_name}</td>";
            echo "<td>{$student['midterm']}</td>";
            echo "<td>{$student['final']}</td>";
            echo "<td>{$average}</td>";
            echo "<td class='{$resultClass}'>{$resultText}</td>";
            echo "</tr>";
        }
        ?>
    </tbody>
</table>

</body>
</html>