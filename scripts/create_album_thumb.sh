# $1 album_name, $2 photo_name, $3 photo_dir
if [ -z $1 ]; then
  echo "ERROR: Must supply dirname"
  exit 1;
fi
if [ -z $3 ]; then
  photo_dir='../photo'
else
  photo_dir=$3
fi
if [ ! -d $photo_dir/$1/.album ]; then
  echo "Creating album thumb dir for $1"
  mkdir $photo_dir/$1/.album
fi
echo $(date) Creating album thumb for $1: $2 from $4 >> scripts/log.txt
cp $photo_dir/$1/.thumb/${2%.*}.webp $photo_dir/$1/.album/thumb.webp
cp $photo_dir/$1/.web/${2%.*}.webp $photo_dir/$1/.album/thumb_big.webp
cp $photo_dir/$1/.thumb/${2%.*}.jpg $photo_dir/$1/.album/thumb.jpg
cp $photo_dir/$1/.web/${2%.*}.jpg $photo_dir/$1/.album/thumb_big.jpg
cwebp $photo_dir/$1/.album/thumb.webp -crop 45 0 120 120 -short -o $photo_dir/$1/.thumb/icon.webp
convert $photo_dir/$1/.album/thumb.jpg -crop 120x120+45+0 +repage $photo_dir/$1/.thumb/icon.jpg
