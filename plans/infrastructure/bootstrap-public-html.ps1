$ErrorActionPreference = 'Stop'

$base = 'C:\laragon\www\mintreu\server\public_html'
$paths = @(
    "$base\local-map",
    "$base\local-map\api.mintreu.test",
    "$base\local-map\shopcore-api.mintreu.test",
    "$base\local-map\helpdesk-api.mintreu.test",
    "$base\local-map\licenseops-api.mintreu.test",
    "$base\production-map",
    "$base\production-map\mintreu.com",
    "$base\production-map\api.mintreu.com",
    "$base\production-map\shopcore-api.mintreu.com",
    "$base\production-map\helpdesk-api.mintreu.com",
    "$base\production-map\licenseops-api.mintreu.com"
)

foreach ($path in $paths) {
    if (-not (Test-Path $path)) {
        New-Item -ItemType Directory -Path $path | Out-Null
        Write-Host "Created: $path"
    } else {
        Write-Host "Exists : $path"
    }
}

Write-Host ''
Write-Host 'Done. Folder map ready.'
Write-Host 'Next: follow plans/infrastructure/laragon-hostinger-setup.md'
