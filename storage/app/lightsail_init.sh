cd /opt/bitnami/wordpress && php /opt/bitnami/wp-cli/bin/wp-cli.phar db query "RENAME TABLE wp__users TO wp_"$prefix"_users";
cd /opt/bitnami/wordpress && php /opt/bitnami/wp-cli/bin/wp-cli.phar db query "RENAME TABLE wp__usermeta TO wp_"$prefix"_usermeta";



