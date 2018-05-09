#!/bin/bash


# Output directory
output_dir=../../css/sprites
cd `dirname $0`
mkdir -p $output_dir


function echo_pieceset {
	echo ""
	echo "#################################################"
	echo "# Pieceset $1"
	echo "#################################################"
	echo ""
}


function echo_sprite {
	echo "Processing sprite $1..."
}



################################################################################
# CBURNETT
################################################################################

function export_cburnett {

	echo_pieceset "cburnett"

	codes=$1
	for code in $codes; do

		echo_sprite $code

		# Input/output files
		input=cburnett/$code.svg
		output=$output_dir/cburnett-$code.png

		# Create the sprite
		inkscape -e $output -w 64 -h 64 $input > /dev/null

	done
}



################################################################################
# MMONGE
################################################################################

function export_mmonge {

	codes=$1
	piecesets=$2

	for pieceset in $piecesets; do

		echo_pieceset $pieceset

		for code in $codes; do

			echo_sprite $code

			colorcode=${code:0:1}
			piececode=${code:1:1}

			if [ "$piececode" == "x" ]; then

				# Input/output files
				input=mmonge/$pieceset-$code.svg
				output=$output_dir/$pieceset-$code.png

				# Create the sprite
				inkscape -e $output -w 64 -h 64 $input > /dev/null

			else

				# Input/output files
				input=mmonge/$pieceset.svg
				output=$output_dir/$pieceset-$code.png

				# Area to extract from the source file
				x1=`expr "(" index "kqrbnp" $piececode ")" "*" 200`
				y1=`expr "(" index "wb" $colorcode ")" "*" 200`
				x0=`expr $x1 - 200`
				y0=`expr $y1 - 200`
				area="$x0:$y0:$x1:$y1"

				# Create the sprite
				inkscape -e $output -a $area -w 64 -h 64 $input > /dev/null
			fi

		done
	done
}



################################################################################
# RUN THE EXPORTS
################################################################################

export_cburnett "bb bk bn bp bq br bx wb wk wn wp wq wr wx"
export_mmonge "bb bk bn bp bq br wb wk wn wp wq wr" "celtic eyes fantasy skulls spatial"
export_mmonge "bx wx" "celtic eyes-spatial fantasy skulls"
