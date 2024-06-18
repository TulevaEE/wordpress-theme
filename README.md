# Running
1. Export the live database into an `.sql` file
2. Move it into `./mysql-init` directory (alternatively import with phpMyAdmin at `http://localhost:8881/`)
3. `docker-compose up`
4. Wait for `./wordpress` creation by docker and copy from live `./wp-content` subdirectories `languages`, `plugins` (delete previous `plugins` folder to be sure that it is replaced) and `uploads` to the respective folders in local `./wordpress/wp-content` `
5. Log in to phpMyAdmin (`http://localhost:8881` `wordpress`/`wordpress`) and fix site url in the database `SELECT * FROM wp_options WHERE option_name = 'siteurl' OR option_name = 'home';`. New url is `http://localhost:8880`
6. Add site url to `wp-config.php` if needed
   `define('WP_HOME', 'http://localhost:8880');
   define('WP_SITEURL', 'http://localhost:8880');`
7. Deactivate SSL enforcing plugin e.g. `really-simple-ssl` by logging in to `wp-admin` and table `options` and row `active_plugins` and remove `i:16;s:47:"really-simple-ssl/rlrsssl-really-simple-ssl.php";` entry (or deactivate the plugin via phpAdmin or file system)

##
Re-initialize the database by deleting the existing one before
```
docker-compose down
docker volume rm wordpress-theme_db_data
```


# Update theme POT file command

`php tools/i18n/makepot.php wp-theme src/wp-content/themes/tuleva/ src/wp-content/themes/tuleva/lang/tuleva.pot`

# Generate .css from .scss files

`cd src/wp-content/themes/tuleva; npm install --global gulp-cli; gulp`
