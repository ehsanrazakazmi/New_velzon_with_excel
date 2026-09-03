<?php

/**
 * Mints a fresh onboarding user + signed one-time sign-in link for the
 * Playwright suite, and writes it to e2e/.fixtures/welcome.json.
 *
 * Run automatically by e2e/global-setup.ts before the suite. Needed because the
 * link is single-use by design, so a previous run always burns it.
 *
 *   php e2e/fixtures/make-welcome.php [baseUrl]
 */

use App\Models\User;
use Illuminate\Contracts\Console\Kernel;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Str;

$root = dirname(__DIR__, 2);

require $root . '/vendor/autoload.php';
$app = require $root . '/bootstrap/app.php';
$app->make(Kernel::class)->bootstrap();

$baseUrl = rtrim($argv[1] ?? 'http://localhost/New_velzon_with_excel/public', '/');

// The signature covers the whole URL, so the root must match what the browser
// will actually request.
URL::forceRootUrl($baseUrl);

// Clear out users left by earlier runs.
User::where('email', 'like', 'magic%@example.test')->orWhere('email', 'like', 'pending%@example.test')->delete();

$make = function (string $email, string $name) {
    $user = User::create([
        'name'     => $name,
        'email'    => $email,
        'password' => bcrypt('initial-pass-123'),
    ]);
    $user->assignRole('Mini-Admin');

    $token = Str::random(48);
    $user->forceFill([
        'welcome_token'        => hash('sha256', $token),
        'must_change_password' => true,
    ])->save();

    return [$user, $token];
};

$stamp = time();

// Consumed by the happy-path journey test.
[$journey, $journeyToken] = $make("magic{$stamp}@example.test", 'Magic Tester');

// Never activated - backs the "password login is refused" and resend tests.
[$pending] = $make("pending{$stamp}@example.test", 'Pending Tester');

$url = URL::temporarySignedRoute(
    'welcome.login',
    now()->addHours(48),
    ['user' => $journey->id, 'token' => $journeyToken]
);

$dir = $root . '/e2e/.fixtures';
if (!is_dir($dir)) {
    mkdir($dir, 0777, true);
}

file_put_contents(
    $dir . '/welcome.json',
    json_encode([
        'url'      => $url,
        'email'    => $journey->email,
        'id'       => $journey->id,
        'password' => 'initial-pass-123',
        'pending'  => ['email' => $pending->email, 'id' => $pending->id],
    ], JSON_PRETTY_PRINT)
);

echo "welcome fixtures ready: journey {$journey->id}, pending {$pending->id}\n";
