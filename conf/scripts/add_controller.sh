#!/bin/bash

# Get the script dir so who cares where it is executed
SCRIPT_DIR="`dirname \"$0\"`"
SCRIPT_DIR="`( cd \"$SCRIPT_DIR\" && pwd )`"
if [ -z "$SCRIPT_DIR" ] ; then
  # error; for some reason, the path is not accessible
  # to the script (e.g. permissions re-evaled after suid)
  exit 1
fi


typeset -l name
name=$1

if [ $# -eq 0 ]; then
	# Print usage
	echo "./add_controller.sh <name> [action,...,action]"
	exit
elif [ -f "$SCRIPT_DIR/../../app/controllers/${name}.php" ]; then
	# Print to notify that controller already exists
	echo "Controller '${name}' already exists!"
	exit
else
	# Create new controller
	echo "Creating controller '${name}'"
	if [ ! -d "$SCRIPT_DIR/../../app/controllers" ]; then
		mkdir -p "../../app/controllers"
	fi
	if [ ! -d "$SCRIPT_DIR/../../app/views/${name}" ]; then
		mkdir -p "$SCRIPT_DIR/../../app/views/${name}"
	fi
	controllerdat="<?php \t// ${name}.php - Auto generated controller.\n\nswitch(\$GLOBALS['app_action']) {\n"
	for i in ${@:2}
	do
		actiondat="\tcase '${i}':\n\t\trender(\"${name}\", \"${i}\");\n\t\tbreak;\n"
		controllerdat=$controllerdat$actiondat
		echo "<h1>Auto generated action: <em>$i</em></h1>" > "$SCRIPT_DIR/../../app/views/${name}/${i}.php"
		echo "Created action '${i}'"
	done
	controllerdatclose="\tdefault:\n\t\tErrors::generate_error('500',\"Action requested could not be found.\");\n\t\tbreak;\n}\n\n?>"
	controllerdat=$controllerdat$controllerdatclose
	echo -e $controllerdat > "$SCRIPT_DIR/../../app/controllers/${name}.php"
	echo "Created controller."
	exit
fi
