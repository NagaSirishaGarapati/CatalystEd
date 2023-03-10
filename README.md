# CatalystEd
Test Repository for inserting Users

# user_upload.php
#### --file [csv file name] (Required) - this is the name of the CSV to be parsed. If this option is not provided the script will die and show the message
Example command

    ```
    php user_upload.php --file users.csv
    ```

#### --create_table (Table Name - Optional) - If table name is provided, the script will use the provided table name otherwise default table name "Users" is used. This option will cause the MySQL table to be built (and no further action will be taken) - If table doesn't exist and if this option is not provided, the script will die and show the message.
Example command

    ```
    php user_upload.php --file users.csv --create_table=users

    or

    php user_upload.php --file users.csv --create_table
    ```

#### --dry_run  (Boolean - No Value required) - this will be used with the --file directive in case we want to run the script but not insert into the DB. All other functions will be executed, but the database won't be altered
Example command

    ```
    php user_upload.php --file users.csv --create_table --dry_run
    ```

#### -u (MySQL username - Optional) - if this option is not provided by default it will use username 'root'
#### -p (MySQL password - Optional) - if this option is not provided by default it will use password ''
#### -h (MySQL host - Optional) - if this option is not provided by default it will use 'localhost'
Example command

    ```
    php user_upload.php --file users.csv --create_table -uroot -proot -hlocalhost

    or

    php user_upload.php --file users.csv --create_table -u -p -h

    ```
#### --help (Boolean - No Value required)– which will output the above list of directives with details.
Example command

    ```
    php user_upload.php --help
    ```



