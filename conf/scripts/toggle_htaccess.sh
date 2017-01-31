#!/bin/bash

# Get the script dir so who cares where it is executed
SCRIPT_DIR="`dirname \"$0\"`"
SCRIPT_DIR="`( cd \"$SCRIPT_DIR\" && pwd )`"
if [ -z "$SCRIPT_DIR" ] ; then
  # error; for some reason, the path is not accessible
  # to the script (e.g. permissions re-evaled after suid)
  exit 1
fi

if [ -f "$SCRIPT_DIR/../../.htaccess" ]; then
	echo "Removing .htaccess"
	rm -f "$SCRIPT_DIR/../../.htaccess"
else
	echo "Creating .htaccess"
	echo -e "#\n#\n#\tYou should not usually use this, have the server's config point to index.php not the .htaccess.\n#\n#\tBut it is here because I use it on my dev PC... Ayy.\n#\n#\n\n# Turn rewriting on\nOptions +FollowSymLinks\nRewriteEngine On\n# Redirect requests to index.php\nRewriteCond %{REQUEST_URI} !=/index.php\nRewriteRule .* /index.php" > "$SCRIPT_DIR/../../.htaccess"
fi