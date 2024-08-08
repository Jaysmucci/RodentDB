![RodentDB](https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcQTYSo-jZBI1ikXtttSMQJVyOVzlkTGbABU1A&s)
# Rodent DB - A SIMPLE PHP DATABASE WRAPPER
## Overview
RodentDB is a lightweight PHP class that implements a Singleton pattern to manage database connections and operations. It allows you to easily perform CRUD (Create, Read, Update, Delete) operations with MySQL databases using PDO.
## Features
* Singleton Pattern: Ensures a single database connection instance throughout the application.
* CRUD Operations: Supports easy insertion, selection, updating, and deletion of records.
* Error Handling: Provides basic error handling with options to extend for custom error management.
## Installation
### Prerequesites
* PHP 7.4 or higher
* MySQL 5.7 or higher
* PDO extension enabled in your PHP configuration
### Step 1: Clone the Repository

```bash
git clone https://github.com/jaysmuchi/RodentDB.git
cd RodentDB
```
### Step 2: Configuration
In the config.ini file in the root directory of your project, fill in your connection variables:

```php
[database]
hostname = "your_hostname"
port = "your_port"
database = "your_database_name"
username = "your_username"
password = "your_password"
```
### Include the Class
In your PHP script, include the `RodentDB` class:
```php
require_once 'path/to/RodentDB.php';
```
### Step 4: Usage
You can now start using the `RodentDB` class in your project.
### Example: Insert Data
```php
$db = Database::getInstance();

$data = [
    'column1' => 'value1',
    'column2' => 'value2'
];

$lastInsertId = $db->insert('your_table_name', $data);

if ($lastInsertId) {
    echo "Data inserted successfully with ID: " . $lastInsertId;
} else {
    echo "Insert failed.";
}
```
### Example: Select Data
```php
$conditions = [
    'column1' => 'value1',
    'column2' => 'value2'
];

$results = $db->select('your_table_name', $conditions);

foreach ($results as $row) {
    echo $row['column_name'];
}
```
### Example: Update Data
```php
$data = [
    'column1' => 'new_value1'
];

$conditions = [
    'column2' => 'value2'
];

$success = $db->update('your_table_name', $data, $conditions);

if ($success) {
    echo "Data updated successfully.";
} else {
    echo "Update failed.";
}
```
### Example: Delete Data
```php
$conditions = [
    'column1' => 'value1'
];

$success = $db->delete('your_table_name', $conditions);

if ($success) {
    echo "Data deleted successfully.";
} else {
    echo "Delete failed.";
}
```

## Error Handling
The `RodentDB` class includes basic error handling using try-catch blocks around critical operations like connecting to the database and executing queries. Errors will be logged or echoed depending on the context, and you can easily extend this behavior to suit your application's needs.
## Contributing
Contributions are welcome! Please feel free to submit a Pull Request or open an Issue if you have any suggestions or find any bugs.
## License
This project is licensed under the MIT License. See the LICENSE file for details.
