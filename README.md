# health-connection-api

## DB.INI file

| Item | Value |
|:----:|:-----:|
| `url`  | `mysql:host=localhost;dbname=health-connection` |
| `user` | `username` |
| `password` | `password` |
| `type` | `iot-client` |
| `organisation` | `mbrym4` |
| `apiKey` | `a-mbrym4-rdf3lojgwv` |
| `apiToken` | `5jq*9Dca6UBOQQhQpG` |

## Apache configuration

You should update your configuration to have your API and front on different ports :

- 80 : Front in Vue.js,
- 81 : API in PHP.

First, we order to Apache to listen on 81 in addition to 80.

```console
sudo nano /etc/apache2/ports.conf
```

Your file should be like this one below :

```apacheconfig
Listen 80
Listen 81

<IfModule ssl_module>
        Listen 443
</IfModule>

<IfModule mod_gnutls.c>
        Listen 443
</IfModule>
```

Then, you sould create a file for a new available site.

```console
sudo nano /etc/apache2/sites-available/health-connection.local.conf
```

In this file, you insert the configuration below :

**Replace 'antony' by your current user : DONT USE ROOT**

```apacheconfig
<VirtualHost *:80>
    DocumentRoot /home/antony/health-connection/dist
    <Directory /home/antony/health-connection/dist>
        Options -Indexes +FollowSymLinks
        Require all granted
        AllowOverride All
        RewriteEngine on
         Header add Access-Control-Allow-Headers "origin, x-requested-with, content-type, authorization"
        Header add Access-Control-Allow-Methods "PUT, GET, POST, DELETE"
        Header set Access-Control-Allow-Origin "*"
        <IfModule mod_rewrite.c>
            RewriteEngine On
            RewriteBase /
            RewriteRule ^index\.html$ - [L]
            RewriteCond %{REQUEST_FILENAME} !-f
            RewriteCond %{REQUEST_FILENAME} !-d
            RewriteRule . /index.html [L]
        </IfModule>
     </Directory>
</VirtualHost>

<VirtualHost *:81>
        DocumentRoot "/home/antony/health-connection-api"
        <Directory /home/antony/health-connection-api>
            Options -Indexes +FollowSymLinks
            Header set Access-Control-Allow-Origin "*"
            Header add Access-Control-Allow-Headers "origin, x-requested-with, content-type, authorization"
            Header add Access-Control-Allow-Methods "PUT, GET, POST, DELETE, OPTIONS"
            Options -Indexes +FollowSymLinks
            AllowOverride All
            Require all  granted
        </Directory>
</VirtualHost>
```

Finally, enter this three commands, and it's up !

```console
# Enable new configuration
sudo a2ensite health-connection.local
# Disable default site
sudo a2dissite 000-default
# Restart apache2.service
sudo systemctl reload apache2
```

## MySQL

To install and configure MySQL, you can use this very well [tutorial](https://web-community.fr/posts/laravel-debian/#6---installation-et-configuration-de-mysql) !

Now, you have to create a new database. Be sure to be in the health-connection-api directory and enter the following command :

**Replace 'antony' by the user created in the previous tutorial**

```bash
mysql < database.sql -u antony -p
```

## Routes

### Register - POST

```json
{
  "identifiant":"antonyzer",
  "password":"Formation13@",
  "c_password":"Formation13@",
  "role":"citoyen"
}
```

### Login - POST

```json
{
  "identifiant":"antonyzer",
  "password":"Formation13@"
}
```
