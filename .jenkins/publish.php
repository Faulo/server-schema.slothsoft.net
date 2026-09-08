<?php

declare(strict_types=1);

if ($argc < 3) {
    fwrite(STDERR, "Usage: php publish.php <destination> <path> [path ...]\n");
    exit(2);
}

$workspace = realpath((string) getcwd());
$destination = realpath($argv[1]);

if ($workspace === false) {
    throw new RuntimeException('Could not resolve the workspace.');
}
if ($destination === false || !is_dir($destination)) {
    throw new InvalidArgumentException(sprintf('Destination directory does not exist: %s', $argv[1]));
}
if ($destination === dirname($destination)) {
    throw new InvalidArgumentException('Refusing to publish into a filesystem root.');
}

$pathKey = static function (string $path): string {
    $path = str_replace('\\', '/', $path);
    return PHP_OS_FAMILY === 'Windows' ? strtolower($path) : $path;
};

$isWithin = static function (string $path, string $directory) use ($pathKey): bool {
    $path = $pathKey(rtrim($path, '/\\'));
    $directory = $pathKey(rtrim($directory, '/\\'));

    return $path === $directory || strpos($path, $directory . '/') === 0;
};

$remove = function (string $path) use (&$remove): void {
    if (is_link($path) || is_file($path)) {
        if (!unlink($path)) {
            throw new RuntimeException(sprintf('Could not remove file: %s', $path));
        }
        return;
    }

    if (!is_dir($path)) {
        return;
    }

    $items = scandir($path);
    if ($items === false) {
        throw new RuntimeException(sprintf('Could not read directory: %s', $path));
    }

    foreach ($items as $item) {
        if ($item !== '.' && $item !== '..') {
            $remove($path . DIRECTORY_SEPARATOR . $item);
        }
    }

    if (!rmdir($path)) {
        throw new RuntimeException(sprintf('Could not remove directory: %s', $path));
    }
};

$setOwnership = static function (string $path): void {
    if (PHP_OS_FAMILY === 'Windows') {
        return;
    }

    @chown($path, 33);
    @chgrp($path, 33);
};

$copy = function (string $source, string $target) use (&$copy, $setOwnership): void {
    if (is_link($source)) {
        $linkTarget = readlink($source);
        if ($linkTarget === false || !symlink($linkTarget, $target)) {
            throw new RuntimeException(sprintf('Could not copy symbolic link: %s', $source));
        }
        return;
    }

    if (is_file($source)) {
        if (!copy($source, $target)) {
            throw new RuntimeException(sprintf('Could not copy file: %s', $source));
        }
        @chmod($target, fileperms($source) & 0777);
        $setOwnership($target);
        return;
    }

    if (!is_dir($source)) {
        throw new RuntimeException(sprintf('Unsupported source path: %s', $source));
    }

    if (!mkdir($target, fileperms($source) & 0777, true) && !is_dir($target)) {
        throw new RuntimeException(sprintf('Could not create directory: %s', $target));
    }
    $setOwnership($target);

    $items = scandir($source);
    if ($items === false) {
        throw new RuntimeException(sprintf('Could not read directory: %s', $source));
    }

    foreach ($items as $item) {
        if ($item !== '.' && $item !== '..') {
            $copy(
                $source . DIRECTORY_SEPARATOR . $item,
                $target . DIRECTORY_SEPARATOR . $item,
            );
        }
    }
};

$entries = [];
foreach (array_slice($argv, 2) as $argument) {
    $relative = str_replace('\\', '/', trim($argument));
    $parts = explode('/', $relative);

    if (
        $relative === ''
        || strpos($relative, '/') === 0
        || preg_match('/^[A-Za-z]:\//', $relative) === 1
        || count($parts) !== 1
        || in_array($relative, ['.', '..'], true)
    ) {
        throw new InvalidArgumentException(sprintf('Publish paths must be workspace root entries: %s', $argument));
    }

    $source = $workspace . DIRECTORY_SEPARATOR . $relative;
    if (!file_exists($source) && !is_link($source)) {
        throw new InvalidArgumentException(sprintf('Source path does not exist: %s', $source));
    }

    $resolvedSource = realpath($source);
    if ($resolvedSource === false || !$isWithin($resolvedSource, $workspace)) {
        throw new InvalidArgumentException(sprintf('Source path leaves the workspace: %s', $source));
    }
    if ($isWithin($resolvedSource, $destination)) {
        throw new InvalidArgumentException(sprintf('Source path is inside the destination: %s', $source));
    }

    $key = $pathKey($relative);
    if (isset($entries[$key])) {
        throw new InvalidArgumentException(sprintf('Source path was supplied more than once: %s', $relative));
    }

    $entries[$key] = [
        'name' => $relative,
        'source' => $source,
        'target' => $destination . DIRECTORY_SEPARATOR . $relative,
    ];
}

$staged = [];
$nonce = sprintf('.publish-%d-%s-', getmypid(), bin2hex(random_bytes(4)));

try {
    foreach (array_values($entries) as $index => $entry) {
        $stage = $destination . DIRECTORY_SEPARATOR . $nonce . $index;
        $entry['stage'] = $stage;
        $staged[] = $entry;
        $copy($entry['source'], $stage);
    }

    foreach ($staged as $entry) {
        $remove($entry['target']);
        if (!rename($entry['stage'], $entry['target'])) {
            throw new RuntimeException(sprintf('Could not publish path: %s', $entry['name']));
        }
    }
} finally {
    foreach ($staged as $entry) {
        $remove($entry['stage']);
    }
}

printf("Published %d workspace entries.\n", count($entries));
