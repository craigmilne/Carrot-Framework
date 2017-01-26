#!/bin/bash

if [ ! $# -eq 1 ]; then
	# Print usage
	echo "./rm_controller.sh <name>"
	exit
else
	typeset -l controller
	controller=$1
	echo "Removing controller '$controller' and related actions/utils"
	rm -f "../../app/controllers/$controller.php"
	rm -f "../../app/utils/$controller.php"
	rm -rf "../../app/views/$controller"
	echo "Removed all files."
fi
