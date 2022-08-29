# Cloud Storage
Прототип облачного хранилища.
## Особенности
- Авторизация и регистрация
- Квота для каждого пользователя: 100 МБ
- Максимальный размер файла: 20 МБ
- Загрузка, переименование, удаление и скачивание файлов
- Запрещено загружать файлы с расширением .php
- При загрузке файла можно указать дату и время, после которой он будет удалён
- Создание папок (без подпапок)
- Создание уникальных публичных ссылок на файлы

## Как запустить?
(*Проверено на Debian 11*)

### Требования
PHP 8
### Инструкция
Выполните следующую последовательность команд
```shell
apt install git docker docker-compose composer
```
```shell
cd /var/www && git clone https://github.com/markstalker/cloudstorage html && cd html
```
```shell
composer install --ignore-platform-reqs
```
Создайте .env файл
```shell
cp .env.example .env && chmod 777 .env
```
Установите следующие значения в .env
```shell
APP_DEBUG=false
DB_HOST=mariadb
DB_DATABASE=laravel
DB_USERNAME=sail
DB_PASSWORD=password
```
Запустите sail. После окончания установки и запуска контейнеров прервите процесс (Ctrl+C)
```shell
./vendor/bin/sail up
```
Установите права доступа на папку storage
```shell
chmod 777 -R storage/
```
Запустите sail снова.
```shell
./vendor/bin/sail up -d
```
Сгенерируйте ключ приложения
```shell
./vendor/bin/sail artisan key:generate
```
Запустите миграции
```shell
./vendor/bin/sail artisan migrate
```
