<?php

namespace App\Services\BugTracker\Providers;

use App\Models\User;
use App\Services\BugTracker\BugTracker;
use Sentry\Severity;
use Sentry\State\Scope;
use Throwable;

class SentryTracker implements BugTracker
{
    public const SEVERITY_ERROR = 'error';

    public const SEVERITY_FATAL = 'fatal';

    public const SEVERITY_WARNING = 'warning';

    public const SEVERITY_INFO = 'info';
    
    public const SEVERITY_DEBUG = 'debug';

    public function __construct()
    {
        $this->withUser();
    }

    /**
     * @inherited
     */
    public function notifyException(Throwable $exception, ?array $payload = null): ?string
    {
        $this->handlePayload($payload);

        return \Sentry\captureException($exception);
    }

    /**
     * @inherited
     */
    public function notifyError(string $error, ?string $message = null, ?array $payload = null): ?string
    {
        $this->withSeverity(self::SEVERITY_ERROR);

        return $this->notifyMessage($error, $message, $payload);
    }

    /**
     * @inherited
     */
    public function notifyWarning(string $error, ?string $message = null, ?array $payload = null): ?string
    {
        $this->withSeverity(self::SEVERITY_WARNING);

        return $this->notifyMessage($error, $message, $payload);
    }

    /**
     * @inherited
     */
    public function notifyInfo(string $error, ?string $message = null, ?array $payload = null): ?string
    {
        $this->withSeverity(self::SEVERITY_INFO);

        return $this->notifyMessage($error, $message, $payload);
    }

    /**
     * @inherited
     */
    public function notifyFatal(string $error, ?string $message = null, ?array $payload = null): ?string
    {
        $this->withSeverity(self::SEVERITY_FATAL);

        return $this->notifyMessage($error, $message, $payload);
    }

    /**
     * @inherited
     */
    public function notifyDebug(string $error, ?string $message = null, ?array $payload = null): ?string
    {
        $this->withSeverity(self::SEVERITY_DEBUG);

        return $this->notifyMessage($error, $message, $payload);
    }

    /**
     * @inherited
     */
    public function withPayload(array $payload): BugTracker
    {
        foreach ($payload as $key => $value) {
            \Sentry\configureScope(function (Scope $scope) use ($key, $value) {
                $scope->setContext($key, $value);
            });
        }

        return $this;
    }

    /**
     * @inherited
     */
    public function withTags(array $tags): BugTracker
    {
        \Sentry\configureScope(function (Scope $scope) use ($tags) {
            $scope->setTags($tags);
        });

        return $this;
    }

    /**
     * @inherited
     */
    public function withSeverity(string $severity): BugTracker
    {
        \Sentry\configureScope(function (Scope $scope) use ($severity) {
            $severity = new Severity($severity);
            $scope->setLevel($severity);
        });

        return $this;
    }

    /**
     * @inherited
     */
    public function withUser(?User $user = null): BugTracker
    {
        $user = $user ?? auth()->user();

        if (!$user) {
            return $this;
        }

        \Sentry\configureScope(function (Scope $scope) use ($user) {
            $scope->setUser([
                'id' => $user->id,
                'email' => $user->email,
                'IP' => ip(),
            ]);
        });

        return $this;
    }

    /**
     * end a generic notification
     *
     * @param   string       $error
     * @param   string|null  $message
     * @param   array|null   $payload
     *
     * @return  string|null
     */
    protected function notifyMessage(string $error, ?string $message = null, ?array $payload = null): ?string
    {
        $this->handlePayload($payload);

        $event = $this->createEventMessagePayload($error, $message);

        return \Sentry\captureEvent($event);
    }

    /**
     * Handle the payload array to be injected 
     *
     * @param   array<string, array>  $payload
     *
     * @return  void
     */
    protected function handlePayload(?array $payload = null): void
    {
        if ($payload && count($payload) > 0) {
            $this->withPayload($payload);
        }
    }

    /**
     * Create a event to be sent
     *
     * @param   string  $error 
     * @param   string  $message
     * @param   null
     *
     * @return  array
     */
    protected function createEventMessagePayload(string $error, ?string $message = null): array
    {
        $message = $message ? "{$error}: $message" : $error;

        $event = [
            'message' => $message,
        ];

        return $event;
    }
}
