# Тестовое задание
Stack: PHP 7.4+, MySQL 5.7+  
Frameworks: Нативный PHP, либо Laravel.  
Задача: На ftp сервер проекта раз в сутки выгружается XML-файл с данными по стоку. С каждой новой
выгрузкой данные меняются - одни данные могут обновиться, другие добавиться, третьи удалиться (их не
будет в новом XML-файле). Необходимо разработать архитектуру БД на основе XML-выгрузки и написать
парсер XML-файла.  
Парсер должен:  
- добавлять в базу записи, которых в ней еще нет;
- обновлять записи, которые пришли в XML и уже есть в базе;
- удалять записи из базы, которых нет в XML (чистить таблицу перед парсингом нельзя). 

Парсер должен запускаться через консольную команду. При вызове консольной команды должна быть
возможность указать путь до локального файла выгрузки, при этом, если путь до файла не указан, то
берется дефолтный файл.
При проектировании архитектуры БД необходимо учитывать, что по всем полям, кроме id и
generation_id , будет происходить фильтрации данных.
Критерии оценки реализации:
1. Выполени всех требований в задании;
2. Соблюдение стандартов PSR и чистота кода;
3. Naming классов, методов и переменных;
4. Набор принципов программирования, используемых для реализации задания;
5. Структура таблиц, типы данных колонок и naming в БД.

Развёртывание проекта для linux:
- git clone https://github.com/meepozZza/test-carstock.git
- cd ./test-carstock/docker
- docker-compose up -d --force --build
- docker exec -it carstock.php-fpm bash
- php artisan migrate

Artisan команда парсера: php artisan parse:xml {file}

Локальный адрес проекта: https://carstock.localhost/
