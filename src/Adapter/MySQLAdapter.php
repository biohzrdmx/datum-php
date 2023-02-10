<?php

declare(strict_types = 1);

/**
 * Datum
 * Simple (as in VERY simple) database abstraction layer
 * @author  biohzrdmx <github.com/biohzrdmx>
 * @license MIT
 */

namespace Datum\Adapter;

use PDO;
use PDOException;

use Datum\Adapter\AdapterInterface;
use Datum\Adapter\PDOAdapter;
use Datum\DatabaseException;

class MySQLAdapter extends PDOAdapter implements AdapterInterface {

	/**
	 * Connect adapter
	 * @return bool
	 */
	public function connect(): bool {
		$ret = false;
		# Get connection options
		$host = $this->options['host'] ?? '';
		$port = $this->options['port'] ?? '3306';
		$name = $this->options['name'] ?? '';
		$user = $this->options['user'] ?? '';
		$password = $this->options['password'] ?? '';
		# Build DSN
		$this->dsn = sprintf('mysql:host=%s;port=%s;dbname=%s', $host, $port, $name);
		try {
			$this->dbh = new PDO($this->dsn, $user, $password);
			$this->dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			$this->dbh->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_OBJ);
			$ret = true;
		} catch (PDOException $e) {
			throw new DatabaseException($this, $e->getMessage(), (int) $e->getCode(), $e->getPrevious());
		}
		return $ret;
	}
}
