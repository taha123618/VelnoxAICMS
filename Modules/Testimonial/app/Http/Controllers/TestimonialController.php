<?php

namespace Modules\Testimonial\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Inertia\Inertia;
use Modules\Testimonial\Actions\CreateTestimonialAction;
use Modules\Testimonial\Actions\DeleteTestimonialAction;
use Modules\Testimonial\Actions\SearchTestimonialsAction;
use Modules\Testimonial\Actions\UpdateTestimonialAction;
use Modules\Testimonial\Data\TestimonialData;
use Modules\Testimonial\Http\Requests\CreateTestimonialRequest;
use Modules\Testimonial\Http\Requests\UpdateTestimonialRequest;
use Modules\Testimonial\Models\Testimonial;

class TestimonialController extends Controller
{
    public function index(Request $request)
    {
        $filters = $request->only(['search', 'sort']);

        $data = resolve(SearchTestimonialsAction::class)->handle($request);

        return Inertia::render('Testimonial::index', [
            'data' => TestimonialData::collect($data),
            'filters' => $filters,
        ]);
    }

    public function create()
    {
        Gate::authorize('create', Testimonial::class);

        return Inertia::render('Testimonial::create');
    }

    public function store(CreateTestimonialRequest $createTestimonialRequest)
    {
        Gate::authorize('create', Testimonial::class);

        resolve(CreateTestimonialAction::class)->handle($createTestimonialRequest);

        return back()->with('success', 'Testimonial created!');
    }

    public function edit(Testimonial $testimonial)
    {
        Gate::authorize('update', $testimonial);

        return Inertia::render('Testimonial::edit', [
            'testimonial' => TestimonialData::fromModel($testimonial),
        ]);
    }

    public function update(UpdateTestimonialRequest $updateTestimonialRequest, Testimonial $testimonial)
    {
        Gate::authorize('update', $testimonial);

        resolve(UpdateTestimonialAction::class)->handle($updateTestimonialRequest, $testimonial);

        return back()->with('success', 'Testimonial updated!');
    }

    public function destroy(Testimonial $testimonial)
    {
        Gate::authorize('delete', $testimonial);

        resolve(DeleteTestimonialAction::class)->handle($testimonial);

        return back()->with('success', 'Testimonial deleted!');
    }
}
