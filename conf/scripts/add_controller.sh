#!/bin/bash

typeset -l name
name=$1

if [ $# -eq 0 ]; then
	# Print usage
	echo "./add_controller.sh <name> [action,...,action]"
	exit
elif [ -f "../../app/controllers/${name}.php" ]; then
	# Print to notify that controller already exists
	echo "Controller '${name}' already exists."
	exit
else
	# Create new controller
	echo "Creating controller '${name}'"
	if [ ! -d "../../app/controllers/${name}" ]; then
		mkdir -p "../../app/controllers"
	fi
	if [ ! -d "../../app/views/${name}" ]; then
		mkdir -p "../../app/views/${name}"
	fi
	# TODO	- make this create action files and create the controller file
	exit
fi
