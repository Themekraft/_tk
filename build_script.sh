#!/bin/bash

printf "Theme Name: "
read NAME

printf "Destination folder: "
read FOLDER

printf "Initialise new git repo (y/n): "
read NEWREPO


DEFAULT_NAME="_tk"

TOKEN1=${NAME// /_}
TOKEN2=$( tr '[A-Z]' '[a-z]' <<< $TOKEN1)

TEMP1='/tmp/outputsed1.tmp'
TEMP2='/tmp/outputsed2.tmp'
TEMP3='/tmp/outputsed3.tmp'
TEMP4='/tmp/outputsed4.tmp'

NEWTHEME="${FOLDER}/${TOKEN2}/"
NEWRSRCS="${FOLDER}/${TOKEN2}/includes/*.php"

mkdir -p $FOLDER

git clone --depth=1 git@github.com:Themekraft/$DEFAULT_NAME.git $FOLDER/$TOKEN2

echo "Removing git files..."

rm -rf $FOLDER/$TOKEN2/.git
rm $FOLDER/$TOKEN2/README.md

echo "Updating theme files..."

#TODO: Handle name in style.css so is not tokenized, as it is right now.
for f in `find $NEWTHEME -type f -name "*.php" -or -name "*.css" -or -name "*.md"`
do
  if [ -f $f -a -r $f ]; then
   sed "s/\'$DEFAULT_NAME\'/\'$TOKEN2\'/g" "$f" > $TEMP1
   sed "s/$DEFAULT_NAME\_/$TOKEN2\_/g" "$TEMP1" > $TEMP2
   sed "s/ $DEFAULT_NAME/ $TOKEN2/g" "$TEMP2" > $TEMP3
   sed "s/$DEFAULT_NAME-/$TOKEN2-/g" "$TEMP3" > $TEMP4 && mv $TEMP4 "$f"
  else
   echo "Error: Cannot read $f"
  fi
done

for f in ${NEWRSRCS}
do
  if [ -f $f -a -r $f ]; then
   sed "s/\'$DEFAULT_NAME\'/\'$TOKEN2\'/g" "$f" > $TEMP1
   sed "s/$DEFAULT_NAME/$TOKEN2\_/g" "$TEMP1" > $TEMP2
   sed "s/ $DEFAULT_NAME/ $TOKEN2/g" "$TEMP2" > $TEMP3
   sed "s/$DEFAULT_NAME-/$TOKEN2-/g" "$TEMP3" > $TEMP4 && mv $TEMP4 "$f"
  else
   echo "Error: Cannot read $f"
  fi
done

rm $TEMP1
rm $TEMP2
rm $TEMP3

if [ "$NEWREPO" == "y" ]; then
	echo "Initialising new git repo..."
	cd $FOLDER/$TOKEN2
	git init
fi

echo "Complete!"
