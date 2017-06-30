# gal - Raspberry Pi Gallery for DSLR Cameras
No-sweat setup, just plop in the public directory of a PHP web-server  

Downloads pictures from your DSLR straight to a sibling directory named `photo`  

Thumbnails and lightbox-sized previews are generated automatically at time of download

![Screenshot](https://raw.githubusercontent.com/zvakanaka/photo-gal/master/img/photo-gal.png)  

## Transfer Albums to other Servers
Upload albums without a password (mandatory for UI uploads)
```sh
$ ssh-keygen
$ ssh-copy-id user@your_website.com -P 22
```

## Change Default Photo Directory
Edit values in `config.php`.  

---
## Local Development
See [here](http://php.net/manual/en/features.commandline.webserver.php)
