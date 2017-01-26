#!/bin/bash

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
	if [ -f "../../app/controllers/$controller.php" ]; then
		echo -e "Controller: $controller.php"
	else
		echo -e "Controller: ${RED}$controller.php${NOC} [Controller Missing]"
	fi
	if [ -f "../../app/utils/$controller.php" ]; then
		echo -e "\tUtils: $controller.php"
	else
		echo -e "\tUtils: ${YLW}$controller.php${NOC} [Utils Missing]"
	fi
	noactions=0
	if [ -d "../../app/views/$controller/" ]; then
		for file in ../../app/views/$controller/*.php; do
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
