<p align="center">Crea tu Evento</p>

## Requerimientos

- npm -v 3.10.10
- composer -V 1.4.1
- PHP -v >=5.6.4 - 7.1.4



## Uso
1. **clone** : git clone https://github.com/micatw11/Crea-tu-Evento.git
2. **php dependencias** : composer install
3. **js dependencias** : npm install
4. **.env** : archivo de entorno .env
    - `configuración de base de datos` :
        ```
        DB_CONNECTION=
        DB_HOST=
        DB_PORT=
        DB_DATABASE=
        DB_USERNAME=
        DB_PASSWORD=
        ```

    -  `key` : php artisan key:generate
5. **migraciones** : 
    - php artisan migrate
    - php artisan db:seed
6. **storage**: php artisan storage:link
7. node_modules/.bin/webpack --hide-modules --progress 
8. php artisan serve

