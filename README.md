# Как поднять приложение
1) В папку домена клонировать репозиторий

        git clone https://github.com/Donvalent/MyLibrary
        
2) Открыть проект в Phpstorm
3) Загрузить все вендоры

        composer install
        
4) Создать .env.local файл и настроить под свою базу данных
5) Создать базу данных

        php bin/console doctrine:database:create
        
6) Выполнить миграцию таблиц в базу данных

        php bin/console doctrine:migrations:migrate
        
7) (необязательно) Заполнить базу данных случайными данными

        php bin/console doctrine:fixtures:load
        
Готово!

Просмотр всех книг: /books
Редактирование книги: по кнопке, либо /book/edit/{id книги}
Добавление книги: /book/create

Как так, у одной книги может быть много авторов, а у автора может быть много книг, добавил страницу добавления автора
Добавления автора: /author/create
