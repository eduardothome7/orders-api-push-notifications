# criação banco de dados
> mysql -u root -p
> CREATE DATABASE orders_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

# migração do banco de dados
> composer require illuminate/console
> php artisan migrate
> CREATE USER 'orders_user'@'localhost' IDENTIFIED BY 'OrdersSenha123!';
> GRANT ALL PRIVILEGES ON orders_db.* TO 'orders_user'@'localhost';
> FLUSH PRIVILEGES;

# subir servidor local
> php -S localhost:8000 -t public

# criar novas migrações
> php artisan make:migration create_orders_table --create=orders


        // $pusher = new Pusher(
        //     env('PUSHER_APP_KEY'),
        //     env('PUSHER_APP_SECRET'),
        //     env('PUSHER_APP_ID'),
        //     [
        //         'cluster' => env('PUSHER_APP_CLUSTER'),
        //         'useTLS' => true
        //     ]
        // );

        // $pusher->trigger('orders-channel', 'new-order', $order);   