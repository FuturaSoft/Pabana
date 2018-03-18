# CHANGELOG

## 1.1.0 "Kiwi" - 18/03/2018

 - Enhancement : [#8 [Mvc] Improves the MVC process](https://github.com/FuturaSoft/Pabana/issues/8)
 - Feature : [#9 [Type] Create a String class](https://github.com/FuturaSoft/Pabana/issues/9)
 - Feature : [#11 [Database] Autoload database configuration from config file](https://github.com/FuturaSoft/Pabana/issues/11)
 - Feature : [#12 [Type] Create an Array class](https://github.com/FuturaSoft/Pabana/issues/12)
 - Enhancement : [#14 [Database] Add transaction method in Connection](https://github.com/FuturaSoft/Pabana/issues/14)
 - Documentation : Replace version number 1.0.0 by 1.0 in docblock
 - Documentation : Solve documentation error (expect in Database class)
 - Feature : Add PHPUnit and create first sample test for Configuration
 - Community : Add an issue template to formalize issue

## 1.0.6 "Banana" - 21/02/2018

 - Bug : [Network] : Correct X-Mailer tag in Mail class (regressception :\ )

## 1.0.5 "Banana" - 19/02/2018

 - Bug : [Network] : Correct X-Mailer tag in Mail class (regression)  
 - Bug : [Network] : Add encoding tag in Mail class (regression)

## 1.0.4 "Banana" - 15/02/2018

 - Bug : [Mvc] : Fix change Layout bug  
 - Enhancement : [Global] : Improve documentation for all part of Pabana

## 1.0.3 "Banana" - 13/02/2018

 - Bug : [Database] : Correct construct method in Mysql and Sqlserver class  
 - Bug : [Network] : Correct X-Mailer tag in Mail class  
 - Bug : [Network] : Add encoding tag in Mail class  
 - Bug : [Intl] : If encoding failed, return string before encoding

## 1.0.2 "Banana" - 05/02/2018

 - Bug : [Composer] : Correct version in composer.json file

## 1.0.1 "Banana" - 05/02/2018

 - Bug : [Composer] : Correct src path for autoloading  
 - Bug : [Database] : Correct getOption method calling

## 1.0.0 "Banana" - 05/02/2018

 - Bug : [Html] : Correct the meta tag generation  
 - Feature : [Global] : Support PSR-1, PSR-2 and PSR-4 (Composer)  
 - Feature : [Core] : Initialize Pabana and start MVC in Application class  
 - Feature : [Core] : Move Bootstrap class to Core module  
 - Feature : [Core] : Add parsing of Ini in Configuration load  
 - Feature : [Database] : Add SQLite, SQLServer and Pgsql in available datasource  
 - Feature : [Intl] : Add automatique encoding conversion  
 - Feature : [Mvc] : Add auto usage of connection in Model  
 - Feature : [Network] : Add Request class to analyse http request  
 - Feature : [Network] : Add Mail class to send email  
 - Feature : [Parser] : Add Ini class to parse ini to array  
 - Feature : [Routing] : Add RouteCollection and Route to allow a custom routage