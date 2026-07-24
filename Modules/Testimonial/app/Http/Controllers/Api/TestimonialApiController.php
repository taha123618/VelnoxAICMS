<?php

namespace Modules\Testimonial\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\Pagination\CursorPaginator;
use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Http\Request;
use Illuminate\Pagination\AbstractCursorPaginator;
use Illuminate\Pagination\AbstractPaginator;
use Illuminate\Support\Enumerable;
use Modules\Testimonial\Data\TestimonialData;
use Modules\Testimonial\Models\Testimonial;
use Spatie\LaravelData\CursorPaginatedDataCollection;
use Spatie\LaravelData\DataCollection;
use Spatie\LaravelData\PaginatedDataCollection;

class TestimonialApiController extends Controller
{
    public function index(Request $request): DataCollection|PaginatedDataCollection|CursorPaginatedDataCollection|Enumerable|AbstractPaginator|Paginator|AbstractCursorPaginator|CursorPaginator|array
    {
        $testimonials = Testimonial::query()->published()->latest()->get();

        return TestimonialData::collect($testimonials);
    }
}
