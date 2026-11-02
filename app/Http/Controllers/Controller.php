<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Http\RedirectResponse;

abstract class Controller
{
    protected function databaseFailure(string $recordName, Exception $exception, bool $withInput = true): RedirectResponse
    {
        report($exception);

        $message = $exception instanceof QueryException
            ? "Unable to complete the operation for this {$recordName}. Check that codes are unique and that no items depend on it."
            : "An unexpected error prevented the operation for this {$recordName}. Please try again.";

        $redirect = redirect()->back();

        if ($withInput) {
            $redirect->withInput();
        }

        return $redirect->withErrors(['alert' => $message]);
    }
}
