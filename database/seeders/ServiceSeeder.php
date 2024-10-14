<?php

namespace Database\Seeders;

use App\Models\Service\Service;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ServiceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        $allServices = $this->getPredefinedServices();
        foreach ($allServices as $service)
        {
            Service::factory()->create([
                'name' => $service['name'],
                'short_desc' => $service['description']
            ]);
        }


    }


    private function getPredefinedServices(): array
    {
        return [
            [
                'name' => 'Web Development',
                'description' => 'Custom websites built with the latest technologies, ensuring responsive and high-performance digital experiences.'
            ],
            [
                'name' => 'Mobile App Development',
                'description' => 'Native and cross-platform mobile applications designed to deliver exceptional user experiences across devices.'
            ],
            [
                'name' => 'UI/UX Design',
                'description' => 'Intuitive and engaging designs that enhance user satisfaction and drive better results for your digital products.'
            ],
            [
                'name' => 'Digital Marketing',
                'description' => 'Strategies to enhance your online presence and reach your target audience effectively with measurable results.'
            ],
            [
                'name' => 'E-Commerce Solutions',
                'description' => 'Custom online stores with secure payment integration, designed to enhance your customers\' shopping experience.'
            ],
            [
                'name' => 'Content Management Systems',
                'description' => 'Custom CMS solutions that allow easy management of content and media, tailored to meet your specific business needs.'
            ],
            [
                'name' => 'SaaS Platforms',
                'description' => 'Scalable and robust Software as a Service (SaaS) platforms designed for various industries, ensuring high performance and security.'
            ],
            [
                'name' => 'API Development',
                'description' => 'Robust and secure API solutions that enable seamless integration and communication between different software systems.'
            ],
            [
                'name' => 'Cloud Solutions',
                'description' => 'Cloud architecture and migration services that enhance your business\'s flexibility, scalability, and security.'
            ],
            [
                'name' => 'IT Consulting',
                'description' => 'Expert IT consulting services to help you strategize and implement technology solutions that align with your business goals.'
            ]
        ];

    }


}
