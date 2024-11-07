## Running with Docker

1. Export the live database into an `.sql` file
2. Move it into `./mysql-init` directory (alternatively import with phpMyAdmin at `http://localhost:8881/`)
3. Start docker containers `docker compose up`
4. Wait for `./wordpress` creation by docker
5. Copy `languages`, `uploads`, `plugins` (delete previous local `plugins` folder to make sure it is replaced), from live `wp-content` to `./wordpress/wp-content`
6. Log in to phpMyAdmin (`http://localhost:8881` `wordpress`/`wordpress`) and fix site url in the database by running following SQL query in SQL tab.
  * `UPDATE wp_options SET option_value = 'http://localhost:8880' WHERE option_name IN ('siteurl', 'home');`
  * Verify with
`SELECT * FROM wp_options WHERE option_name = 'siteurl' OR option_name = 'home';`.
    * New url should be `http://localhost:8880` for both.
7. Add site url to `wp-config.php`
* After `define( 'DB_COLLATE', getenv_docker('WORDPRESS_DB_COLLATE', '') );` add rows:
* `define('WP_HOME', 'http://localhost:8880'); define('WP_SITEURL', 'http://localhost:8880');`
8. Deactivate SSL enforcing plugin e.g. `really-simple-ssl`:
    1. Log in to phpMyAdmin and edit table `options` and row `active_plugins`.
    2. Copy the original entry for `active_plugins` in `wp_options` table to somewhere for backup.
    3. Remove `i:16;s:47:"really-simple-ssl/rlrsssl-really-simple-ssl.php";` entry.
    4. Decrease the first number after `a:` by 1.
    5. Make sure to update other indices `i:<value>`, so that the positions are correct (or deactivate the plugin via phpAdmin or file system)
8. Deactivate `wordfence/wordfence.php` similarly to previous step
9. Open http://localhost:8880/, http://localhost:8880/wp-admin/. To login, either:
    1. Use your production wordpress credentials.
    2. Move `tools/create-default-user.php` to `wordpress` folder, and visit http://localhost:8880/create-default-user.php. Sign in with `admin`-`admin`
9. Consider disabling most plugins for performance reasons (Wordfence Security seems to impact performance heavily). If admin session expires and you are unable to log in, disable WPML Sticky Links via PHPMyAdmin, log in, and enable it again.


### Diagnosing
If you see an error with plugins like

```
Fatal error: Uncaught Error: Call to undefined function get_field() in /var/www/html/wp-content/themes/tuleva/lib/class-wp-bootstrap-navwalker.php:137 Stack trace: #0 /var/www/html/wp-includes/class-wp-walker.php(147): WP_Bootstrap_Navwalker->start_el('
```

Then step 7 was not done succesfully.

You need to activate necessary plugins again in `http://localhost:8880/wp-admin/plugins.php`.
First initialize all plugins that can be initialized, then `WPML` plugins, then all plugins except `really-simple-ssl`.

## Resetting database

Re-initialize the database by deleting the existing one before

```
docker-compose down
docker volume rm wordpress-theme_db_data
```

## Syncing live data

1. Connect to VPN which routes your traffic **or** register your ip with the host
2. Set up variables in `tools/sync_database.sh` and `tools/sync_media.sh`, ask from team
3. Run scripts with `sh sync_media.sh` and `sh sync_database.sh`
   1. If needed add execution permission ` chmod +x <file name>`
   2. The script expects you to have an SSH connected in the agent. If you do not then edit the scripts by adding `-i <path to key>` to ssh use
4. Monitor progress and wait for finish message

## CI
CI uses host `ftp.tuleva.ee` since CloudFlare is configured to forward to the correct machine with that url.
Rsync uploads changed files using a key, which is configured in the host and CI.

## Update theme POT file script

### With WP cli

Install wp cli:
`brew install wp-cli`

1. Generate POT file after code changes affecting translations
* `./tools/i18n/generate-pot.sh`
* Or `wp i18n make-pot src/wp-content/themes/tuleva src/wp-content/themes/tuleva/lang/tuleva.pot --ignore-domain`

> If the command fails with `PHP Fatal error:  Allowed memory size of 134217728 bytes exhausted (tried to allocate 4096 bytes)` then run the following command instead, which uses php from current environment and the php.ini can be modified to increase memory limit:
```
php /opt/homebrew/bin/wp i18n make-pot src/wp-content/themes/tuleva src/wp-content/themes/tuleva/lang/tuleva.pot --ignore-domain
```

2. Generate PO file and add Estonian translations
* `./tools/i18n/generate-po.sh`
* Or `wp i18n update-po src/wp-content/themes/tuleva/lang/tuleva.pot  src/wp-content/themes/tuleva/lang`

3. Generate MO file to reflect them in Wordpress
* `./tools/i18n/generate-mo.sh`
* `wp i18n make-mo src/wp-content/themes/tuleva/lang`

Alternatively, use POEdit for steps 2 and 3.

### With PHP script (legacy, may not work)
`php tools/i18n/makepot.php wp-theme src/wp-content/themes/tuleva/ src/wp-content/themes/tuleva/lang/tuleva.pot`


## Start listener to compile .scss files to .css

Start following npm script
```
cd src/wp-content/themes/tuleva; npm install; npm run watch:scss
```

And edit scss files to have them built into css.
