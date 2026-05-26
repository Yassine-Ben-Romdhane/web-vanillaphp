# PHP Course Summary (Cours Web 4 PHP)

This document outlines the scope of concepts, functions, and syntaxes covered in the PHP course. Please restrict code generation to these native PHP approaches (e.g., using PDO instead of mysqli, spl_autoload_register instead of modern Composer PSR-4 out-of-the-box setups) to ensure the project stays exactly within the course bounds.

## 1. PHP Basics & Syntax
* **Introduction**: PHP as a server-side, interpreted language and its integration into HTML (using `<?php ?>` tags and `include()`).
* **Variables & Constants**: Variable declaration (`$`), dynamic variables (`$$`), variable scope (`global`, `$GLOBALS`), and defining constants with `define()`.
* **Variable Checking**: Functions to verify and cast variables, including `isset()`, `empty()`, `gettype()`, `intval()`, `floatval()`, etc.

## 2. Data Structures & Types
* **Strings**: Standard manipulation functions such as `explode()`, `implode()`, `strlen()`, `strpos()`, `substr()`, `str_replace()`, `htmlspecialchars()`, etc.
* **Arrays**: Creation of indexed and associative arrays, array manipulation (`array_merge`, `array_diff`, `array_intersect`), and sorting (like `sort()` or custom sorting using `uasort()`).
* **Dates**: Managing dates and timestamps using `date()`, `getdate()`, and `time()`.

## 3. Control Structures & Functions
* **Conditionals**: Standard `if`, `elseif`, and `else` statements.
* **Functions**: Creating functions, returning values, passing parameters by reference (using `&`), recursive calls, and dynamic function calling.
* **Dynamic Arguments**: Handling an unknown number of arguments using `func_num_args()` and `func_get_arg()`.

## 4. Form Handling & Environment Variables
* **Superglobals**: Using `$_SERVER` to retrieve client/server environment variables.
* **Forms**: Retrieving form submissions using `$_GET` and `$_POST`.

## 5. State Management
* **Sessions**: Starting sessions with `session_start()`, storing data in `$_SESSION`, and destroying sessions with `session_destroy()` and `session_unset()`.
* **Cookies**: Creating cookies via `setcookie()`, reading them via `$_COOKIE`, and deleting them by setting past expiration dates.

## 6. Error Handling
* **Exceptions**: Using `try`, `catch`, and throwing exceptions with `throw new Exception()`.

## 7. Object-Oriented Programming (PHP 5)
* **Classes & Objects**: Defining classes, instantiating objects with `new`, and cloning objects with `clone`.
* **Visibility**: Using `public`, `private`, and `protected` access modifiers.
* **Static & Constants**: Defining static attributes/methods (`static`, `self::`), and class constants (`const`).
* **Inheritance**: Extending classes (`extends`), accessing parent methods (`parent::`), defining `abstract` classes/methods, and using `final` methods.
* **Interfaces**: Creating interfaces (`interface`) and implementing them (`implements`).
* **Autoloading**: Using `spl_autoload_register()` to automatically load class files.

## 8. Database Access (PDO)
* **Connecting & Querying**: Using PDO to execute simple queries (`query()`) and extracting results with `fetch()` and `fetchAll()`.
* **Fetch Styles**: Formatting results using fetch styles like `PDO::FETCH_ASSOC`, `PDO::FETCH_OBJ`, and `PDO::FETCH_BOTH`.
* **Prepared Statements**: Preventing SQL injection by using `prepare()` and `execute()` for safe queries.

## 9. PHP 7 Specific Features
* **New Operators**: The spaceship operator (`<=>`) for comparisons and the null coalescing operator (`??`) for quick `isset()` checks.
* **Anonymous Classes**: Defining classes on the fly with `new class`.
