#!/bin/bash

# Files to process
files="bb.svg bk.svg bn.svg bp.svg bq.svg br.svg bx.svg wb.svg wk.svg wn.svg wp.svg wq.svg wr.svg wx.svg trash.png"
output_dir=../../css/sprite

# Sizes
size_min=12
size_max=64

# Loop over the files
cd `dirname $0`
for file in $files; do
	echo "Processing file $file..."

	# Build the output file name
	extension=${file##*.}
	base=${file%.*}
	output=$output_dir/$base.png

	# Size conversion
	current_size=$size_min
	while [ $current_size -le $size_max ]; do
		if [ "$extension" == "svg" ]; then
			inkscape -e tmp-$current_size.png -w $current_size -h $current_size $file
		else
			convert $file -resize "$current_size"x"$current_size" tmp-$current_size.png
		fi
		current_size=`expr $current_size + 1`
	done

	# Stack the medium and small sprites
	small_size=`expr $size_max / 2`
	medium_size=`expr $small_size + 1`
	while [ $small_size -ge $size_min ]; do
		convert -background '#ffffff00' -append tmp-$medium_size.png tmp-$small_size.png tmp-$medium_size-stack.png
		small_size=`expr $small_size - 1`
		medium_size=`expr $medium_size + 1`
	done

	# Rename the large sprites
	large_size=$medium_size
	while [ $large_size -le $size_max ]; do
		mv tmp-$large_size.png tmp-$large_size-stack.png
		large_size=`expr $large_size + 1`
	done

	# Merge all the stacked sprites
	convert -background '#ffffff00' +append tmp-*-stack.png $output

	# Cleanup
	rm tmp-*.png

done
