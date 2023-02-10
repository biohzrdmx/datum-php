<?php

declare(strict_types = 1);

/**
 * Datum
 * Simple (as in VERY simple) database abstraction layer
 * @author  biohzrdmx <github.com/biohzrdmx>
 * @license MIT
 */

namespace Datum;

use RuntimeException;
use Throwable;

use Datum\Adapter\AdapterInterface;

class DatabaseException extends RuntimeException {

	/**
	 * Adapter instance
	 * @var AdapterInterface
	 */
	protected $adapter;

	/**
	 * Constructor
	 * @param AdapterInterface $adapter Adapter instance
	 */
	public function __construct(AdapterInterface $adapter, string $message = '', int $code = 0, ?Throwable $previous = null) {
		parent::__construct($message, $code, $previous);
		$this->adapter = $adapter;
	}

	/**
	 * Get adapter instance
	 * @return AdapterInterface
	 */
	public function getAdapter(): AdapterInterface {
		return $this->adapter;
	}
}
