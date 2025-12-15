<?php

namespace App\Http\Controllers\Home;

use App\Http\Controllers\Controller;
use App\Models\AdvantageImpact;
use \Illuminate\Http\Request;
use App\Models\ContactFaq;
use App\Models\ContactInfo;
use App\Models\HomeStatistic;
use App\Models\ParishFaq;
use App\Models\ParishSuccessStory;
use App\Models\SupportHour;
use App\Models\Testimonial;

class HomeController extends Controller
{
    public function home()
    {
        $statistics = HomeStatistic::first();
        $testimonials = Testimonial::active()->forHome()->get();

        return view('pages.accueil', compact('statistics', 'testimonials'));
    }

    public function fonctionnalites()
    {
        return view('pages.fonctionnalites');
    }

    public function messes()
    {
        return view('pages.messes');
    }

    public function paroisses()
    {
        $successStories = ParishSuccessStory::active()->get();
        $faqs = ParishFaq::active()->ordered()->get();

        return view('pages.paroisses', compact('successStories', 'faqs'));
    }

    public function evenements()
    {
        return view('pages.evenements');
    }

    public function avantages()
    {
        $impacts = AdvantageImpact::active()->ordered()->get();
        $testimonials = Testimonial::active()->forAvantages()->get();

        return view('pages.avantages', compact('impacts', 'testimonials'));
    }

    public function contact()
    {
        $contactInfos = ContactInfo::active()->get();
        $faqs = ContactFaq::active()->ordered()->get();
        $supportHours = SupportHour::active()->get();

        return view('pages.contact', compact('contactInfos', 'faqs', 'supportHours'));
    }

    public function sendMessage(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'subject' => 'required|string|max:255',
            'message' => 'required|string',
        ]);

        \App\Models\ContactMessage::create($validated);

        return response()->json(['message' => 'Votre message a été envoyé avec succès !']);
    }
}
