<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Document</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://getbootstrap.com/docs/5.3/assets/css/docs.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        .editor-content {
            min-height: 300px;
            border: 1px solid #dee2e6;
            padding: 15px;
            border-radius: 5px;
        }
        .page-card {
            transition: transform 0.2s;
        }
        .page-card:hover {
            transform: translateY(-2px);
        }
        .editor-toolbar {
            border: 1px solid #dee2e6;
            border-radius: 0.375rem 0.375rem 0 0;
            padding: 0.5rem;
            background-color: #f8f9fa;
        }
        #editor {
            min-height: 300px;
            border: 1px solid #dee2e6;
            border-top: none;
            border-radius: 0 0 0.375rem 0.375rem;
            padding: 1rem;
        }
        .category-badge {
            cursor: pointer;
        }
        .article-card {
            transition: all 0.2s ease;
        }
        .article-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
        }
        .sidebar {
            height: calc(100vh - 56px);
            position: sticky;
            top: 56px;
            overflow-y: auto;
        }
        .content-area {
            height: calc(100vh - 56px);
            overflow-y: auto;
        }
    </style>

</head>
<body style="padding-left: 5%;  padding-right: 5%;">
<?php include 'menu.php';?>
<div>
    <img src="/title2.png" class="img-fluid" >
</div>

<div class="mb-3" style="padding-top: 20px;padding-left: 10%;  padding-right: 10%;">
<?php if(isAuthorized()):?>

                    <div class="card-body">
                        <form id="pageForm" <?php if($wikiTopic[0]['id']===0):?> action="?page=article&new" <?php else: ?> action="?page=article&editid=$wikiTopic[0]['id']"<?php endif;?> method="post">
                            <input type="hidden" name="page_id" id="page_id" value="<?=$wikiTopic[0]['id']?>">
                            <div class="mb-3">
                                <label for="title" class="form-label">Заголовок</label>
                                <input type="text" class="form-control" id="title" name="title" value="<?=$wikiTopic[0]['theme']?>" required>
                            </div>
                            <div class="mb-3">
                                <label for="description" class="form-label">Заголовок</label>
                                <input type="text" class="form-control" id="description" name="description" value="<?=$wikiTopic[0]['description']?>">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Категория</label>
                                           <select class="form-select" aria-label="Категория" id="category" >
                                               <?php if(isset($AllCategories)):?>
                                                    <?php foreach ($AllCategories as $OneCat): ?>
                                                        <option value="<?=$OneCat['id']?>" <?php if($wikiTopic[0]['idcategory']===$OneCat['id']):?> selected <?php endif;?>><?=$OneCat['name']?></option>
                                                    <?php endforeach; ?>
                                                <?php endif;?>
                                           </select>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Теги</label>
                                <input type="text" class="form-control" placeholder="Теги" id="tags" value="<?=$wikiTopic[0]['tags']?>">
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Содержание</label>
                                <div class="editor-toolbar mb-2">
                                    <button type="button" class="btn btn-sm btn-outline-secondary" onclick="formatText('bold')">
                                        <i class="bi bi-type-bold"></i>
                                    </button>
                                    <button type="button" class="btn btn-sm btn-outline-secondary" onclick="formatText('italic')">
                                        <i class="bi bi-type-italic"></i>
                                    </button>
                                    <button type="button" class="btn btn-sm btn-outline-secondary" onclick="formatText('underline')">
                                        <i class="bi bi-type-underline"></i>
                                    </button>
                                    <button type="button" class="btn btn-sm btn-outline-secondary" onclick="insertHeading()">
                                        <i class="bi bi-type-h1"></i>
                                    </button>
                                    <button type="button" class="btn btn-sm btn-outline-secondary" onclick="insertList('ul')">
                                        <i class="bi bi-list-ul"></i>
                                    </button>
                                    <button type="button" class="btn btn-sm btn-outline-secondary" onclick="insertList('ol')">
                                        <i class="bi bi-list-ol"></i>
                                    </button>
                                    <button type="button" class="btn btn-sm btn-outline-secondary" onclick="insertLink()">
                                        <i class="bi bi-link"></i>
                                    </button>
                                    <button type="button" class="btn btn-sm btn-outline-secondary" onclick="insertImage()">
                                        <i class="bi bi-image"></i>
                                    </button>
                                </div>
                                <div
                                    class="editor-content form-control"
                                    id="content"
                                    contenteditable="true"
                                    oninput="updateHiddenContent()"
                                ><?=$wikiTopic[0]['text']?></div>
                                <textarea type="text" name="content" id="hiddenContent" style="display: none;"></textarea>
                            </div>
                            <div class="row g-7">
                                <div class="col-sm-11">
                                    <button type="button" class="btn btn-primary" onclick="saveChanges()">Сохранить</button>
                                    <button type="button" class="btn btn-outline-primary" onclick="location.href='<?= $ReturnURL ?>'"  id="deleteBtn" >Отмена</button>
                                </div>
                                <div class="col-sm-1">
                                    <button type="button" class="btn btn-danger" onclick="deletePage()" id="deleteBtn" >Удалить </button>
                                </div>
                            </div>
                        </form>
                    </div>



<?php else: ?>
    <div class="alert alert-danger" role="alert">
        Вы не имеете доступа к данной странице - авторизируйтесь
    </div>
<?php endif;?>
</div>
<script>
        function formatText(command) {
            document.execCommand(command, false, null);
            updateHiddenContent();
        }

        function insertHeading() {
            const selection = window.getSelection();
            if (selection.rangeCount > 0) {
                const range = selection.getRangeAt(0);
                const h2 = document.createElement('h2');
                h2.textContent = 'Заголовок';
                range.insertNode(h2);
                updateHiddenContent();
            }
        }

        function insertList(type) {
            document.execCommand('insert' + (type === 'ul' ? 'Unordered' : 'Ordered') + 'List', false, null);
            updateHiddenContent();
        }

        function insertLink() {
            const url = prompt('Введите URL:');
            if (url) {
                document.execCommand('createLink', false, url);
                updateHiddenContent();
            }
        }

        function insertImage() {
            const url = prompt('Введите URL изображения:');
            if (url) {
                document.execCommand('insertImage', false, url);
                updateHiddenContent();
            }
        }

        function updateHiddenContent() {
            document.getElementById('hiddenContent').value = document.getElementById('content').innerHTML;
        }


        async function deletePage() {
            if (confirm('Вы уверены, что хотите удалить эту страницу?')) {
                 $id = document.getElementById('page_id').value;
                 console.log($id);
                const response = await fetch('?page=article&api&action=delete', {
                                        method: 'POST',
                                        body: JSON.stringify({id: $id
                                    })
                            });
                console.log(response);
                const result = await response.json();
                console.log(result);

                if (result.success===true) {
                    alert('Данные успешно удалены!');
                    document.location.href = '?page=wiki&pg=1&curpg=1';

                } else {
                    alert('Ошибка: ' + result.message);
                }

            }
        }
    //аналог ф-ции в php
    function htmlspecialchars(str) {
        str = str.replace(/&/g, '&amp;').replace(/"/g, '&quot;').replace(/'/g, '&#039;').replace(/</g, '&lt;').replace(/>/g, '&gt;');
        return str;
    }

    async function saveChanges() {
        $id = document.getElementById('page_id').value;
        $title = document.getElementById('title').value;
        $description = (document.getElementById('description').value);
        $tags = (document.getElementById('tags').value);
        $idcategory = (document.getElementById('category').value);
        $text = (document.getElementById('content').innerHTML);


        try {
             const response = await fetch('?page=article&api&action=edit', {
                                 method: 'POST',
                                 body: JSON.stringify({id: $id,
                                    title: $title,
                                    description: $description,
                                    tags: $tags,
                                    idcategory: $idcategory,
                                    text: $text

                                 })
                             });
            const result = await response.json();

            if (result.success===true) {
                alert('Данные успешно сохранены!');
                document.location.href = '?page=article&id='+result.returnID;

            } else {
                alert('Ошибка: ' + result.message);
            }
        } catch (error) {
            console.error('Ошибка:', error);
            alert('Произошла ошибка при сохранении данных');
        }
    }

    </script>
</body>
</html>
