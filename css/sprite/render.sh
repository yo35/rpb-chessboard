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

	# Export the SVG files into PNG
	inkscape -e bb.png -w $current_size -h $current_size bb.svg
	inkscape -e bk.png -w $current_size -h $current_size bk.svg
	inkscape -e bn.png -w $current_size -h $current_size bn.svg
	inkscape -e bp.png -w $current_size -h $current_size bp.svg
	inkscape -e bq.png -w $current_size -h $current_size bq.svg
	inkscape -e br.png -w $current_size -h $current_size br.svg
	inkscape -e bx.png -w $current_size -h $current_size bx.svg
	inkscape -e wb.png -w $current_size -h $current_size wb.svg
	inkscape -e wk.png -w $current_size -h $current_size wk.svg
	inkscape -e wn.png -w $current_size -h $current_size wn.svg
	inkscape -e wp.png -w $current_size -h $current_size wp.svg
	inkscape -e wq.png -w $current_size -h $current_size wq.svg
	inkscape -e wr.png -w $current_size -h $current_size wr.svg
	inkscape -e wx.png -w $current_size -h $current_size wx.svg
	convert trash.png -resize "$current_size"x"$current_size" x0.png

	# Montage
	convert +append b?.png b.png
	convert +append w?.png w.png
	convert +append x?.png x.png
	convert -append b.png w.png x.png all-$current_size.png

	# Remove the temporary files
	rm ??.png ?.png

	# Increment the counter
	current_size=`expr $current_size + $step`
done
