#!/bin/bash

# Ensure that the number of inputs is correct
if [ "$#" -eq "0" ]; then
	echo 'Usage:'
	echo '  ./render.sh [size]'
	echo '  ./render.sh [size-min] [size-max]'
	echo '  ./render.sh [size-min] [size-max] [step]'
	exit
fi

# Read the input arguments
size_min=$1
size_max=$1
step=1
if [ "$#" -ge "2" ]; then
	size_max=$2
fi
if [ "$#" -ge "3" ]; then
	step=$3
fi

# Rendering loop
current_size=$size_min
while [ $current_size -le $size_max ]; do

	# Create the output directory
	mkdir -p $current_size
	
	# Export the files
	inkscape -e $current_size/bb.png -w $current_size -h $current_size bb.svg
	inkscape -e $current_size/bk.png -w $current_size -h $current_size bk.svg
	inkscape -e $current_size/bn.png -w $current_size -h $current_size bn.svg
	inkscape -e $current_size/bp.png -w $current_size -h $current_size bp.svg
	inkscape -e $current_size/bq.png -w $current_size -h $current_size bq.svg
	inkscape -e $current_size/br.png -w $current_size -h $current_size br.svg
	inkscape -e $current_size/wb.png -w $current_size -h $current_size wb.svg
	inkscape -e $current_size/wk.png -w $current_size -h $current_size wk.svg
	inkscape -e $current_size/wn.png -w $current_size -h $current_size wn.svg
	inkscape -e $current_size/wp.png -w $current_size -h $current_size wp.svg
	inkscape -e $current_size/wq.png -w $current_size -h $current_size wq.svg
	inkscape -e $current_size/wr.png -w $current_size -h $current_size wr.svg
	inkscape -e $current_size/clear.png -w $current_size -h $current_size clear.svg
	inkscape -e $current_size/black.png -w $current_size -h $current_size black.svg
	inkscape -e $current_size/white.png -w $current_size -h $current_size white.svg
	
	# Increment the counter
	current_size=`expr $current_size + $step`
done
