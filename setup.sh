#!/bin/bash

type mysql >/dev/null 2>&1 && echo "Sql Installation Found -- Moving on" || echo "Pls install mysql" && exit

echo "----------------------------"
read -p "SQL User: " DBUSER
read -s -p "SQL Pass: " DBPASS
echo -e "\n----------------------------"
read -p "New Teacher User: " NTUSER
read -s -p "New Teacher Pass: " NTUPASS
echo -e "\n----------------------------"

# Get password hash from php cli
NTPASS=`php -r "echo password_hash('$NTUPASS', PASSWORD_DEFAULT);"`

# Run database setup

echo "CREATE USER '$DBUSER'@'localhost' IDENTIFIED BY '$DBPASS';
CREATE DATABASE questions;
GRANT INSERT,UPDATE ON questions.* to '$DBUSER'@'localhost';
GRANT SELECT ON questions.questions to '$DBUSER'@'localhost';
USE questions;CREATE TABLE questions ( \`id\` INT NOT NULL AUTO_INCREMENT, \`ip\` TEXT(25) DEFAULT NULL, \`text\` TEXT(1024) DEFAULT NULL, \`answered\` BOOLEAN DEFAULT '0', \`time\` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP, KEY \`id\` (\`id\`) USING BTREE );
USE questions;CREATE TABLE users (username varchar(255), password varchar(255));
USE questions;INSERT INTO users (username, password) VALUES ('$NTUSER','$NTPASS');" | mysql -u root -p

# Update the connections.php file
sed -i "s/<usernamehere>/$DBUSER/g" "connections.php"
sed -i "s/<passwordhere>/$DBPASS/g" "connections.php"

echo -e "\n\n!***************************************!"
echo -e "! Make sure to delete the setup.sh file !"
echo -e "!***************************************!\n\n"
echo "Move the code to your web directory if you have not done so."
