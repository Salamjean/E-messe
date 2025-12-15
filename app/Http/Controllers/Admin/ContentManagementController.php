<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AdvantageImpact;
use App\Models\ContactFaq;
use App\Models\ContactInfo;
use App\Models\HomeStatistic;
use App\Models\ParishFaq;
use App\Models\ParishSuccessStory;
use App\Models\SupportHour;
use App\Models\Testimonial;
use Illuminate\Http\Request;

class ContentManagementController extends Controller
{
    // ========== HOME STATISTICS ==========
    public function homeStatistics()
    {
        $statistics = HomeStatistic::first();

        return view('admin.content.home-statistics.index', compact('statistics'));
    }

    public function homeStatisticsUpdate(Request $request)
    {
        $request->validate([
            'parishes_count' => 'required|integer',
            'users_count' => 'required|string',
            'availability' => 'required|string',
        ]);

        $statistics = HomeStatistic::first();
        if ($statistics) {
            $statistics->update($request->all());
        } else {
            HomeStatistic::create($request->all());
        }

        return redirect()->route('content.home-statistics')->with('success', 'Statistiques mises à jour avec succès');
    }

    // ========== TESTIMONIALS ==========
    public function testimonialsIndex(Request $request)
    {
        if ($request->ajax()) {
            $testimonials = Testimonial::orderBy('created_at', 'desc')->get();
            return response()->json(['data' => $testimonials]);
        }

        return view('admin.content.testimonials.index');
    }

    public function testimonialsCreate()
    {
        return view('admin.content.testimonials.create');
    }

    public function testimonialsStore(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'location' => 'required|string|max:255',
            'message' => 'required|string',
            'rating' => 'required|integer|min:1|max:5',
            'display_on' => 'required|in:home,avantages,both',
            'is_active' => 'boolean',
        ]);

        Testimonial::create($request->all());

        return redirect()->route('content.testimonials.index')->with('success', 'Témoignage ajouté avec succès');
    }

    public function testimonialsEdit(Testimonial $testimonial)
    {
        return view('admin.content.testimonials.edit', compact('testimonial'));
    }

    public function testimonialsUpdate(Request $request, Testimonial $testimonial)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'location' => 'required|string|max:255',
            'message' => 'required|string',
            'rating' => 'required|integer|min:1|max:5',
            'display_on' => 'required|in:home,avantages,both',
            'is_active' => 'boolean',
        ]);

        $testimonial->update($request->all());

        return redirect()->route('content.testimonials.index')->with('success', 'Témoignage modifié avec succès');
    }

    public function testimonialsDestroy(Testimonial $testimonial)
    {
        $testimonial->delete();

        return redirect()->route('content.testimonials.index')->with('success', 'Témoignage supprimé avec succès');
    }

    // ========== PARISH SUCCESS STORIES ==========
    public function parishSuccessIndex()
    {
        $stories = ParishSuccessStory::orderBy('created_at', 'desc')->get();

        return view('admin.content.parish-success.index', compact('stories'));
    }

    public function parishSuccessCreate()
    {
        return view('admin.content.parish-success.create');
    }

    public function parishSuccessStore(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'location' => 'required|string|max:255',
            'participation_increase' => 'nullable|string|max:255',
            'description' => 'required|string',
            'active_users' => 'required|integer',
            'masses_reserved' => 'required|integer',
            'is_active' => 'boolean',
        ]);

        ParishSuccessStory::create($request->all());

        return redirect()->route('content.parish-success.index')->with('success', 'Succès de paroisse ajouté avec succès');
    }

    public function parishSuccessEdit(ParishSuccessStory $story)
    {
        return view('admin.content.parish-success.edit', compact('story'));
    }

    public function parishSuccessUpdate(Request $request, ParishSuccessStory $story)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'location' => 'required|string|max:255',
            'participation_increase' => 'nullable|string|max:255',
            'description' => 'required|string',
            'active_users' => 'required|integer',
            'masses_reserved' => 'required|integer',
            'is_active' => 'boolean',
        ]);

        $story->update($request->all());

        return redirect()->route('content.parish-success.index')->with('success', 'Succès de paroisse modifié avec succès');
    }

    public function parishSuccessDestroy(ParishSuccessStory $story)
    {
        $story->delete();

        return redirect()->route('content.parish-success.index')->with('success', 'Succès de paroisse supprimé avec succès');
    }

    // ========== PARISH FAQS ==========
    public function parishFaqsIndex()
    {
        $faqs = ParishFaq::ordered()->get();

        return view('admin.content.parish-faqs.index', compact('faqs'));
    }

    public function parishFaqsCreate()
    {
        return view('admin.content.parish-faqs.create');
    }

    public function parishFaqsStore(Request $request)
    {
        $request->validate([
            'question' => 'required|string',
            'answer' => 'required|string',
            'order' => 'required|integer',
            'is_active' => 'boolean',
        ]);

        ParishFaq::create($request->all());

        return redirect()->route('content.parish-faqs.index')->with('success', 'FAQ ajoutée avec succès');
    }

    public function parishFaqsEdit(ParishFaq $faq)
    {
        return view('admin.content.parish-faqs.edit', compact('faq'));
    }

    public function parishFaqsUpdate(Request $request, ParishFaq $faq)
    {
        $request->validate([
            'question' => 'required|string',
            'answer' => 'required|string',
            'order' => 'required|integer',
            'is_active' => 'boolean',
        ]);

        $faq->update($request->all());

        return redirect()->route('content.parish-faqs.index')->with('success', 'FAQ modifiée avec succès');
    }

    public function parishFaqsDestroy(ParishFaq $faq)
    {
        $faq->delete();

        return redirect()->route('content.parish-faqs.index')->with('success', 'FAQ supprimée avec succès');
    }

    // ========== ADVANTAGE IMPACTS ==========
    public function advantageImpactsIndex(Request $request)
    {
        if ($request->ajax()) {
            $impacts = AdvantageImpact::ordered()->get();
            return response()->json(['data' => $impacts]);
        }

        return view('admin.content.advantage-impacts.index');
    }

    public function advantageImpactsCreate()
    {
        return view('admin.content.advantage-impacts.create');
    }

    public function advantageImpactsStore(Request $request)
    {
        $request->validate([
            'value' => 'required|string|max:255',
            'label' => 'required|string|max:255',
            'order' => 'required|integer',
            'is_active' => 'boolean',
        ]);

        AdvantageImpact::create($request->all());

        return redirect()->route('content.advantage-impacts.index')->with('success', 'Impact ajouté avec succès');
    }

    public function advantageImpactsEdit(AdvantageImpact $impact)
    {
        return view('admin.content.advantage-impacts.edit', compact('impact'));
    }

    public function advantageImpactsUpdate(Request $request, AdvantageImpact $impact)
    {
        $request->validate([
            'value' => 'required|string|max:255',
            'label' => 'required|string|max:255',
            'order' => 'required|integer',
            'is_active' => 'boolean',
        ]);

        $impact->update($request->all());

        return redirect()->route('content.advantage-impacts.index')->with('success', 'Impact modifié avec succès');
    }

    public function advantageImpactsDestroy(AdvantageImpact $impact)
    {
        $impact->delete();

        return redirect()->route('content.advantage-impacts.index')->with('success', 'Impact supprimé avec succès');
    }

    // ========== CONTACT INFOS ==========
    public function contactInfosIndex()
    {
        $infos = ContactInfo::all();

        return view('admin.content.contact-infos.index', compact('infos'));
    }

    public function contactInfosCreate()
    {
        return view('admin.content.contact-infos.create');
    }

    public function contactInfosStore(Request $request)
    {
        $request->validate([
            'type' => 'required|in:email,phone,address',
            'icon' => 'required|string|max:255',
            'title' => 'required|string|max:255',
            'value' => 'required|string|max:255',
            'subtitle' => 'nullable|string|max:255',
            'is_active' => 'boolean',
        ]);

        ContactInfo::create($request->all());

        return redirect()->route('content.contact-infos.index')->with('success', 'Information de contact ajoutée avec succès');
    }

    public function contactInfosEdit(ContactInfo $info)
    {
        return view('admin.content.contact-infos.edit', compact('info'));
    }

    public function contactInfosUpdate(Request $request, ContactInfo $info)
    {
        $request->validate([
            'type' => 'required|in:email,phone,address',
            'icon' => 'required|string|max:255',
            'title' => 'required|string|max:255',
            'value' => 'required|string|max:255',
            'subtitle' => 'nullable|string|max:255',
            'is_active' => 'boolean',
        ]);

        $info->update($request->all());

        return redirect()->route('content.contact-infos.index')->with('success', 'Information de contact modifiée avec succès');
    }

    public function contactInfosDestroy(ContactInfo $info)
    {
        $info->delete();

        return redirect()->route('content.contact-infos.index')->with('success', 'Information de contact supprimée avec succès');
    }

    // ========== CONTACT FAQS ==========
    public function contactFaqsIndex()
    {
        $faqs = ContactFaq::ordered()->get();

        return view('admin.content.contact-faqs.index', compact('faqs'));
    }

    public function contactFaqsCreate()
    {
        return view('admin.content.contact-faqs.create');
    }

    public function contactFaqsStore(Request $request)
    {
        $request->validate([
            'question' => 'required|string',
            'answer' => 'required|string',
            'order' => 'required|integer',
            'is_active' => 'boolean',
        ]);

        ContactFaq::create($request->all());

        return redirect()->route('content.contact-faqs.index')->with('success', 'FAQ ajoutée avec succès');
    }

    public function contactFaqsEdit(ContactFaq $faq)
    {
        return view('admin.content.contact-faqs.edit', compact('faq'));
    }

    public function contactFaqsUpdate(Request $request, ContactFaq $faq)
    {
        $request->validate([
            'question' => 'required|string',
            'answer' => 'required|string',
            'order' => 'required|integer',
            'is_active' => 'boolean',
        ]);

        $faq->update($request->all());

        return redirect()->route('content.contact-faqs.index')->with('success', 'FAQ modifiée avec succès');
    }

    public function contactFaqsDestroy(ContactFaq $faq)
    {
        $faq->delete();

        return redirect()->route('content.contact-faqs.index')->with('success', 'FAQ supprimée avec succès');
    }

    // ========== SUPPORT HOURS ==========
    public function supportHoursIndex()
    {
        $hours = SupportHour::all();

        return view('admin.content.support-hours.index', compact('hours'));
    }

    public function supportHoursCreate()
    {
        return view('admin.content.support-hours.create');
    }

    public function supportHoursStore(Request $request)
    {
        $request->validate([
            'type' => 'required|in:email,phone',
            'title' => 'required|string|max:255',
            'schedule' => 'required|string|max:255',
            'note' => 'nullable|string|max:255',
            'is_active' => 'boolean',
        ]);

        SupportHour::create($request->all());

        return redirect()->route('content.support-hours.index')->with('success', 'Horaire de support ajouté avec succès');
    }

    public function supportHoursEdit(SupportHour $hour)
    {
        return view('admin.content.support-hours.edit', compact('hour'));
    }

    public function supportHoursUpdate(Request $request, SupportHour $hour)
    {
        $request->validate([
            'type' => 'required|in:email,phone',
            'title' => 'required|string|max:255',
            'schedule' => 'required|string|max:255',
            'note' => 'nullable|string|max:255',
            'is_active' => 'boolean',
        ]);

        $hour->update($request->all());

        return redirect()->route('content.support-hours.index')->with('success', 'Horaire de support modifié avec succès');
    }

    public function supportHoursDestroy(SupportHour $hour)
    {
        $hour->delete();

        return redirect()->route('content.support-hours.index')->with('success', 'Horaire de support supprimé avec succès');
    }

    // ========== CONTACT MESSAGES ==========
    public function contactMessagesIndex()
    {
        $messages = \App\Models\ContactMessage::orderBy('created_at', 'desc')->get();

        return view('admin.content.contact-messages.index', compact('messages'));
    }

    public function contactMessagesDestroy(\App\Models\ContactMessage $message)
    {
        $message->delete();

        return redirect()->route('content.contact-messages.index')->with('success', 'Message supprimé avec succès');
    }
}
