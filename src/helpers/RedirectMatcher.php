<?php

namespace recranet\redirects\helpers;

/**
 * Pure matching logic, decoupled from Craft so it can be unit tested.
 * Operates on plain database rows (arrays).
 */
final class RedirectMatcher
{
    /**
     * Find the first matching redirect row for a path.
     *
     * @param array $redirects Rows from the redirects table (enabled ones)
     * @param string $path Request path, or a full URL
     * @param int|null $siteId Current site ID (null matches any)
     * @param string|null $hostInfo e.g. "https://example.com", enables full-URL matching
     * @param \DateTimeInterface|null $now Reference time for expiry checks (UTC)
     * @return array|null The matched row, with toUrl resolved for regex matches
     */
    public static function match(
        array $redirects,
        string $path,
        ?int $siteId = null,
        ?string $hostInfo = null,
        ?\DateTimeInterface $now = null,
    ): ?array {
        $now = $now ?? new \DateTimeImmutable('now', new \DateTimeZone('UTC'));

        // Accept a full URL as input (e.g. from the Test URL tool)
        if (preg_match('#^(https?://[^/]+)(/.*)?$#i', $path, $matches)) {
            $hostInfo = $matches[1];
            $path = $matches[2] ?? '/';
        }

        $path = '/' . ltrim($path, '/');
        $normalizedPath = strtolower(rtrim($path, '/'));

        // Exact-match candidates: the path, and the full URL when the host is known
        $candidates = [$normalizedPath];
        if ($hostInfo) {
            $candidates[] = strtolower(rtrim($hostInfo, '/')) . $normalizedPath;
        }

        $exactMatches = [];
        $regexRows = [];

        foreach ($redirects as $row) {
            if (!self::isActive($row, $siteId, $now)) {
                continue;
            }

            if (($row['matchType'] ?? 'exact') === 'regex') {
                $regexRows[] = $row;
                continue;
            }

            $from = strtolower(rtrim((string)($row['fromUrl'] ?? ''), '/'));
            if (in_array($from, $candidates, true)) {
                $exactMatches[] = $row;
            }
        }

        // Site-specific wins over global
        foreach ($exactMatches as $row) {
            if (($row['siteId'] ?? null) !== null) {
                return $row;
            }
        }
        if ($exactMatches) {
            return $exactMatches[0];
        }

        // Regex matches: site-specific first, matched against the path and the full URL
        usort($regexRows, fn(array $a, array $b) => (($a['siteId'] ?? null) === null) <=> (($b['siteId'] ?? null) === null));

        $subjects = [$path];
        if ($hostInfo) {
            $subjects[] = rtrim($hostInfo, '/') . $path;
        }

        foreach ($regexRows as $row) {
            $pattern = '#' . $row['fromUrl'] . '#i';

            foreach ($subjects as $subject) {
                if (@preg_match($pattern, $subject)) {
                    // Support $1, $2 etc. backreferences in toUrl
                    $row['toUrl'] = @preg_replace($pattern, (string)$row['toUrl'], $subject);
                    return $row;
                }
            }
        }

        return null;
    }

    /**
     * Append the incoming query string to a destination URL.
     */
    public static function appendQueryString(string $toUrl, string $queryString): string
    {
        if ($queryString === '') {
            return $toUrl;
        }

        return $toUrl . (str_contains($toUrl, '?') ? '&' : '?') . $queryString;
    }

    private static function isActive(array $row, ?int $siteId, \DateTimeInterface $now): bool
    {
        if (!($row['enabled'] ?? true)) {
            return false;
        }

        $rowSiteId = $row['siteId'] ?? null;
        if ($rowSiteId !== null && $siteId !== null && (int)$rowSiteId !== $siteId) {
            return false;
        }

        if (!empty($row['expiryDate'])) {
            $expiry = new \DateTimeImmutable($row['expiryDate'], new \DateTimeZone('UTC'));
            if ($expiry <= $now) {
                return false;
            }
        }

        return true;
    }
}
