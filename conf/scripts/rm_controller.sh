#!/bin/bash

# Get the script dir so who cares where it is executed
SCRIPT_DIR="`dirname \"$0\"`"
SCRIPT_DIR="`( cd \"$SCRIPT_DIR\" && pwd )`"
if [ -z "$SCRIPT_DIR" ] ; then
  # error; for some reason, the path is not accessible
  # to the script (e.g. permissions re-evaled after suid)
  exit 1
fi


if [ ! $# -eq 1 ]; then
	# Print usage
	echo "./rm_controller.sh <name>"
	exit
else
	typeset -l controller
	controller=$1
	echo "Removing controller '$controller' and related actions/utils"
	rm -f "$SCRIPT_DIR/../../app/controllers/$controller.php"
	rm -f "$SCRIPT_DIR/../../app/utils/$controller.php"
	rm -rf "$SCRIPT_DIR/../../app/views/$controller"
	echo "Removed all files."
fi
