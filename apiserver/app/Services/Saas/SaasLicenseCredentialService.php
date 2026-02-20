<?php

namespace App\Services\Saas;

class SaasLicenseCredentialService
{
    public function isValid(string $project, string $licenseKey, string $licenseSecret): bool
    {
        if ($project === '' || $licenseKey === '' || $licenseSecret === '') {
            return false;
        }

        $normalizedProject = strtoupper(preg_replace('/[^A-Z0-9]/', '_', strtoupper($project)) ?? '');
        $projectKeyVar = 'MINTREU_SAAS_'.$normalizedProject.'_LICENSE_KEY';
        $projectSecretVar = 'MINTREU_SAAS_'.$normalizedProject.'_LICENSE_SECRET';

        $expectedKey = (string) (env($projectKeyVar) ?: config('services.mintreu_saas.license_key', ''));
        $expectedSecret = (string) (env($projectSecretVar) ?: config('services.mintreu_saas.license_secret', ''));

        if ($expectedKey === '' || $expectedSecret === '') {
            return false;
        }

        return hash_equals($expectedKey, $licenseKey)
            && hash_equals($expectedSecret, $licenseSecret);
    }
}
