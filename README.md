# gal - Raspberry Pi Gallery for DSLR Cameras
No-sweat setup, just plop in the public directory of a PHP web-server  

Downloads pictures from your DSLR straight to a directory named `photo` (also in web-server public folder)



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
[Sng](https://www.npmjs.com/package/sng) can be used to serve PHP from somewhere in your home folder. Nginx and PHP are required. Sng requires npm, the neatest way to install that is with [nvm](nvm.sh) (Node Version Manager).

1. Place a file named `.sng.conf` in the parent directory of the project. Place these contents in `.sng.conf`:  
```
# pass the PHP scripts to FastCGI server listening on the php-fpm socket
location ~ \.php$ {
  try_files $uri =404;
  fastcgi_pass unix:/run/php/php7.0-fpm.sock;
  fastcgi_index index.php;
  fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;
  include fastcgi_params;
}
```
2. Run `sng` from that parent directory.
