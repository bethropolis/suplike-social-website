<?php

class RateLimiter
{
    private $rateLimits;
    private $retryAfter;
    private $sessionKey;
    private $errorRedirectUrl;

    public function __construct($rateLimits = [], $errorRedirectUrl = '/error.php')
    {
        $this->rateLimits = $rateLimits;
        $this->retryAfter = 0;
        $this->sessionKey = 'rate_limiter_requests';
        $this->errorRedirectUrl = $errorRedirectUrl;
    }

    public function handleRequest($userRole = 'default')
    {
        session_start();

        try {
            $this->initializeSessionVariables();
            $this->incrementRequestCount();
            $this->checkRateLimit($userRole);
        } catch (Exception $e) {
            $this->handleError($e->getMessage());
        }
    }

    private function initializeSessionVariables()
    {
        if (!isset($_SESSION[$this->sessionKey])) {
            $_SESSION[$this->sessionKey] = [];
        }
    }

    private function incrementRequestCount()
    {
        if (!isset($_SESSION[$this->sessionKey]['start_time'])) {
            $_SESSION[$this->sessionKey]['start_time'] = time();
        }

        $userRole = $this->getUserRole();
        if (!isset($_SESSION[$this->sessionKey][$userRole])) {
            $_SESSION[$this->sessionKey][$userRole] = 1;
        } else {
            $_SESSION[$this->sessionKey][$userRole]++;
        }
    }

    private function getUserRole()
    {
        if (isset($_SESSION['isAdmin']) && $_SESSION['isAdmin']) {
            return 'admin';
        }

        return 'default';
    }

    private function checkRateLimit()
    {
        $userRole = $this->getUserRole();

        if (!isset($this->rateLimits[$userRole])) {
            return; // No rate limit defined for the user's role, so no rate limiting applied
        }

        $rateLimit = $this->rateLimits[$userRole];
        $now = time();
        $startTime = $_SESSION[$this->sessionKey]['start_time'];
        $requestCount = $_SESSION[$this->sessionKey][$userRole];

        if ($now - $startTime > $rateLimit['time_period']) {
            $_SESSION[$this->sessionKey]['start_time'] = $now;
            $_SESSION[$this->sessionKey][$userRole] = 1;
        } elseif ($requestCount > $rateLimit['max_requests']) {
            $this->setRetryAfterHeader($startTime, $rateLimit['time_period']);
            http_response_code(429);
            exit();
        }
    }

    private function setRetryAfterHeader($startTime, $timePeriod)
    {
        $this->retryAfter = $startTime + $timePeriod - time();
        header("Retry-After: {$this->retryAfter}");
    }

    private function handleError($errorMessage)
    {
        // You can display an error message or redirect the user to an error page
        header("Location: {$this->errorRedirectUrl}");
        exit();
    }
}



$rateLimits = [
    'default' => [
        'time_period' => 60,
        'max_requests' => 60
    ],
    'admin' => [
        'time_period' => 30,
        'max_requests' => 100
    ]
];

$limiter = new RateLimiter($rateLimits);
$limiter->handleRequest();

session_destroy();