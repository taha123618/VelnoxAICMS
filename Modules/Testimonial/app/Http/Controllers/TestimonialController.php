<?php

namespace Modules\Testimonial\Http\Controllers;

use Inertia\Inertia;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Redirect;
use Modules\Testimonial\Models\Testimonial;
use Modules\Testimonial\Data\TestimonialData;
use Modules\Testimonial\Actions\CreateTestimonialAction;
use Modules\Testimonial\Actions\DeleteTestimonialAction;
use Modules\Testimonial\Actions\UpdateTestimonialAction;
use Modules\Testimonial\Actions\SearchTestimonialsAction;
use Modules\Testimonial\Http\Requests\CreateTestimonialRequest;
use Modules\Testimonial\Http\Requests\UpdateTestimonialRequest;

class TestimonialController extends Controller
{

    public function index(Request $request)
    {
        $filters = $request->only(['search', 'sort']);

        $data = app(SearchTestimonialsAction::class)->handle($request);

        return Inertia::render('Testimonial::index', [
            'data' => TestimonialData::collect($data),
            'filters' => $filters
        ]);
    }


    public function create()
    {
        Gate::authorize('create', Testimonial::class);
        
        return Inertia::render('Testimonial::create');
    }


    public function store(CreateTestimonialRequest $request)
    {
        Gate::authorize('create', Testimonial::class);

        app(CreateTestimonialAction::class)->handle($request);
        
        return Redirect::back()->with('success', 'Testimonial created!');
    }

    public function edit(Testimonial $testimonial)
    {
        Gate::authorize('update', $testimonial);

        return Inertia::render('Testimonial::edit', [
            'testimonial' => TestimonialData::fromModel($testimonial)
        ]);
    }

    public function update(UpdateTestimonialRequest $request, Testimonial $testimonial)
    {
        Gate::authorize('update', $testimonial);

        app(UpdateTestimonialAction::class)->handle($request, $testimonial);

        return Redirect::back()->with('success', 'Testimonial updated!');
    }

    public function destroy(Testimonial $testimonial)
    {
        Gate::authorize('delete', $testimonial);

        app(DeleteTestimonialAction::class)->handle($testimonial);

        return Redirect::back()->with('success', 'Testimonial deleted!');
    }
}
