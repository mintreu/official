<?php

namespace App\Services\Saas;

use App\Models\Saas\SaasProject;
use Illuminate\Http\Request;

class SaasSignatureService
{
    public function verifyStatsPush(Request $request): bool
    {
        return $this->verifyInternalRequest($request);
    }

    public function verifyInternalRequest(Request $request): bool
    {
        return $this->resolveAuthorizedProject($request) !== null;
    }

    public function resolveAuthorizedProject(Request $request): ?string
    {
        $project = trim((string) $request->header('X-Mintreu-Project', ''));
        if ($project === '') {
            return null;
        }

        [$expectedKey, $secret] = $this->projectCredentials($project);
        if ($expectedKey === '' || $secret === '') {
            return null;
        }

        $key = (string) $request->header('X-Mintreu-Key', '');
        $timestamp = (string) $request->header('X-Mintreu-Timestamp', '');
        $signature = (string) $request->header('X-Mintreu-Signature', '');

        if (! hash_equals($expectedKey, $key) || $timestamp === '' || $signature === '') {
            return null;
        }

        if (! ctype_digit($timestamp)) {
            return null;
        }

        $maxSkew = max(30, (int) config('services.mintreu_saas.max_skew_seconds', 300));
        $delta = abs(time() - (int) $timestamp);
        if ($delta > $maxSkew) {
            return null;
        }

        $rawBody = $request->getContent();
        $path = ltrim($request->path(), '/');
        $method = strtoupper($request->method());
        $v1 = hash_hmac('sha256', $timestamp.'.'.$rawBody, $secret);
        $v2 = hash_hmac('sha256', "{$timestamp}.{$method}.{$path}.{$rawBody}", $secret);

        if (hash_equals($v1, $signature) || hash_equals($v2, $signature)) {
            return $project;
        }

        return null;
    }

    /**
     * @return array{0:string,1:string}
     */
    public function projectCredentials(string $project): array
    {
        $configProject = (array) data_get(config('services.mintreu_saas.projects', []), $project, []);
        $configKey = (string) ($configProject['internal_key'] ?? '');
        $configSecret = (string) ($configProject['internal_secret'] ?? '');

        $dbProject = SaasProject::query()->where('slug', $project)->first();
        $dbKey = (string) ($dbProject?->internal_key ?? '');
        $dbSecret = (string) ($dbProject?->internal_secret ?? '');

        $key = $dbKey !== '' ? $dbKey : $configKey;
        $secret = $dbSecret !== '' ? $dbSecret : $configSecret;

        if ($key === '' || $secret === '') {
            $key = (string) config('services.mintreu_saas.internal_key', '');
            $secret = (string) config('services.mintreu_saas.internal_secret', '');
        }

        return [$key, $secret];
    }
}
