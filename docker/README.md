# SaaSCommon - Docker

## Getting started

-   copy `src\.env.example` to `.env` file or run command:

```
    cp .env.example .env
```

-   RUN command:

```
    docker compose build
    docker compose up -d
```

-   After Docker Running, Let's install dependencies for Backend:

```
    docker ps
    //find container [container_app]
    docker exec -it [container_app] sh
    cd backend
    composer install
    cp .env.example .env
    php artisan key:generate
    php artisan migrate:fresh --seed
```
