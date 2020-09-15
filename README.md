# RbcNewsParser
Выводит список новостей с http://rbc.ru, длина спиcка ограничена 15 новостями.
## Установка
Клонировать репозиторий, перейти в директорию  последовательно выполнить команды:
- Для сборки и запуска контейнеров:
>docker-compose up -d
- Для входа в контейнер php:
>docker-compose exec php bash
- Для установки Symfony и зависимостей (потребуется токен для приватных репозиториев):
> composer install
- Для выполнений миграции БД:
>bin/console doctrine:migrations:migrate --no-interaction
- Для установки зависимостей yarn:
>yarn install
- Для сборки фронтенда:
>yarn encore dev

Сервис будет доступен по адресу http://localhost:8080/
