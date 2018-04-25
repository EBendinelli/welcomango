Welcomango project
====

Welcomango is a colaborative plateform which puts travelers and locals in contact through common interest.

# Instalation

Welcomango is a Symfony2 project and thus requires:
* NodeJs
* Gulp
* Bower
* composer
* Git

We recommand using Nginx as your server engine.
To install you local instance of Welcomango and run test or use specific feature of the project, run the following commands:
* `git clone https://github.com/EBendinelli/welcomango
* `php composer.phar install    //load dependencies `
* `app/console do:da:cr         // create database `
* `app/console do:sc:cr         // create database schema`
* `app/console do:fi:lo         // load fixtures`
* `app/console ass:in`
* `bower install`
* `gulp`
* `app/console server:run`

# Usage

Welcomango core code is basically a booking system and allows user to book a date with another user. It features an inbox and a experience management module. Experience model can be enriched to fit your needs (booking a house, a trip) and is packed with an approval system

# Contribution

Any contribution to the welcomango project is welcome 

