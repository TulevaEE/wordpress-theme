# Running

1. Export the live database into an `.sql` file
2. Move it into `./mysql-init` directory (alternatively import with phpMyAdmin at `http://localhost:8881/`)
3. `docker-compose up`
4. Wait for `./wordpress` creation by docker and copy from live `./wp-content` subdirectories `languages`, `plugins` (delete previous `plugins` folder to be sure that it is replaced) and `uploads` to the respective folders in local `./wordpress/wp-content` `
5. Log in to phpMyAdmin (`http://localhost:8881` `wordpress`/`wordpress`) and fix site url in the database by running SQL query
`UPDATE wp_options SET option_value = 'http://localhost:8880' WHERE option_name  IN ('siteurl', 'home');`. Verify
`SELECT * FROM wp_options WHERE option_name = 'siteurl' OR option_name = 'home';`. New url is `http://localhost:8880`
6. Add site url to `wp-config.php` for example after `define( 'DB_COLLATE', getenv_docker('WORDPRESS_DB_COLLATE', '') );` row
   `define('WP_HOME', 'http://localhost:8880');
define('WP_SITEURL', 'http://localhost:8880');`
7. Deactivate SSL enforcing plugin e.g. `really-simple-ssl`:
    1. by logging in to phpMyAdmin and edit table `options` and row `active_plugins`.
    2. Save the original entry for `active_plugins` in `wp_options` table.
    3. Then remove `i:16;s:47:"really-simple-ssl/rlrsssl-really-simple-ssl.php";` entry.
    4. Then decrease the first number after `a:` by 1.
    5. Make sure to update other indices `i:<value>`, so that the positions are correct (or deactivate the plugin via phpAdmin or file system)
8. Open http://localhost:8880/, http://localhost:8880/wp-admin/. To login, either:
    1. Use your production wordpress credentials.
    2. Move `tools/create-default-user.php` to `wordpress` folder, and visit http://localhost:8880/create-default-user.php. Sign in with `admin`-`admin`

If you see an error with plugins like

```
Fatal error: Uncaught Error: Call to undefined function get_field() in /var/www/html/wp-content/themes/tuleva/lib/class-wp-bootstrap-navwalker.php:137 Stack trace: #0 /var/www/html/wp-includes/class-wp-walker.php(147): WP_Bootstrap_Navwalker->start_el('
```

, then step 7 was not done succesfully.

, you need to activate necessary plugins again in http://localhost:8880/wp-admin/plugins.php.
First initialize all plugins that can be initialized, then `WPML` plugins, then all plugins except `really-simple-ssl`.

##

Re-initialize the database by deleting the existing one before

```
docker-compose down
docker volume rm wordpress-theme_db_data
```

# CI
CI uses host `ftp.tuleva.ee`, since CloudFlare is configured to forward to the correct machine with that url.
Rsync uploads changed files using a key, which is configured in the host and CI.

# Update theme POT file script

## With WP cli

Install wp cli:
`brew install wp-cli`

1. Generate POT file `wp i18n make-pot src/wp-content/themes/tuleva src/wp-content/themes/tuleva/lang/tuleva.pot --ignore-domain`

2. Generate PO file `wp i18n update-po src/wp-content/themes/tuleva/lang/tuleva.pot  src/wp-content/themes/tuleva/lang`

3. Generate MO file `wp i18n make-mo src/wp-content/themes/tuleva/lang`

Alternatively, use POEdit for steps 2 and 3.

## With PHP script (legacy, may not work)
`php tools/i18n/makepot.php wp-theme src/wp-content/themes/tuleva/ src/wp-content/themes/tuleva/lang/tuleva.pot`


# Start listener to compile .scss files to .css

Start following npm script
```cd src/wp-content/themes/tuleva; npm install --global gulp-cli; gulp`

And edit scss files to have them built into css.
