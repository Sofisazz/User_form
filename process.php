<?php
require_once 'config.php';

$errors = [];

$position = trim($_POST['position'] ?? '');
$applicant_name = trim($_POST['applicant_name'] ?? '');
$resume_link = trim($_POST['resume_link'] ?? '');
$cover_letter = trim($_POST['cover_letter'] ?? '');

// Валидация
if (empty($position)) $errors[] = "Укажите должность.";
if (empty($applicant_name)) $errors[] = "Введите ФИО.";
if (empty($resume_link)) {
    $errors[] = "Укажите ссылку на резюме.";
} elseif (!filter_var($resume_link, FILTER_VALIDATE_URL)) {
    $errors[] = "Ссылка на резюме некорректна.";
}
if (empty($cover_letter)) $errors[] = "Напишите сопроводительное письмо.";

// Общие стили (встроенные для простоты)
$commonStyles = '
  <style>
    body {
      background: linear-gradient(135deg, #f5f7fa 0%, #e4edf9 100%);
      min-height: 100vh;
      padding: 2rem 0;
      font-family: "Segoe UI", system-ui, sans-serif;
    }
    .card {
      border: none;
      border-radius: 16px;
      box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
      max-width: 600px;
      margin: 0 auto;
      background: white;
    }
    .btn-primary, .btn-secondary {
      padding: 0.65rem 1.25rem;
      font-weight: 600;
      border: none;
      border-radius: 8px;
      transition: all 0.25s ease;
    }
    .btn-primary {
      background: linear-gradient(to right, #4361ee, #3a0ca3);
    }
    .btn-secondary {
      background: #6c757d;
    }
    .btn-primary:hover {
      transform: translateY(-2px);
      box-shadow: 0 4px 12px rgba(67, 97, 238, 0.4);
    }
    .btn-secondary:hover {
      background: #5a6268;
    }
    .alert {
      border: none;
      border-radius: 12px;
      padding: 1.5rem;
    }
    .alert-success {
      background: linear-gradient(to right, #4cc9f0, #4361ee);
      color: white;
    }
    .alert-danger {
      background: linear-gradient(to right, #f72585, #b5179e);
      color: white;
    }
    .alert ul {
      text-align: left;
      padding-left: 1.25rem;
      margin-top: 1rem;
    }
    .alert li {
      margin-bottom: 0.4rem;
    }
  </style>
';

if (!empty($errors)) {
    echo '<!DOCTYPE html><html lang="ru"><head><meta charset="UTF-8"><title>Ошибки заполнения</title>';
    echo '<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">';
    echo $commonStyles;
    echo '</head><body>';
    echo '<div class="container">';
    echo '<div class="card mt-4">';
    echo '<div class="alert alert-danger text-center">';
    echo '<h4 class="mb-3">❌ Исправьте ошибки</h4>';
    echo '<ul class="text-start">';
    foreach ($errors as $error) {
        echo "<li>" . htmlspecialchars($error) . "</li>";
    }
    echo '</ul>';
    echo '<a href="form.html" class="btn btn-secondary mt-3">← Вернуться к форме</a>';
    echo '</div></div></div></body></html>';
    exit;
}

// Подготовленный запрос
$stmt = $mysqli->prepare("INSERT INTO job_applications (position, applicant_name, resume_link, cover_letter) VALUES (?, ?, ?, ?)");
$stmt->bind_param("ssss", $position, $applicant_name, $resume_link, $cover_letter);

if ($stmt->execute()) {
    echo '<!DOCTYPE html><html lang="ru"><head><meta charset="UTF-8"><title>Успех!</title>';
    echo '<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">';
    echo $commonStyles;
    echo '</head><body>';
    echo '<div class="container">';
    echo '<div class="card mt-5">';
    echo '<div class="alert alert-success text-center">';
    echo '<h3 class="mb-3">✅ Заявка успешно отправлена!</h3>';
    echo '<p class="fs-5">Спасибо, <strong>' . htmlspecialchars($applicant_name) . '</strong>!<br>Мы внимательно изучим ваше резюме и свяжемся в ближайшее время.</p>';
    echo '<a href="form.html" class="btn btn-primary mt-3">Отправить ещё одну заявку</a>';
    echo '</div></div></div></body></html>';
} else {
    echo '<!DOCTYPE html><html lang="ru"><head><meta charset="UTF-8"><title>Ошибка</title>';
    echo '<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">';
    echo $commonStyles;
    echo '</head><body>';
    echo '<div class="container">';
    echo '<div class="card mt-5">';
    echo '<div class="alert alert-danger text-center">';
    echo '<h4>❌ Не удалось сохранить заявку</h4>';
    echo '<p>Попробуйте позже или свяжитесь с поддержкой.</p>';
    echo '<a href="form.html" class="btn btn-secondary mt-3">← Попробовать снова</a>';
    echo '</div></div></div></body></html>';
}

$stmt->close();
$mysqli->close();
?>