<?php

namespace App\Casts;

use App\Filament\Common\Resources\Office\EmployeeResource;
use App\Filament\Common\Resources\UserResource;
use App\Filament\Resources\AdminResource;
use App\Models\Admin;
use App\Models\Office\Employee;
use App\Models\User;
use Filament\Support\Colors\Color;
use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasIcon;
use Filament\Support\Contracts\HasLabel;

enum PanelGuardCast: string implements HasLabel, HasIcon, HasColor
{


    case ADMIN = 'admin';
    case OFFICE = 'office';
    case APP = 'app';

    case AFFILIATE = 'affiliate';


    /**
     * @return string|array|null
     */
    public function getColor(): string|array|null
    {
        return match ($this) {
            self::APP => Color::Indigo,
            self::ADMIN => Color::Pink,
            self::OFFICE => Color::Orange,
        };
    }

    /**
     * @return string|null
     */
    public function getIcon(): ?string
    {
        return match ($this) {
            self::APP => 'heroicon-s-user',
            self::OFFICE => 'heroicon-o-user',
            self::ADMIN => 'heroicon-s-no-symbol',
        };
    }

    /**
     * @return string|null
     */
    public function getLabel(): ?string
    {
        return match ($this) {
            self::APP => 'Member Panel',
            self::ADMIN => 'Admin Panel',
            self::OFFICE => 'Office Panel',
        };
    }


    /**
     * @return string|null
     */
    public function getPanelPath(): ?string
    {
        return match ($this) {
            self::APP => 'app',
            self::ADMIN => 'admin',
            self::OFFICE => 'office',
        };
    }


    /**
     * @return string|null
     */
    public function getPanelGuard(): ?string
    {
        return match ($this) {
            self::APP => 'web',
            self::ADMIN => 'admin',
            self::OFFICE => 'office',
        };
    }


    /**
     * Get the brand name for the panel.
     *
     * @return string|null
     */
    public function getBrandName(): ?string
    {
        return match ($this) {
            self::APP => 'Member Panel',
            self::ADMIN => 'Admin Panel',
            self::OFFICE => 'Office Panel',
        };
    }

    public function getAuthAccountUrl():?string
    {
        $filamentUser = filament()->auth()->user();
        if (!$filamentUser)
        {
            return null;
        }
        return match ($this) {
            self::APP => UserResource::getUrl('view',['record' => $filamentUser->referral_code]),
            self::ADMIN => AdminResource::getUrl('view',['record' => $filamentUser->id]),
            self::OFFICE => EmployeeResource::getUrl('view',['record' => $filamentUser->uuid]),
        };
    }


    public function getModelTable(): string
    {
        return match ($this) {
            self::APP => 'users',
            self::ADMIN => 'admins',
            self::OFFICE => 'employees',
            self::PROVIDER => 'service_points',
            self::APPLICANT => 'applicants',
        };
    }



    public function getModel(): string
    {
        return match ($this) {
            self::APP => User::class,
            self::ADMIN => Admin::class,
            self::OFFICE => Employee::class,
            self::PROVIDER => ServicePoint::class,
            self::APPLICANT => Applicant::class
        };
    }


    /**
     * @return string|null
     */
    public function getPanelDomain(): ?string
    {
        return match ($this) {
            self::APP => $this->getDomainForPanel('marketplace'),
            self::ADMIN => $this->getDomainForPanel('admin'),
            self::OFFICE => $this->getDomainForPanel('office'),
            self::PROVIDER => $this->getDomainForPanel('service'),
            self::APPLICANT => $this->getDomainForPanel('applicant'),
        };
    }

    public function getFontFamily()
    {
        return match ($this) {
            self::APP, self::APPLICANT => 'Nunito Sans',
            self::ADMIN => 'Montserrat',
            self::OFFICE => 'Merriweather',
            self::PROVIDER => 'Source Sans Pro'
        };
    }

    /**
     * Helper method to generate domain URLs for a specific panel.
     *
     * @param string $subdomain
     * @return string
     */
    protected function getDomainForPanel(string $subdomain): string
    {
        if (app()->isLocal() || config('app.env') === 'testing') {
           // return config('app.url');
            return '/';
        }

        if (config('app.env') === 'staging' || app()->isProduction()) {
            return "{$subdomain}." . config('app.domain');
        }

       // return config('app.url'); // Fallback in case no condition matches
    }


    public function validate(): bool
    {
        return filament()->getCurrentPanel()->getId() === $this->value;
    }

    public static function ActivePanel(): ?PanelGuardCast
    {
        return self::tryFrom(filament()->getCurrentPanel()->getId());
    }


}
