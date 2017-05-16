# Further Setup
(Nginx with PHP, Symlinks, Permissions)  

## Dependencies
```sh
 sudo apt install php5-common webp imagemagick gphoto2 rsync php5-fpm
```

[Enable PHP for Nginx](http://askubuntu.com/questions/134666/what-is-the-easiest-way-to-enable-php-on-nginx) and [Enable symlinks](http://unix.stackexchange.com/questions/157022/make-nginx-follow-symlinks) for Nginx  
```sh
sudo nano /etc/nginx/sites-available/default
```

```sh
sudo service php5-fpm restart
sudo service nginx restart
```
## Symbolic links
```sh
sudo ln -s ~/git/photo photo
sudo ln -s ~/git/gal gal
```
## Permissions
Permissions for `www-data`  
```sh
sudo chown www-data:www-data -R photo
sudo chown www-data:www-data -R gal
```

Allow gphoto2 to be used by `www-data`
```sh
sudo chmod +s $(which gphoto2)
```
## SCP (upload to server)
Upload albums without a password (mandatory for UI uploads)
```sh
# copy ssh directory of user that server already trusts
sudo cp /home/trusted_username/.ssh/* /var/www/.ssh/
```
# Optional

[Https (SSL)](https://www.digitalocean.com/community/tutorials/how-to-create-an-ssl-certificate-on-nginx-for-ubuntu-14-04) for Nginx   
[Redirect HTTP to HTTPS](https://www.digitalocean.com/community/questions/http-https-redirect-positive-ssl-on-nginx) for Nginx

Disable root login  
```sh
sudo nano /etc/ssh/sshd_config
```

Here are some lines from my Nginx config that show where log files are
```sh
#        access_log /var/log/nginx/access.log;
#        error_log /var/log/nginx/error.log;
```

### Additional information
Photos    
Place photos in a sibling directory of the project named `photo` (can be symbolic link). This can be customized in `config.php`.
