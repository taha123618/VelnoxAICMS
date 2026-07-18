<?php

use Illuminate\Foundation\Testing\RefreshDatabase;
use Modules\Auth\Models\User;
use Modules\Builder\Agents\PageSectionGenerator;
use function Pest\Laravel\actingAs;
use function Pest\Laravel\postJson;

uses(RefreshDatabase::class);

dataset('ai_generation_scenarios', [
    // Layout & Page Generation
    'Full Page Generation' => ['Generate a complete multi-section landing page with a hero, features, pricing, and footer'],
    'Landing Page Generation' => ['Generate a SaaS landing page layout'],
    'Homepage Generation' => ['Create a modern homepage for a digital agency'],
    'About Page Generation' => ['Create an About Us page with company history and team members'],
    'Contact Page Generation' => ['A contact page with a form, location map wrapper, and contact details'],
    'Blog Page Generation' => ['A blog listing page with a grid of articles'],
    'Portfolio Page Generation' => ['A creative portfolio page with a gallery grid'],
    'E-commerce Page Generation' => ['An e-commerce storefront with a featured banner and product grid'],

    // Section Generation
    'Navbar Generation' => ['Generate a navbar with a logo, links, and a CTA button'],
    'Hero Section Generation' => ['Create a hero section with a dark background, headline, and two buttons'],
    'Slider/Carousel Generation' => ['Generate a slider or carousel layout with 3 images and captions'],
    'Banner Generation' => ['A promotional banner section with text and a button'],
    'CTA Section Generation' => ['A call to action section with a bold heading and primary button'],
    'Features Section Generation' => ['A 3-column features grid with icons, titles, and descriptions'],
    'Services Section Generation' => ['A services section outlining 4 different offerings in cards'],
    'Pricing Table Generation' => ['A pricing table with 3 tiers, highlighting the middle tier'],
    'Testimonials Generation' => ['A testimonials section with 3 customer reviews and avatars'],
    'Team Section Generation' => ['A team section displaying 4 team members with photos and roles'],
    'Statistics/Counter Generation' => ['A statistics section with 4 numerical counters'],
    'Timeline Generation' => ['A vertical timeline section showing company milestones'],
    'FAQ Generation' => ['An FAQ section with collapsible accordion items'],
    'Gallery Generation' => ['A masonry image gallery section'],
    'Portfolio Section Generation' => ['A portfolio showcase section with project thumbnails'],
    'Clients/Partners Section Generation' => ['A logo cloud section for trusted partners'],
    'Contact Form Generation' => ['A contact form section with name, email, message inputs and a submit button'],
    'Newsletter Section Generation' => ['A newsletter subscription section with an email input'],
    'Footer Generation' => ['A comprehensive footer with 4 columns of links and social media icons'],

    // Content Generation
    'Headline Generation' => ['Generate a catchy H1 headline for a coffee shop'],
    'Paragraph Generation' => ['Write a descriptive paragraph about sustainable farming'],
    'Blog Article Generation' => ['A blog article layout with a featured image, H1 title, and paragraphs'],
    'Product Description Generation' => ['A persuasive product description for running shoes'],
    'FAQ Content Generation' => ['Generate 5 common FAQ questions and answers for a web hosting service'],
    'SEO Meta Generation' => ['Generate SEO meta title and description for a bakery website'],
    'Call-to-Action Copy Generation' => ['Generate persuasive CTA button text'],
    'Translation Generation' => ['Translate the following text to Spanish'],
    'Content Rewriting' => ['Rewrite this paragraph to sound more professional'],
    'Content Summarization' => ['Summarize this long text into 3 bullet points'],

    // Media Generation
    'Image Suggestions' => ['Suggest 3 placeholder images for a travel blog'],
    'Icon Suggestions' => ['Suggest icon classes for a features grid'],
    'Background Generation' => ['Generate a dark gradient background wrapper'],
    'Illustration Generation' => ['Suggest an illustration for a 404 error page'],
    'Video Embed Suggestions' => ['Generate a video embed wrapper layout'],

    // Component Generation
    'Button Generation' => ['A primary action button'],
    'Card Generation' => ['A reusable feature card component'],
    'Accordion Generation' => ['An accordion component with 3 items'],
    'Tabs Generation' => ['A tabs component layout'],
    'Modal Generation' => ['A popup modal layout with a close button'],
    'Form Generation' => ['A generic input form layout'],
    'Table Generation' => ['A data table layout with 3 columns'],
    'Pricing Card Generation' => ['A single pricing tier card'],
    'Feature Card Generation' => ['A feature card with an icon and description'],
    'Alert Generation' => ['A success alert banner component'],

    // Theme & Styling Generation
    'Theme Generation' => ['A cohesive theme layout using modern web design principles'],
    'Color Palette Generation' => ['Apply a pastel color palette to a features section'],
    'Typography Suggestions' => ['A section using serif typography for headings'],
    'Layout Variations' => ['A split-screen layout variation'],
    'Responsive Design Generation' => ['A section that stacks vertically on mobile but uses a grid on desktop'],
    'Dark Mode Generation' => ['A sleek dark mode contact form section'],
    'Animation Suggestions' => ['A section with fade-in animation classes applied'],

    // E-commerce Generation
    'Product Page Generation' => ['An e-commerce product detail section with an image gallery and add to cart button'],
    'Category Page Generation' => ['An e-commerce product category listing layout'],
    'Checkout Page Generation' => ['A checkout form layout with billing and shipping fields'],
    'Shopping Cart Generation' => ['A shopping cart table layout with item quantities'],
    'Product Grid Generation' => ['A 4-column product grid'],

    // Blog & CMS Generation
    'Blog Layout Generation' => ['A blog index layout with a sidebar and main content area'],
    'Author Page Generation' => ['An author profile page layout with a bio and recent posts'],
    'Category Page Generation (Blog)' => ['A blog category archive layout'],
    'Tag Page Generation' => ['A tag archive layout for blog posts'],
    'Search Page Generation' => ['A search results page layout with a search bar and results grid'],

    // Builder & Template Generation
    'Block Generation' => ['A reusable content block template'],
    'Component Generation' => ['A reusable UI component structure'],
    'Template Generation' => ['A full page template skeleton'],
    'Widget Generation' => ['A sidebar widget layout'],
    'Dynamic Layout Generation' => ['A dynamic layout that adapts based on content length'],
]);

it('successfully processes various ai generation requests', function (string $prompt) {
    // Fake the AI response to simulate a successful generation without hitting the actual API
    PageSectionGenerator::fake([
        [
            'elements' => [
                [
                    'type' => 'wrapper',
                    'name' => 'Generated Wrapper',
                    'isLayoutElement' => false,
                    'canDrop' => true,
                    'children' => [
                        [
                            'type' => 'heading',
                            'name' => 'Heading',
                            'isLayoutElement' => false,
                            'canDrop' => false,
                            'children' => [],
                            'props' => [
                                'tag' => 'h2',
                                'content' => ['innerText' => 'Mocked AI Content'],
                            ]
                        ]
                    ],
                    'props' => [
                        'padding' => '20px'
                    ]
                ]
            ]
        ]
    ]);

    $user = User::factory()->create();

    $response = actingAs($user, 'sanctum')->postJson('/api/ai/generate-section', [
        'prompt' => $prompt,
    ]);

    $response->assertStatus(200)
        ->assertJsonStructure([
            'elements' => [
                '*' => [
                    'type',
                    'name',
                    'isLayoutElement',
                    'canDrop',
                    'children',
                    'props'
                ]
            ]
        ]);
        
    expect($response->json('elements.0.type'))->toBe('wrapper');
})->with('ai_generation_scenarios');

it('validates the prompt input', function () {
    $user = User::factory()->create();

    $response = actingAs($user, 'sanctum')->postJson('/api/ai/generate-section', [
        'prompt' => '', // Empty prompt
    ]);

    $response->assertStatus(422)
        ->assertJsonValidationErrors(['prompt']);
});
