# datum-php

Simple (as in VERY simple) database abstraction layer

### Basic usage

First require `biohzrdmx/datum-php` with Composer.

Then create a `Database` instance, to call the constructor you will need an `AdapterInterface` implementation instance; there are two adapters included: `MySQLAdapter` and `SQLiteAdapter`, both of which extend from `PDOAdapter`:

```php
$options = [
	'host' => getenv('TEST_DB_HOST') ?: 'localhost',
	'name' => getenv('TEST_DB_NAME') ?: 'test',
	'user' => getenv('TEST_DB_USER') ?: 'root',
];
$adapter = new MySQLAdapter($options);
$database = new Database($adapter);
```

Or using SQLite:

```php
$options = [
	'file' => 'path/to/database.sqlite'
];
$adapter = new SQLiteAdapter($options);
$database = new Database($adapter);
```

#### Simple queries

To run a simple query use the `query` method:

```php
$database->query("CREATE TABLE test ...");
```

```php
$database->query("DROP TABLE IF EXISTS test");
```

For `INSERT` statements you can easily get the auto-increment ID with the `lastInsertId` method:

```php
$database->query("INSERT INTO ...");
$id = $database->lastInsertId();
```

If you need to do more complex stuff, you may pass a `Closure` as the third argument, to manipulate the `PDOStatement` object directly:

```php
$params = [4];
$row = $database->query("SELECT * FROM test WHERE id = ?", $params, function($stmt) {
	return $stmt->fetch();
});
```

#### Selecting data

To retrieve data from the database, use the `select` method:

```php
$rows = $database->select("SELECT * FROM test");
```

If you need to pass parameters, pass an `array` as the second argument:

```php
$params = ['Draft'];
$rows = $database->select("SELECT * FROM test WHERE status = ?", $params);
```

Named parameters are supported too:

```php
$params = ['status' => 'Draft'];
$rows = $database->select("SELECT * FROM test WHERE status = :status", $params);
```

To retrieve just one row, use the `first` method:

```php
$params = ['id' => 2];
$row = $database->first("SELECT * FROM test WHERE id = :id", $params);
```

You can also query single, scalar values with the `scalar` method:

```php
$count = $database->scalar("SELECT count(*) FROM test");
```

Chunking is also possible by means of the `chunk` method:

```php
$params = ['Published'];
$database->chunk(100, "SELECT id, title FROM test WHERE status = ?", $params, function($rows) {
	foreach ($rows as $row) {
		// Do something with the row data
	}
});
```

Just pass the number of rows per chunk, the query, its parameters as an `array` and a `Closure` that will be executed for each chunk, and that receives the `$rows`.

#### Transactions

Automatic transactions are supported, if the query generates an error the transaction will be rolled-back automatically, otherwise it will commit.

To use them, call the `transaction` method:

```php
$database->transaction(function($database) {
	$params = ['Draft', 2];
	$database->query("UPDATE test SET status = ? WHERE id = ?", $params);
});
```

Pass a `Closure`, inside it you will call your queries by using the `$database` argument that it receives.

You can also use manual transactions with the `begin`, `commit` and `rollback` methods:

```php
$params = ['Published', 3];
try {
	$database->begin();
	$database->query("UPDATE test SET status = ? WHERE id = ?", $params);
	$database->commit();
} catch (Exception $e) {
	$database->rollback();
}
```

### Licensing

This software is released under the MIT license.

Copyright © 2023 biohzrdmx

Permission is hereby granted, free of charge, to any person obtaining a copy of this software and associated documentation files (the "Software"), to deal in the Software without restriction, including without limitation the rights to use, copy, modify, merge, publish, distribute, sublicense, and/or sell copies of the Software, and to permit persons to whom the Software is furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.

### Credits

**Lead coder:** biohzrdmx [github.com/biohzrdmx](http://github.com/biohzrdmx)