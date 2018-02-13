# CHANGELOG

## 1.0.3 "Banana" - 13/02/2018

FIX : [Database] : Correct construct method in Mysql and Sqlserver class
FIX : [Network] : Correct X-Mailer tag in Mail class
FIX : [Network] : Add encoding tag in Mail class
Fix : [Intl] : If encoding failed, return string before encoding

## 1.0.2 "Banana" - 05/02/2018

FIX : [Composer] : Correct version in composer.json file

## 1.0.1 "Banana" - 05/02/2018

FIX : [Composer] : Correct src path for autoloading
FIX : [Database] : Correct getOption method calling

## 1.0.0 "Banana" - 05/02/2018

NEW : [Global] : Support PSR-1, PSR-2 and PSR-4 (Composer) 
NEW : [Core] : Initialize Pabana and start MVC in Application class  
NEW : [Core] : Move Bootstrap class to Core module  
NEW : [Core] : Add parsing of Ini in Configuration load  
NEW : [Database] : Add SQLite, SQLServer and Pgsql in available datasource  
FIX : [Html] : Correct the meta tag generation  
NEW : [Intl] : Add automatique encoding conversion  
NEW : [Mvc] : Add auto usage of connection in Model  
NEW : [Network] : Add Request class to analyse http request  
NEW : [Network] : Add Mail class to send email  
NEW : [Parser] : Add Ini class to parse ini to array
NEW : [Routing] : Add RouteCollection and Route to allow a custom routage