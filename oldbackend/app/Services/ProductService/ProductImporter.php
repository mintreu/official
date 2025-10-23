<?php

namespace App\Services\ProductService;

use Illuminate\Support\Facades\Http;

class ProductImporter
{

    protected ?string $apiUrl = null;
    protected array $versions = [];
    protected ?string $defaultVersion = null;
    protected $version;
    protected array $plans = [];
    protected array $durations = [];
    protected array $priceList = [];
    protected ?string $currency = null;

    public static function make(string $url):static
    {
        $instance = new static();
        $instance->apiUrl = $url.'/m_product';
        return $instance;
    }



    public function loadVersions(): array
    {
        if (empty($this->versions))
        {
            $versionDetails =  $this->getApiData($this->apiUrl.'/versions');
            $this->versions = $versionDetails['versions'];
            $this->defaultVersion = $versionDetails['default'];
        }
        return $this->versions;
    }

    public function getVersions(): array
    {
        return $this->versions;
    }

    public function getDefaultVersion(): ?string
    {
        if (empty($this->versions))
        {
            $this->version = $this->loadVersions();
        }
        return $this->defaultVersion;
    }


    public function getProjectName(): ?string
    {
        return $this->version['name'];
    }

    public function getProjectDescription(): ?string
    {
        return $this->version['description'];
    }


    public function loadVersionDetail(?string $version = null): bool
    {
        $versionUrl = $this->apiUrl.'/version/'.$version;
        $this->version = $this->getApiData($versionUrl)['data'];
        return !empty($this->version);
    }


    public function loadVersionPlans(string $version): bool
    {
        $planUrl = $this->apiUrl.'/version/'.$version.'/plans';
        $versionDetail = $this->getApiData($planUrl);
        $this->plans = $versionDetail['plans'];
        $this->durations = $versionDetail['durations'];
        $this->priceList = $versionDetail['price_list'];
        $this->currency = $versionDetail['currency'];
        return empty($this->plans);
    }

    public function getPlans(): array
    {
        return $this->plans;
    }

    public function getDurations(): array
    {
        return $this->durations;
    }

    public function getPlanPrices(): array
    {
        return $this->priceList;
    }

    public function getCurrency(): ?string
    {
        return $this->currency;
    }


    public function resolve():bool
    {
        $responseData = $this->getApiData($this->apiUrl);
        if (!empty($responseData))
        {
            $this->versions = $this->getApiData($responseData['routes']['versions']);
        }

        return true;
    }



    protected function getApiData(string $url):array
    {
        $response = Http::get($url);
        if ($response->successful()) {
            return $response->json();
        }
        return [];
    }




}
