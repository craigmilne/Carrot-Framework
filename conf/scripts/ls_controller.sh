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
	echo "./ls_controller.sh <controller>"
	exit
else
	typeset -l controller
	controller=$1

	RED='\033[0;31m'
	YLW='\033[0;33m'
	GRN='\033[0;32m'
	NOC='\033[0m'
	if [ -f "$SCRIPT_DIR/../../app/controllers/$controller.php" ]; then
		echo -e "Controller: $controller.php"
	else
		echo -e "Controller: ${RED}$controller.php${NOC} [Controller Missing]"
	fi
	if [ -f "$SCRIPT_DIR/../../app/utils/$controller.php" ]; then
		echo -e "\tUtils: $controller.php"
	else
		echo -e "\tUtils: ${YLW}$controller.php${NOC} [Utils Missing]"
	fi
	noactions=0
	if [ -d "$SCRIPT_DIR/../../app/views/$controller/" ]; then
		for file in $SCRIPT_DIR/../../app/views/$controller/*.php; do
			filename=${file##*/}
			echo -e "\tAction: ${filename}"
			noactions=$((noactions+1))
		done
		if [ $noactions -eq 0 ]; then
			echo -e "\tActions: ${RED}No Actions Found"
		fi
	else
		echo -e "\tActions: ${RED}No Actions Found"
	fi

fi
