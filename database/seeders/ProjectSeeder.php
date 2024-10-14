<?php

namespace Database\Seeders;

use App\Models\Project\Project;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ProjectSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $allProjects = $this->getPredefinedProjects();

        foreach ($allProjects as $project)
        {
           $newProject = Project::factory()
               ->hasProducts(rand(6,10))
               ->create([
                'name' => $name = $project['name'],
                'url' => Str::slug($name),
                'short_desc' => $project['description'],
                'desc' => implode('<br>',fake()->paragraphs(4)),
            ]);

            $newProject->addMediaFromUrl('https://picsum.photos/400/300')->toMediaCollection('displayImage');



        }


    }




    private function getPredefinedProjects(): array
    {
        return [
            [
                'name' => 'E-Commerce Platform',
                'description' => 'Developed a robust e-commerce platform with integrated payment gateways, product management, and customer support features, enhancing the shopping experience.'
            ],
            [
                'name' => 'Corporate Website',
                'description' => 'Designed and built a professional corporate website showcasing company services, projects, and team, aimed at strengthening the brand\'s online presence.'
            ],
            [
                'name' => 'Mobile Application for Retail',
                'description' => 'Created a mobile app for a retail client, providing features like product catalog, secure checkout, and real-time order tracking, improving customer engagement.'
            ],
            [
                'name' => 'SaaS Product Development',
                'description' => 'Developed a SaaS platform offering scalable solutions for businesses, including CRM, invoicing, and analytics, designed for high performance and security.'
            ],
            [
                'name' => 'Content Management System (CMS)',
                'description' => 'Built a custom CMS allowing easy content and media management, tailored for a client\'s specific business needs, ensuring a user-friendly admin experience.'
            ],
            [
                'name' => 'E-Learning Platform',
                'description' => 'Designed and implemented an e-learning platform with interactive courses, quizzes, and certification, aimed at providing an engaging learning experience.'
            ],
            [
                'name' => 'API Integration and Development',
                'description' => 'Developed and integrated APIs for a financial services company, facilitating secure data exchange and enhancing system interoperability.'
            ],
            [
                'name' => 'Healthcare Web Application',
                'description' => 'Created a web application for healthcare providers, offering appointment scheduling, patient records management, and telemedicine capabilities.'
            ],
            [
                'name' => 'Real Estate Listing Website',
                'description' => 'Built a real estate listing website with advanced search filters, property details, and interactive maps, aimed at improving user navigation and experience.'
            ],
            [
                'name' => 'Online Marketplace',
                'description' => 'Developed an online marketplace platform supporting multiple vendors, product listings, and secure transactions, enhancing the shopping experience for users.'
            ]
        ];

    }



}
