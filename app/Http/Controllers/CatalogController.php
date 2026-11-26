<?php

namespace App\Http\Controllers;

use App\Models\Concert;
use Illuminate\Http\Request;

class CatalogController extends Controller
{
    public function index(Request $request)
    {
        $request->validate([
            'q'    => ['nullable', 'string', 'max:100'],
            'city' => ['nullable', 'string', 'max:100'],
        ]);

        $published = Concert::where('status', 'published')->where('event_date', '>=', now());

        $concerts = (clone $published)
            ->with('ticketTypes')
            ->when($request->filled('q'), function ($query) use ($request) {
                $keyword = '%' . $request->input('q') . '%';
                $query->where(fn ($w) => $w->where('title', 'like', $keyword)->orWhere('lineup', 'like', $keyword));
            })
            ->when($request->filled('city'), fn ($query) => $query->where('city', $request->input('city')))
            ->orderBy('event_date')
            ->paginate(9)
            ->withQueryString();

        $cities = (clone $published)->distinct()->orderBy('city')->pluck('city');

        return view('customer.catalog', compact('concerts', 'cities'));
    }

    public function show(Concert $concert)
    {
        abort_unless($concert->status === 'published', 404);

        $concert->load('ticketTypes');

        return view('customer.concert-detail', compact('concert'));
    }
}
