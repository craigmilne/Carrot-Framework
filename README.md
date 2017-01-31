#Carrot Framework

A super-duper lightweight PHP MVC-type framework.

##Setup

The `master` branch will always be the one to download, I'll possibly be playing about with new things or whatnot in other branches if you're feeling adventurous though.

You can download the repo as a .zip file or use `git clone https://github.com/enlim/Carrot-Framework.git` in your web server.

You'll also want to point all of your incoming connections to the `index.php` file provided. This will depend on your setup and can be done with a quick search on your favourite search engine, but here's a couple examples.

####Nginx

Plop this in your server block if your are using Nginx and PHP-FPM, along with whatever else you might need:

```
location / {
    root [APP ROOT];
    fastcgi_index index.php;
    fastcgi_param SCRIPT_FILENAME  $document_root/index.php;
    ...
    ...
}
```

####Apache2 (A)

If you're an Apache person then try this, ensuring that your have `mod_rewrite` enabled:

```
Options +FollowSymLinks
RewriteEngine On
RewriteCond %{REQUEST_URI} !=/index.php
RewriteRule .* /index.php

```

####Apache2 (B)

If this is more of a temporary thing then there's a neat script that generates a .htaccess for you, that'll do the same as above although it isn't as proper.

```
$ ./toggle_htaccess.sh
```

##Using It

###Routing and Controllers

I'm in the process of including more detailed documentation on things.

Routing is done with the `routing.conf.php` file. Add routes to either `$get` or `$post` depending on the method you wish for them to be used. All entries are in the form of:

`'path' => '['controller' => 'CONTROLLER_HERE', 'action' => 'ACTION_HERE', ...]'`

You can use matching groups to add arguments to the global scope and indexes to allocate them, for example using `app/(.*\.php) => ['controller' => 'app', 'action' => 'show', file => '{1}']` will assign the match from the first bracket set to the variable `{1}`. You can use `{0}` to indicate the whole string.

Controllers can be created either of your own accord or using the `./add_controller.sh <controller> [actions,...]` script. They can be removed, or described using the `./rm_controller.sh` or `./ls_controller.sh` scripts respectively.

###App.conf.php

The main config at `app.conf.php` is described in more detail in the docs, but it will contain key information such as database info, how to handle errors, or which enironment to use. 

###LESS CSS

I have included the [LESSPHP compiler](http://leafo.net/lessphp/) for use. It's a fantastic advancement to plain CSS but of course it is not required. 

Example usage, placing this in the `<head>` tag:

```
if (Config::is_dev()) {
	require LIB_ROOT . "/less/lessc.inc.php";

	$less = new lessc;
	echo "<style>";
	echo $less->compileFile(realpath(APP_ROOT . "/content/css/style.less"));
	echo "</style>";
}
```

Of course for Production it would be beneficial to precompile:

```
$ plessc input.less > output.css
```

###Utils

The global utilities file `/lib/global_utils.php` is included in all requests, this includes rendering, errors, and database functions. In addition to this, files located in the `/app/utils/` directory are included to controllers that share a common filename.

##Other Things

###Todo

* Still tidy code
* A few more scripts, gosh darn I must be a script kiddie!