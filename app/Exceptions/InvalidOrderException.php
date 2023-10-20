<?php
namespace App\Exceptions;
 
use Exception;
 
class InvalidOrderException extends Exception
{
    // ...
 
    /**
     * Get the exception's context information.
     *
     * @return array<string, mixed>
     */
    public function context(): array
    {
        return ['order_id' => $this->orderId];
    }
}