# SkyBlue CMS

SkyBlue CMS version 2.0 is the second generation of the SkyBlueCanvas lightweight CMS. Before downloading this code, be sure you understand the following:

- The code is not quite finished and is not ready for production web sites. 
- The code works, but the set-up requires advanced PHP skills
- You will need decent command-line and Linux skills
- Support from the developer will be minimal due to time constraints but I will make an effort to help serious developers who want to contribute to the project


## Set-up:

NOTE: In the explanation below, I refer to the web root `/var/www/html/`. This is a typical Linux web root, but it can be different depending on your server configuration. If you are using inexpensive shared virtual hosting (most inexpensive hosting is this kind), then the folder may be named something like `/home/USER_NAME/public_html/`. I am hosting my sites on MediaTemple and so the web root is `/domains/mydomain.com/html/`. If you are not sure what the path to your web root is, contact your server administrator or your hosting company's technical support.

The Apache web server allows you to configure where each web site hosted on the server resides via the DocumentRoot directive. On a typical Linux environment, you might find the default DocumentRoot pointing to `/var/www/html/`. Anything in the 'html' folder is said to be in the web path.

In order to minimize risk, as much of the executable code in SkyBlue is kept out of the web path. Only the files that absolutely have to be in the web root are placed there.

When setting up your SkyBlue site, you will place the entire _skyblue_ directory in your current web root (e.g., `/var/www/html/`). However, you are going to override the default web root setting to point to the 'webroot' subdirectory inside the SkyBlue package, thus making it the new web root.

The default behavior of your web server is probably something along these lines:

The URL `http://yoursite.com/` currently points to `/var/www/html/`. So if you have a folder of images in your web root that you can access with `http://yoursite.com/images/`, on your web server you would likely have a folder in /var/www/html/image/. So the server configuration considers http://yoursite.com to be equivalent to /var/www/html/.

But we are overriding this setting so that `http://yoursite.com/` will now be `/var/www/html/skyblue/webroot/`. This is accomplished with the htaccess file included with the SkyBlue package. This file must be placed in your domain's default web root (e.g., /var/www/html/).

## Installation:

- Download SkyBlue to your web server. 
- Unzip/untar the download and copy the entire folder into your web server root.
- Make a copy of htaccess.txt and open the copy in a text editor
- Search/replace all occurrences of 'skybluecanvas.com' with your web URL. For example, if your site is called 'acme.com', then replace all instances of 'skybluecanvas.com' with 'acme.com'.
- Save the file then rename to .htaccess (note the dot at the beginning of the name. This is crucial)
- Once you have SkyBlue up and running, log into the admin by going to http://yoursite.com/admin.php. The default login credentials are:

```
username: admin
password: admin
```


## HTACCESS Contents:

```
# Turn on rewrites 

RewriteEngine on

# This rule tells Apache to only apply the subsequent rules to URLs on this domain

`RewriteCond %{HTTP_HOST} ^www\.yoursite.com$`

# This rule tells Apache to only apply subsequent rules to URLs that aren't already under the `/webroot/` folder

RewriteCond %{REQUEST_URI} !^/webroot/

# Now Rewrite all requests to the webroot folder so a request for `http://yoursite.com/index.html` will be understood by Apache to mean `/var/www/html/skyblue/webroot/index.html

RewriteRule ^(.*)$ /webroot/$1

#Now tell Apache to reroute all requests for the root domain to /var/www/html/skyblue/webroot/

RewriteCond %{HTTP_HOST} ^www.yoursite.com$
RewriteRule ^(/)?$ /webroot/ [L]
```