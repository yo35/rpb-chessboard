#!/bin/bash

# Files to process
files="bb.svg bk.svg bn.svg bp.svg bq.svg br.svg bx.svg wb.svg wk.svg wn.svg wp.svg wq.svg wr.svg wx.svg"
output_dir=../../css/sprite

# Loop over the files
cd `dirname $0`
for file in $files; do
	echo "Processing file $file..."

	# Build the output file name
	extension=${file##*.}
	base=${file%.*}
	output=$output_dir/$base.png

	# Size conversion
	current_size=12
	while [ $current_size -le 64 ]; do
		if [ "$extension" == "svg" ]; then
			inkscape -e tmp-$current_size.png -w $current_size -h $current_size $file
		else
			convert $file -resize "$current_size"x"$current_size" tmp-$current_size.png
		fi
		current_size=`expr $current_size + 1`
	done

	# Create the output image
	cp empty.png $output

	# Montage
	composite -compose over -gravity northwest -geometry  +450+53 tmp-12.png $output $output
	composite -compose over -gravity northwest -geometry  +437+52 tmp-13.png $output $output
	composite -compose over -gravity northwest -geometry  +423+51 tmp-14.png $output $output
	composite -compose over -gravity northwest -geometry  +408+50 tmp-15.png $output $output
	composite -compose over -gravity northwest -geometry  +392+49 tmp-16.png $output $output
	composite -compose over -gravity northwest -geometry  +375+48 tmp-17.png $output $output
	composite -compose over -gravity northwest -geometry  +357+47 tmp-18.png $output $output
	composite -compose over -gravity northwest -geometry  +338+46 tmp-19.png $output $output
	composite -compose over -gravity northwest -geometry  +318+45 tmp-20.png $output $output
	composite -compose over -gravity northwest -geometry  +297+44 tmp-21.png $output $output
	composite -compose over -gravity northwest -geometry  +275+43 tmp-22.png $output $output
	composite -compose over -gravity northwest -geometry  +252+42 tmp-23.png $output $output
	composite -compose over -gravity northwest -geometry  +228+41 tmp-24.png $output $output
	composite -compose over -gravity northwest -geometry  +203+40 tmp-25.png $output $output
	composite -compose over -gravity northwest -geometry  +177+39 tmp-26.png $output $output
	composite -compose over -gravity northwest -geometry  +150+38 tmp-27.png $output $output
	composite -compose over -gravity northwest -geometry  +122+37 tmp-28.png $output $output
	composite -compose over -gravity northwest -geometry   +93+36 tmp-29.png $output $output
	composite -compose over -gravity northwest -geometry   +63+35 tmp-30.png $output $output
	composite -compose over -gravity northwest -geometry   +32+34 tmp-31.png $output $output
	composite -compose over -gravity northwest -geometry    +0+33 tmp-32.png $output $output
	composite -compose over -gravity northwest -geometry    +0+0  tmp-33.png $output $output
	composite -compose over -gravity northwest -geometry   +33+0  tmp-34.png $output $output
	composite -compose over -gravity northwest -geometry   +67+0  tmp-35.png $output $output
	composite -compose over -gravity northwest -geometry  +102+0  tmp-36.png $output $output
	composite -compose over -gravity northwest -geometry  +138+0  tmp-37.png $output $output
	composite -compose over -gravity northwest -geometry  +175+0  tmp-38.png $output $output
	composite -compose over -gravity northwest -geometry  +213+0  tmp-39.png $output $output
	composite -compose over -gravity northwest -geometry  +252+0  tmp-40.png $output $output
	composite -compose over -gravity northwest -geometry  +292+0  tmp-41.png $output $output
	composite -compose over -gravity northwest -geometry  +333+0  tmp-42.png $output $output
	composite -compose over -gravity northwest -geometry  +375+0  tmp-43.png $output $output
	composite -compose over -gravity northwest -geometry  +418+0  tmp-44.png $output $output
	composite -compose over -gravity northwest -geometry  +462+0  tmp-45.png $output $output
	composite -compose over -gravity northwest -geometry  +507+0  tmp-46.png $output $output
	composite -compose over -gravity northwest -geometry  +553+0  tmp-47.png $output $output
	composite -compose over -gravity northwest -geometry  +600+0  tmp-48.png $output $output
	composite -compose over -gravity northwest -geometry  +648+0  tmp-49.png $output $output
	composite -compose over -gravity northwest -geometry  +697+0  tmp-50.png $output $output
	composite -compose over -gravity northwest -geometry  +747+0  tmp-51.png $output $output
	composite -compose over -gravity northwest -geometry  +798+0  tmp-52.png $output $output
	composite -compose over -gravity northwest -geometry  +850+0  tmp-53.png $output $output
	composite -compose over -gravity northwest -geometry  +903+0  tmp-54.png $output $output
	composite -compose over -gravity northwest -geometry  +957+0  tmp-55.png $output $output
	composite -compose over -gravity northwest -geometry +1012+0  tmp-56.png $output $output
	composite -compose over -gravity northwest -geometry +1068+0  tmp-57.png $output $output
	composite -compose over -gravity northwest -geometry +1125+0  tmp-58.png $output $output
	composite -compose over -gravity northwest -geometry +1183+0  tmp-59.png $output $output
	composite -compose over -gravity northwest -geometry +1242+0  tmp-60.png $output $output
	composite -compose over -gravity northwest -geometry +1302+0  tmp-61.png $output $output
	composite -compose over -gravity northwest -geometry +1363+0  tmp-62.png $output $output
	composite -compose over -gravity northwest -geometry +1425+0  tmp-63.png $output $output
	composite -compose over -gravity northwest -geometry +1488+0  tmp-64.png $output $output

	# Cleanup
	rm tmp-*.png

done
