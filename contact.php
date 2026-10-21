<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Form Liên Hệ</title>
    <style>
        body { font-family: Arial, sans-serif; background-color: #f4f4f9; padding: 20px; }
        .container { max-width: 500px; margin: 0 auto; background: #fff; padding: 30px; border-radius: 8px; box-shadow: 0 0 10px rgba(0,0,0,0.1); }
        h2 { text-align: center; color: #333; margin-bottom: 5px; }
        p.subtitle { text-align: center; color: #666; font-size: 14px; margin-bottom: 20px; }
        .form-group { margin-bottom: 15px; }
        label { display: block; margin-bottom: 5px; font-weight: bold; font-size: 14px; }
        input[type="text"], input[type="email"], select, textarea, input[type="file"] { width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 4px; box-sizing: border-box; }
        textarea { height: 100px; resize: vertical; }
        .error { color: red; font-size: 13px; margin-top: 5px; display: block; }
        .success { background-color: #d4edda; color: #155724; padding: 15px; border-radius: 4px; margin-bottom: 20px; text-align: center; }
        button { width: 100%; padding: 12px; background-color: #1a73e8; color: white; border: none; border-radius: 4px; font-size: 16px; cursor: pointer; }
        button:hover { background-color: #155db3; }
    </style>
</head>
<body>

<div class="container">
    <h2>Liên hệ</h2>
    <p class="subtitle">Vui lòng nhập đầy đủ thông tin bên dưới.</p>

    <?php
    // Khởi tạo biến để giữ lại dữ liệu khi có lỗi[cite: 3]
    $name = $email = $subject = $message = "";
    $errors = [];
    $successMsg = "";

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Lấy dữ liệu và làm sạch
        $name = trim($_POST['name'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $subject = $_POST['subject'] ?? '';
        $message = trim($_POST['message'] ?? '');

        // 2. Kiểm tra họ tên và nội dung không rỗng[cite: 3]
        if (empty($name)) {
            $errors['name'] = "Họ tên không được để trống.";
        }
        
        if (empty($message)) {
            $errors['message'] = "Nội dung không được để trống.";
        } elseif (strlen($message) < 10 || strlen($message) > 500) {
            // Thêm validate độ dài dựa trên dòng chữ đỏ nhỏ trong ảnh thiết kế[cite: 3]
            $errors['message'] = "Nội dung phải từ 10 đến 500 ký tự.";
        }

        // 3. Kiểm tra email đúng định dạng[cite: 3]
        if (empty($email)) {
            $errors['email'] = "Email không được để trống.";
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = "Email không đúng định dạng.";
        }

        // 1. Kiểm tra Upload ảnh đại diện với 3 điều kiện bắt buộc[cite: 3]
        if (isset($_FILES['avatar']) && $_FILES['avatar']['error'] != UPLOAD_ERR_NO_FILE) {
            $fileTmp = $_FILES['avatar']['tmp_name'];
            $fileSize = $_FILES['avatar']['size'];
            $fileType = mime_content_type($fileTmp); // Lấy kiểu MIME thực tế của file
            
            $allowedTypes = ['image/jpeg', 'image/png', 'image/jpg'];
            $maxSize = 2 * 1024 * 1024; // Giới hạn 2MB

            // Điều kiện 1: Kiểm tra lỗi upload cơ bản
            if ($_FILES['avatar']['error'] !== UPLOAD_ERR_OK) {
                $errors['avatar'] = "Có lỗi xảy ra trong quá trình tải file.";
            }
            // Điều kiện 2: Kiểm tra định dạng file ảnh
            elseif (!in_array($fileType, $allowedTypes)) {
                $errors['avatar'] = "Chỉ cho phép định dạng ảnh (JPG, PNG).";
            }
            // Điều kiện 3: Kiểm tra dung lượng
            elseif ($fileSize > $maxSize) {
                $errors['avatar'] = "Dung lượng ảnh không được vượt quá 2MB.";
            }
        } else {
            $errors['avatar'] = "Vui lòng chọn ảnh đại diện tải lên.";
        }

        // 5. Hiển thị thông báo thành công nếu không có lỗi[cite: 3]
        if (empty($errors)) {
            $successMsg = "Gửi thông tin liên hệ thành công!";
            // Reset form sau khi gửi thành công
            $name = $email = $subject = $message = "";
        }
    }
    ?>

    <?php if ($successMsg): ?>
        <div class="success"><?php echo $successMsg; ?></div>
    <?php endif; ?>

    <!-- Form cần có enctype="multipart/form-data" để upload được file[cite: 3] -->
    <form method="POST" action="" enctype="multipart/form-data">
        <div class="form-group">
            <label>Họ tên</label>
            <!-- 4. Giữ lại dữ liệu đã nhập (value="<?php echo $name; ?>")[cite: 3] -->
            <input type="text" name="name" value="<?php echo htmlspecialchars($name); ?>" placeholder="Ví dụ: Lê Văn Hào">
            <?php if (isset($errors['name'])) echo "<span class='error'>{$errors['name']}</span>"; ?>
        </div>

        <div class="form-group">
            <label>Email</label>
            <input type="text" name="email" value="<?php echo htmlspecialchars($email); ?>" placeholder="Ví dụ: haovanle@edu.vn">
            <?php if (isset($errors['email'])) echo "<span class='error'>{$errors['email']}</span>"; ?>
        </div>

        <div class="form-group">
            <label>Chủ đề</label>
            <select name="subject">
                <option value="Hỗ trợ kỹ thuật" <?php if ($subject == 'Hỗ trợ kỹ thuật') echo 'selected'; ?>>Hỗ trợ kỹ thuật</option>
                <option value="Báo lỗi" <?php if ($subject == 'Báo lỗi') echo 'selected'; ?>>Báo lỗi</option>
                <option value="Góp ý" <?php if ($subject == 'Góp ý') echo 'selected'; ?>>Góp ý</option>
            </select>
        </div>

        <div class="form-group">
            <label>Nội dung</label>
            <textarea name="message" placeholder="Nhập nội dung liên hệ..."><?php echo htmlspecialchars($message); ?></textarea>
            <?php if (isset($errors['message'])) echo "<span class='error'>{$errors['message']}</span>"; ?>
        </div>

        <div class="form-group">
            <label>Ảnh đại diện (Bắt buộc, < 2MB, JPG/PNG)</label>
            <input type="file" name="avatar" accept="image/png, image/jpeg">
            <?php if (isset($errors['avatar'])) echo "<span class='error'>{$errors['avatar']}</span>"; ?>
        </div>

        <button type="submit">Gửi liên hệ</button>
    </form>
</div>

</body>
</html>