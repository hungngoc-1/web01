<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Máy tính điểm học phần</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        .container { max-width: 400px; margin: 0 auto; padding: 20px; border: 1px solid #ccc; border-radius: 5px; }
        .form-group { margin-bottom: 15px; }
        label { display: block; margin-bottom: 5px; }
        input[type="text"], input[type="number"] { width: 100%; padding: 8px; box-sizing: border-box; }
        button { padding: 10px 15px; background-color: #28a745; color: white; border: none; cursor: pointer; }
        .error { color: red; margin-bottom: 15px; }
        .result { background-color: #e9ecef; padding: 15px; margin-top: 20px; border-radius: 5px; }
    </style>
</head>
<body>

<div class="container">
    <h2>Máy tính điểm</h2>

    <?php
    // Hàm kiểm tra điểm hợp lệ (từ 0 đến 10)[cite: 2]
    function isValidScore($score) {
        return is_numeric($score) && $score >= 0 && $score <= 10;
    }

    // Hàm tính điểm tổng kết[cite: 2]
    function finalScore($attendance, $midterm, $final) {
        // Công thức: 10% chuyên cần, 30% giữa kỳ, 60% cuối kỳ[cite: 2]
        return ($attendance * 0.1) + ($midterm * 0.3) + ($final * 0.6);
    }

    // Khởi tạo các biến để giữ lại dữ liệu trên form khi có lỗi[cite: 2]
    $name = $attendance = $midterm = $final = "";
    $errorMsg = $resultMsg = "";

    // Xử lý khi form được submit bằng phương thức POST[cite: 2]
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $name = htmlspecialchars($_POST['name']); // Bảo vệ chống XSS
        $attendance = $_POST['attendance'];
        $midterm = $_POST['midterm'];
        $final = $_POST['final'];

        // Kiểm tra dữ liệu đầu vào[cite: 2]
        if (!isValidScore($attendance) || !isValidScore($midterm) || !isValidScore($final)) {
            $errorMsg = "Lỗi: Vui lòng nhập điểm hợp lệ trong khoảng từ 0 đến 10!";
        } else {
            // Tính toán nếu điểm hợp lệ
            $tongKet = finalScore($attendance, $midterm, $final);
            
            // Xếp loại[cite: 2]
            $xepLoai = "";
            if ($tongKet >= 8.0) {
                $xepLoai = "Giỏi";
            } elseif ($tongKet >= 6.5) {
                $xepLoai = "Khá";
            } elseif ($tongKet >= 5.0) {
                $xepLoai = "Trung bình";
            } else {
                $xepLoai = "Chưa đạt";
            }

            $resultMsg = "Sinh viên: <b>$name</b><br>";
            $resultMsg .= "Điểm tổng kết: <b>" . number_format($tongKet, 2) . "</b><br>";
            $resultMsg .= "Xếp loại: <b>$xepLoai</b>";
        }
    }
    ?>

    <!-- Hiển thị thông báo lỗi nếu có -->
    <?php if ($errorMsg): ?>
        <div class="error"><?php echo $errorMsg; ?></div>
    <?php endif; ?>

    <!-- Form sử dụng POST, value="..." dùng để giữ lại dữ liệu khi có lỗi[cite: 2] -->
    <form method="POST" action="">
        <div class="form-group">
            <label>Họ và tên:</label>
            <input type="text" name="name" value="<?php echo $name; ?>" required>
        </div>
        <div class="form-group">
            <label>Điểm chuyên cần (10%):</label>
            <input type="number" step="0.1" name="attendance" value="<?php echo $attendance; ?>" required>
        </div>
        <div class="form-group">
            <label>Điểm giữa kỳ (30%):</label>
            <input type="number" step="0.1" name="midterm" value="<?php echo $midterm; ?>" required>
        </div>
        <div class="form-group">
            <label>Điểm cuối kỳ (60%):</label>
            <input type="number" step="0.1" name="final" value="<?php echo $final; ?>" required>
        </div>
        <button type="submit">Tính Điểm</button>
    </form>

    <!-- Hiển thị kết quả -->
    <?php if ($resultMsg && !$errorMsg): ?>
        <div class="result">
            <?php echo $resultMsg; ?>
        </div>
    <?php endif; ?>
</div>

</body>
</html>