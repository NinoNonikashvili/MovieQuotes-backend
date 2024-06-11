<div style="display:flex; align-items: center">
  <h1 style="position:relative; top: -6px" >Movie quotes app</h1>
</div>


Movie Quote is a platform for people discover millions of quotes from different movies.

On movie quote platform you can register, create your own movies, post related quotes and show them to the world!

Here you also get reactions and comments from other users of movie quote platform!

#
### Table of Contents
* [Prerequisites](#prerequisites)
* [Tech Stack](#tech-stack)
* [Getting Started](#getting-started)
* [Migrations](#migration)
* [Broadcasting](#broadcasting)
* [Development](#development)
* [Deployment with CI / CD](#deployment-with-ci-\-cd)
* [Resources](#resources)

#
### Prerequisites

* <img src="https://pngimg.com/uploads/php/php_PNG43.png" width="35" style="position: relative; top: 4px" /> *PHP@8.2 and up*
* <img src="https://tse1.mm.bing.net/th?id=OIP.lIIc_svaWdGdEJuEk7TBlgHaHa&pid=Api&P=0&h=220" width="35" style="position: relative; top: 4px" /> *MYSQL@8 and up*
* <img src="https://tse2.mm.bing.net/th?id=OIP.mmXEW6CkG5NfwwM3UdzXcwHaHa&pid=Api&P=0&h=220" width="35" style="position: relative; top: 4px" /> *npm@6 and up*
* <img src="https://tse1.mm.bing.net/th?id=OIP.mFob_nJmwmMPrR4V7M9sAQHaJz&pid=Api&P=0&h=220" width="35" style="position: relative; top: 6px" /> *composer@2 and up*


#
### Tech Stack

* <img src="https://brandlogos.net/wp-content/uploads/2022/01/laravel-logo-brandlogo.net_-300x300.png" height="18" style="position: relative; top: 4px" /> [Laravel@11.x](https://laravel.com/docs/10.x/) - back-end framework
* <img src="https://brandlogos.net/wp-content/uploads/2022/01/laravel-logo-brandlogo.net_-300x300.png" height="18" style="position: relative; top: 4px" /> [Laravel Sanctum](https://laravel.com/docs/10.x/) - authentication system for SPAs
* <img src="https://avatars0.githubusercontent.com/u/7535935?v=4" height="19" style="position: relative; top: 4px" /> [Spatie Translatable](https://avatars0.githubusercontent.com/u/7535935?v=4) - package for translation
* <img src="https://avatars0.githubusercontent.com/u/7535935?v=4" height="19" style="position: relative; top: 4px" /> [Spatie Media Library](https://avatars0.githubusercontent.com/u/7535935?v=4) - package for simplifing working with files
*  <img src="https://avatars0.githubusercontent.com/u/7535935?v=4" height="19" style="position: relative; top: 4px" /> [Spatie Laravel query builder](https://avatars0.githubusercontent.com/u/7535935?v=4) - package for building queries easier
* <img src="https://brandlogos.net/wp-content/uploads/2022/01/laravel-logo-brandlogo.net_-300x300.png" height="18" style="position: relative; top: 4px" /> [Pest](https://laravel.com/docs/10.x/) - package for testing
* <img src="https://brandlogos.net/wp-content/uploads/2022/01/laravel-logo-brandlogo.net_-300x300.png" height="18" style="position: relative; top: 4px" /> [Pusher](https://laravel.com/docs/10.x/) - broadcasting using pusher channels

#
### Getting Started
1\. First of all you need to clone Movie quotes repository from github:
```sh
git clone https://github.com/RedberryInternship/back-movie-quotes-nino-nonikashvili
```

2\. Next step requires you to run *composer install* in order to install all the dependencies.
```sh
composer install
```

3\. after you have installed all the PHP dependencies, it's time to install all the JS dependencies:
```sh
npm install
```



4\. Now we need to set our env file. Go to the root of your project and execute this command.
```sh
cp .env.example .env
```
And now you should provide **.env** file all the necessary environment variables:

#
**MYSQL:**
>DB_CONNECTION=mysql

>DB_HOST=127.0.0.1

>DB_PORT=3306

>DB_DATABASE=*****

>DB_USERNAME=*****

>DB_PASSWORD=*****


#
**Gmail:**
>MAIL_MAILER=smtp

>MAIL_HOST=0.0.0.0

>MAIL_PORT=1025

>MAIL_FROM_ADDRESS=******

#
**Google Auth credentials for Socialite:**
>GOOGLE_CLIENT_ID= ******

>GOOGLE_CLIENT_SECRET = ******

>GOOGLE_REDIRECT = ******


#
**Media for spatie media library:**
>MEDIA_DISK='media'

#
**Broadcating:**
>BROADCAST_CONNECTION=pusher

>BROADCAST_DRIVER=pusher

>PUSHER_APP_ID=******

>PUSHER_APP_KEY=******

>PUSHER_APP_SECRET=******

>PUSHER_APP_CLUSTER=**



after setting up **.env** file, execute:
```sh
php artisan config:cache
```
in order to cache environment variables.

4\. Now execute in the root of you project following:
```sh
  php artisan key:generate
```
Which generates auth key.

##### Now, you should be good to go!


#
### Migration
if you've completed getting started section, then migrating database if fairly simple process, just execute:
```sh
php artisan migrate
```
#
### Broadcasting
In order to recieve notifications in real time you need to start queue by typing :
```sh
php artisan queue:work
```

#
### Running Feature tests
Running feature tests also is very simple process. you can create separate file .env.testing where you provide credentials for new database for testing purposes and just run the following command:

```sh
php artisan test <FileNameTest> --pest
```

#
### Development

You can run Laravel's built-in development server by executing:

```sh
  php artisan serve
```


#
### Deployment with CI \ CD


Continues Development / Continues Integration & Deployment steps:
* CI \ CD process first step is of course is development.
* After some time of development when you are ready to integrate and deploy your feature/fix/work you make a commit or pull request to gihub branch.
* That triggers github action which listens to pull requests and commits on development and main branch. Github actions will set up configure project.
* If process succeed then github actions will deploy your code to development or production server according to the branch you are making commit to.
* After deploying, github actions script will build your code and run migrations all to be up to date.

Then everything should be OK :pray:

#
### Resources

* [Figma Design](https://www.figma.com/design/5uMXCg3itJwpzh9cVIK3hA/Movie-Quotes-Bootcamp-assignment?node-id=225-15639&t=vzKlex6ll1A8dC1m-0)
* [Postman API documentation](https://documenter.getpostman.com/view/33904104/2sA3XMhN9t)
* DataBade Diagram
 <img src="/public/images/drawSQL-image.png" width="600" style="position: relative; top: 4px" /> 



