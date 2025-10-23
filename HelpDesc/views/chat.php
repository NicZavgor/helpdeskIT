<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Чат приложение</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Иконки Bootstrap -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <style>
        .chat-container {
            height: 80vh;
            border: 1px solid #dee2e6;
            border-radius: 10px;
            overflow: hidden;
        }
        .chat-header {
            background-color: #0d6efd;
            color: white;
            padding: 15px;
        }
        .messages {
            height: calc(100% - 120px);
            overflow-y: auto;
            padding: 15px;
        }
        .message-input {
            border-top: 1px solid #dee2e6;
            padding: 15px;
            background-color: #f8f9fa;
        }
        .message {
            margin-bottom: 10px;
            max-width: 70%;
            padding: 10px 15px;
            border-radius: 15px;
        }
        .received {
            background-color: #e9ecef;
            align-self: flex-start;
        }
        .sent {
            background-color: #0d6efd;
            color: white;
            align-self: flex-end;
        }
        .user-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            margin-right: 10px;
        }
        .typing-indicator {
            font-style: italic;
            color: #6c757d;
            padding: 5px 15px;
        }
    </style>
</head>
<body>
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="chat-container d-flex flex-column">
                    <!-- Шапка чата -->
                    <div class="chat-header d-flex justify-content-between align-items-center">
                        <div class="d-flex align-items-center">
                            <img src="https://via.placeholder.com/40" alt="Аватар" class="user-avatar">
                            <h5 class="mb-0">Групповой чат</h5>
                        </div>
                        <div>
                            <button class="btn btn-sm btn-light">
                                <i class="bi bi-people-fill"></i> 3
                            </button>
                            <button class="btn btn-sm btn-light">
                                <i class="bi bi-gear-fill"></i>
                            </button>
                        </div>
                    </div>

                    <!-- Сообщения -->
                    <div class="messages d-flex flex-column">
                        <div class="text-center text-muted small mb-3">Сегодня, 14:30</div>

                        <!-- Полученное сообщение -->
                        <div class="d-flex mb-2">
                            <img src="https://via.placeholder.com/40" alt="Аватар" class="user-avatar">
                            <div>
                                <div class="small text-muted">Алексей</div>
                                <div class="message received">Привет! Как дела?</div>
                            </div>
                        </div>

                        <!-- Отправленное сообщение -->
                        <div class="d-flex flex-row-reverse mb-2">
                            <div>
                                <div class="small text-muted text-end">Вы</div>
                                <div class="message sent">Привет! Все отлично, спасибо!</div>
                            </div>
                        </div>

                        <!-- Полученное сообщение -->
                        <div class="d-flex mb-2">
                            <img src="https://via.placeholder.com/40" alt="Аватар" class="user-avatar">
                            <div>
                                <div class="small text-muted">Мария</div>
                                <div class="message received">Ребята, посмотрите это: https://example.com</div>
                            </div>
                        </div>

                        <!-- Индикатор набора сообщения -->
                        <div class="typing-indicator">
                            Алексей печатает...
                        </div>
                    </div>

                    <!-- Поле ввода сообщения -->
                    <div class="message-input">
                        <div class="input-group">
                            <button class="btn btn-outline-secondary" type="button">
                                <i class="bi bi-paperclip"></i>
                            </button>
                            <input type="text" class="form-control" placeholder="Напишите сообщение...">
                            <button class="btn btn-primary" type="button">
                                <i class="bi bi-send-fill"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>