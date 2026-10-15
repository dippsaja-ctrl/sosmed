<?php

namespace App\Http\Controllers;

class LegalController extends Controller
{
    public function show(string $page)
    {
        abort_unless(in_array($page, ['privacy', 'terms']), 404);
        return view('legal.show', ['page' => $page]);
    }
}
