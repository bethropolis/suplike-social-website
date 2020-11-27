![logo](img/suplike.svg) 
 
# hello welcome to suplike 

<p>
 suplike is a social website made out of <b> PHP/JS/CSS</b> <br />
  
suplike is made with various ideas from all around social medias like Facebook, instagram and 

  you can check the [features](#features);
</p>

 * [getting started](#getting-started)
    * [Installation](#installation)    
    * [instalation steps](#installation-steps)
 * [Components](#components)   
 * [versions](#versions)
 * [features](#features)
 * [Future Improvements](#future-improvements) 
 * [inspiration](#inspiration)
 * [behind it all](#behind-it-all) 
 * [license](#license)
  
<br />

# getting started

## Installation

#### Requirements
* PHP
* Apache server
* MySQL Database
* SQL
* phpMyAdmin

> All of these requirements can be completed at once by simply installing a server stack like `Wamp` or `Xampp` etc.

#### Installation Steps
1. Import the `suplike.sql` file in the `inc` folder into phpMyAdmin. There is no need for any change in the .sql file. This will create the database required for the application to function.

2. Edit the `dbh.inc.php` file in the `inc` folder to create the database connection. Change the password and username to the ones being used within current installation of `phpMyAdmin`. There is no need to change anything else.

```php
$servername = "localhost";
$dBUsername = "root";
$dBPassword = ""; 
$dBName = "suplike";  


$conn = mysqli_connect($servername, $dBUsername, $dBPassword, $dBName);
```
  
## Components

#### Languages
```
PHP 5.6.40
SQL 14.0
JavaScript ES 6
HTML5
CSS3
```

#### Development Environment
```
xampp v3.2.4
Windows 10
```

#### Database
```
MySQL Database 8.0.13
```

#### DBMS
```
phpMyAdmin 4.8.3
```

#### API
```
MySQLi APIs
```

#### Frameworks and Libraries
```
JQuery v3.3.1
BootStrap v4.2.1
font awsome 4.7.0 
```
 
#### Techniques
```
AJAX
```


# versions

<p>
  more improvements will continue to be done to this project you may contribute
  or stay and watch 👀.
</p>


# features                                      

* image and text post  
* like system 
* live messaging/chat system
* home page
* profile page
* settings page
* login/signup system 
* search page
* followers page  


# Future Improvements
* making the whole system depend on ajax api requests for speed on page load and easier for expansion (`social.php` would be a great example);
* `laravel` would really make this even easier;
* adding lazy loading to the posts would make load time quicker
* improving the UI
* messaging/chat page needs more work
* implementing PHPmailer to send emails e.g forgot password system
* admin dashboard 
* Continuous Bug fixes and improvements

> you can show support to this project by staring this repo, it really means alot to me.   
 
# inspiration
my biggest inspiration to make this website was was the [KLIK social website](https://) 
whom I also made the readme from and also top social websites like [facebook](https://facebook.com) gave me some tips.

# behind it all 
Hi I am bethuel(bethropolis) and happy to say I survived the bugs.<br>
I love programming and especially working with API's which you will see most in this project
and my spirit animal is the 🐺. 
 
 <img src="img/myicon.jpg" width="100px"> 

[bethropolis](https://github.com/bethropolis) 

# License
it is licensed under my favourate License [MIT license](https://mit-license.org/). 
 