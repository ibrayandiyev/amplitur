<?php

namespace App\Services\BugTracker;

use Throwable;
use App\Models\User;

interface BugTracker
{
    /**
     * Notify a specific exception
     *
     * @param  Throwable                    $exception
     * @param  null|array<string, mixed>    $payload
     * 
     * @return null|string
     */
    public function notifyException(Throwable $exception, ?array $payload = null): ?string;

    /**
     * Notify a generic error message
     *
     * @param   string       $error
     * @param   string|null  $message
     * @param   array|null   $payload
     *
     * @return  string|null
     */
    public function notifyError(string $error, ?string $message = null, ?array $payload = null): ?string;

    /**
     * Notify a generic warning message
     * 
     * @param   string       $error
     * @param   string|null  $message
     * @param   array|null   $payload
     *
     * @return  string|null
     */
    public function notifyWarning(string $error, ?string $message = null, ?array $payload = null): ?string;

    /**
     * Notify a generic information message
     * 
     * @param   string       $error
     * @param   string|null  $message
     * @param   array|null   $payload
     *
     * @return  string|null
     */
    public function notifyInfo(string $error, ?string $message = null, ?array $payload = null): ?string;

    /**
     * Notify a generic fatal error message
     * 
     * @param   string       $error
     * @param   string|null  $message
     * @param   array|null   $payload
     *
     * @return  string|null
     */
    public function notifyFatal(string $error, ?string $message = null, ?array $payload = null): ?string;

    /**
     * Notify a generic debug message
     * 
     * @param   string       $error
     * @param   string|null  $message
     * @param   array|null   $payload
     *
     * @return  string|null
     */
    public function notifyDebug(string $error, ?string $message = null, ?array $payload = null): ?string;

    /**
     * Inject a payload to the notification
     *
     * @param   array<string, array>  $payload
     *
     * @return  BugTracker
     */
    public function withPayload(array $payload): BugTracker;

    /**
     * Inject a list of tags to the notification
     *
     * @param   array<string, array>  $tags
     *
     * @return  BugTracker
     */
    public function withTags(array $tags): BugTracker;

    /**
     * Inject and override severity of the the notification
     *
     * @param   string         $severity
     *
     * @return  BugTracker
     */
    public function withSeverity(string $severity): BugTracker;

    /**
     * Inject the user information to the notification.
     * If non user passed, authenticated user will be injected.
     *
     * @param   User|null           $user 
     * @param   null
     *
     * @return  BugTracker
     */
    public function withUser(?User $user = null): BugTracker;
}