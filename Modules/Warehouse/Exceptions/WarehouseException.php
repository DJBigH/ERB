<?php

namespace Modules\Warehouse\Exceptions;

use RuntimeException;

/**
 * Lỗi nghiệp vụ kho có kèm HTTP status (409 conflict / 422 unprocessable...).
 */
class WarehouseException extends RuntimeException
{
    public int $statusCode;

    public function __construct(string $message, int $statusCode = 409)
    {
        parent::__construct($message);
        $this->statusCode = $statusCode;
    }
}
