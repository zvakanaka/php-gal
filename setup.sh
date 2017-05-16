#!/bin/bash
cat banner-color.txt
echo

if [ ! -f setup.sh ]; then
  echo "ERROR: Setup must be executed from within project directory"
  exit 1
fi
project_dir=$(basename $(pwd))

if [ ! -f config.php ]; then
  if [ $(dpkg&>/dev/null) ]; then
    echo "WARNING: This does not seem to be a Debian based OS, please configure dependencies manually"
  else
    echo "Make sure these are installed:"
    echo -e '\n\t sudo apt install php5-common webp imagemagick gphoto2 rsync\n'
    echo "If you are using apache, install these too:"
    echo -e '\n\t sudo apt install apache2 libapache2-mod-php\n'
  fi

  photo_dir="../photo"

  echo -e "<?php
return (object) array(
  'photo_dir' => '$photo_dir',
  'project_dir' => '/$project_dir'
);
?>" > config.php
  echo "Created default configuration file"

  if [ ! -d $photo_dir ]; then
    echo "No directory named '$photo_dir' exists... (where photos will be stored)"
    echo -n "Would you like to create one? [y/N]: "
    read should_create_photo_dir
    if [[ $should_create_photo_dir =~ [yY](es)* ]]; then
      mkdir $photo_dir
      if [ $? -ne 0 ]; then
        echo "Failed creating directory '$photo_dir', do you have sufficient rights?"
      else
        echo "Directory created in $photo_dir"
      fi
    fi
  fi
  echo "Setup script complete!"
  echo "Now read docs.md for further setup (Nginx, PHP, SSH)"
else
  echo "It looks like you already created a configuration..."
  echo -n "Would you like to reset the settings? [y/N]: "
  read start_over
  if [[ $start_over =~ [yY](es)* ]]; then
    rm ./config.php
    echo "Reset complete. Re-running to create a new configuration..."
    bash setup.sh
  else
    echo "okay, bye"
  fi
fi
