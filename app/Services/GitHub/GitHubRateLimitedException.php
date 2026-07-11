<?php

declare(strict_types=1);

namespace App\Services\GitHub;

use RuntimeException;

class GitHubRateLimitedException extends RuntimeException {}
