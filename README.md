# Running
1. Export the live database into an `.sql` file
2. Move it into `./mysql-init` directory (alternatively import with phpMyAdmin at `http://localhost:8881/`)
3. docker-compose up

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
