#/bin/sh

# This loads an alternate htaccess file, `.htaccess-lando` if it is present.
# The change is supposed to be made by core lando, but is failing. See:
# https://github.com/lando/lando/issues/2277

echo "Checking for AccessFileName in /etc/apache2/apache2.conf..."
if [ -e /etc/apache2/apache2.conf ]; then
	sed -i 's/AccessFileName .htaccess/AccessFileName .htaccess-lando .htaccess/g' /etc/apache2/apache2.conf
fi
