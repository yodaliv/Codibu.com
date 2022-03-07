sudo su;
prefix=108;
demo_url=homecorporate4.pt.codibu.com;
network_url=pt.codibu.com;
site_title=Random blog name;
name=saidul;
email=saidul@gmail.com;
password=password;
curl -o /opt/bitnami/wordpress/wp-content/themes/themes.zip "$network_url"/wp-content/themes/themes.zip;
curl -o  /opt/bitnami/wordpress/wp-content/plugins/plugins.zip "$network_url"/wp-content/plugins/plugins.zip;
curl -o /opt/bitnami/wordpress/wp-content/"$prefix".zip "$network_url"/wp-content/uploads/sites/"$prefix".zip;
curl -o /opt/bitnami/wordpress/wp-content/db.sql "$network_url"/exporter/dumps/"$demo_url".sql;
wp search-replace wp_ wp_"$prefix"_;
wp db import /opt/bitnami/wordpress/wp-content/db.sql;
wp search-replace toprankon codibu --all-tables;
wp core update-db;
unzip -o /opt/bitnami/wordpress/wp-content/themes/themes.zip -d /opt/bitnami/wordpress/wp-content/themes/;
unzip -o /opt/bitnami/wordpress/wp-content/plugins/plugins.zip -d /opt/bitnami/wordpress/wp-content/plugins/;
unzip -o /opt/bitnami/wordpress/wp-content/"$prefix".zip -d /opt/bitnami/wordpress/wp-content;
cp -r /opt/bitnami/wordpress/wp-content/"$prefix" /opt/bitnami/wordpress/wp-content/uploads/.;
rm -rf /opt/bitnami/wordpress/wp-content/"$prefix";
wp option update blogname "$site_title";
wp option update admin_email "$email";
wp user update 1 --display_name="$name" --user_email="$email" --user_pass="$password";
wp db query 'RENAME TABLE wp_users TO wp_'"$prefix"'_users';
wp db query 'RENAME TABLE wp_usermeta TO wp_'"$prefix"'_usermeta';
wp config set table_prefix wp_"$prefix"_;
wp rewrite flush;
wp plugin activate woocommerce;
wp plugin activate --all;



