# Spurr Technologies Emplyee Directory Application

Spurr Application
The Employee Directory application is a lightweight application using php and Ajax that allows a user to create new employees. It also allows the updating and deleting of said employees.

Contents
========
 * [Installation](#installation)
 * [Configuration](#configuration)

### Installation
---

#### Install From Source

```bash
git clone https://github.com/parkinshane/SpurrApplication.git
```

### Configuration
---

1. To start using the application, simply create a database called raxandb in MySQL.
2. Create a table called employees with the fields id, first_name, last_name, empno, birthdate and gender.
3. Create a local virtual server with the application using wamp or any other virtual server application.
4. Change version of php to 5.6.40 - Earlier versions not supported.
5. Make changes to the config file lokated in the root folder to correspond with your MySQL database
    
    $config['db.raxandb'] = array(
      'dsn'       => 'mysql: host=Host_Name_Here:3306; dbname=Database_Name_Here',
      'user'      => 'Username_Here',
      'password'  => 'Password_Here',
      'attribs'   => PDO::ERRMODE_EXCEPTION // use pdo exception mode
    );
    
6. Serve application.
7. No libraries needed.
8. Your all done enjoy.

NB: For additional documatation on raxan please see https://raxanpdi.com/
User Guide is available at https://raxanpdi.com/sdk/docs/what-is-raxan.html

```
