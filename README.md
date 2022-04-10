## Steps to reproduce

1. Clone Project by running ```git clone git@github.com:seewhy17/ei-api.git```
2. Change into the directory ```cd ei-api```
3. run ```composer install```
4. create an ```.env``` file and copy the contents of ```.env.example``` into it, alternatively run ```cp .env.example .env```
5. change all DB_~ configurations in the newly created .env file to your preferred values as required
6. Create a database with the value of ```DB_DATABASE``` environment variable
7. Use ```ei-api``` directory as the DocumentRoot of the virtual host(http://ei-api.test)
8. run tests with ```./vendor/bin/pest``` or ```.\vendor\bin\pest``` on Windows
9. Use your preferred REST Client to test endpoints
