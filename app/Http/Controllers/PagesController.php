<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Article;
use App\Models\BlogPosts;

class PagesController extends Controller
{
    public function home() {

        //get x most recent posts
        $posts = BlogPosts::orderBy('date', 'desc')->take(4)->get();
    
        //Other recent posts
        $other_posts = Blogposts::orderBy('date', 'desc')->skip(4)->take(6)->get();

        //Recent Guides
        $guides = BlogPosts::orderBy('date', 'desc')
        ->whereHas('blogCategories', function ($query) {
            $query->where('name', 'Guides');
        })
        ->with('blogCategories')
        ->take(6)
        ->get();

        return view('home', compact('posts', 'other_posts', 'guides'));
    }

    public function article(String $slug) {

        // Get the current article id
        $page = Article::with('articles', 'user')->where('slug', $slug)->first();

        // Get all articles related to the article category
        $allPages = $page->articles->article()->select('id', 'slug', 'title')->get();

        // Get 3 most recent blog posts
        $posts = BlogPosts::orderBy('date', 'desc')->take(3)->get();

        return view('article.show', compact('page', 'allPages', 'posts'));
    }      


    public function mortgagePayments(Request $request) {
        $amount = null;
        $term = null;
        $rate = null;
        $interest_only = null;
        $repayment = null;

        $stress_rate = 3;
        $svr = 8.74;
    
        if ($request->has(['amount', 'term', 'rate', 'submit'])) {
            $amount = str_replace(',', '', $request->input('amount'));
            $term = $request->input('term');
            $rate = $request->input('rate');
            $interest_only = $amount * ($rate / 100);
            $repayment = ($amount * ($rate / 100 / 12)) / (1 - pow((1 + ($rate / 100 / 12)), -($term * 12)));
        }
    
        return view('/calculators/mortgage-payments', compact('amount', 'term', 'rate', 'interest_only', 'repayment', 'stress_rate', 'svr'));
    }

    public function calculateStampDuty(Request $request)
    {
        // Define the tax bands for each region
        $regions = $this->getTaxBands();
    
        // If the page is loaded without input, just display the form
        if (!$request->has('amount') && !$request->hasAny(['scot', 'scot_ftb', 'england', 'england_ftb', 'wales', 'wales_add'])) {
            return view('calculators.stamp-duty');
        }
    
        // Validation: Ensure an amount is provided and at least one region is selected
        $validatedData = $request->validate([
            'amount' => 'required|numeric|min:1',
        ], [
            'amount.required' => 'The purchase price is required.',
            'amount.numeric' => 'The purchase price must be a numeric value.',
            'amount.min' => 'The purchase price must be at least £1.',
        ]);
    
        if (!$request->hasAny(['scot', 'scot_ftb', 'england', 'england_ftb', 'wales', 'wales_add'])) {
            return back()->withErrors(['region' => 'You must select a region to calculate stamp duty.'])->withInput();
        }
    
        // Determine which region and type to calculate for
        $regions = $this->getTaxBands();
        $regionKey = $this->getRegionKey($request);
        $amount = $request->input('amount');
    
        // Determine the display name for the selected region and buyer type
        $regionDisplayName = $this->getRegionDisplayName($regionKey);
        $buyerType = $this->getBuyerType($regionKey);
    
        // Get the tax bands for the selected region
        $region = $regions[$regionKey];
    
        // Calculate the tax based on the provided amount and region
        [$totalNormalTax, $totalAdditionalTax] = $this->calculateTax($amount, $region);
    
        // Pass the results, totals, and display names to the view
        return view('calculators.stamp-duty', compact('region', 'totalNormalTax', 'totalAdditionalTax', 'regionDisplayName', 'buyerType'));
    }
    
    private function getRegionKey(Request $request)
    {
        foreach (['scot', 'scot_ftb', 'england', 'england_ftb', 'wales', 'wales_add'] as $region) {
            if ($request->has($region)) {
                return $region;
            }
        }
        return null;
    }
    
    private function getTaxBands()
    {
        return [
            // Scotland - Standard
            'scot' => [
                ['start' => 0, 'end' => 145000, 'normalRate' => 0, 'additionalRate' => 0.06],
                ['start' => 145000, 'end' => 250000, 'normalRate' => 0.02, 'additionalRate' => 0.08],
                ['start' => 250000, 'end' => 325000, 'normalRate' => 0.05, 'additionalRate' => 0.11],
                ['start' => 325000, 'end' => 750000, 'normalRate' => 0.10, 'additionalRate' => 0.16],
                ['start' => 750000, 'end' => PHP_INT_MAX, 'normalRate' => 0.12, 'additionalRate' => 0.18],
            ],
    
            // Scotland - First-Time Buyers
            'scot_ftb' => [
                ['start' => 0, 'end' => 175000, 'normalRate' => 0],
                ['start' => 175000, 'end' => 250000, 'normalRate' => 0.02],
                ['start' => 250000, 'end' => 325000, 'normalRate' => 0.05],
                ['start' => 325000, 'end' => 750000, 'normalRate' => 0.10],
                ['start' => 750000, 'end' => PHP_INT_MAX, 'normalRate' => 0.12],
            ],
    
            // England & Northern Ireland - Standard
            'england' => [
                ['start' => 0, 'end' => 250000, 'normalRate' => 0, 'additionalRate' => 0.03],
                ['start' => 250000, 'end' => 925000, 'normalRate' => 0.05, 'additionalRate' => 0.08],
                ['start' => 925000, 'end' => 1500000, 'normalRate' => 0.10, 'additionalRate' => 0.13],
                ['start' => 1500000, 'end' => PHP_INT_MAX, 'normalRate' => 0.12, 'additionalRate' => 0.15],
            ],
    
            // England & Northern Ireland - First-Time Buyers
            'england_ftb' => [
                ['start' => 0, 'end' => 425000, 'normalRate' => 0, 'additionalRate' => 0.05],
                ['start' => 425000, 'end' => 625000, 'normalRate' => 0.05],
                ['start' => 625000, 'end' => 925000, 'normalRate' => 0.05],
                ['start' => 925000, 'end' => 1500000, 'normalRate' => 0.10],
                ['start' => 1500000, 'end' => PHP_INT_MAX, 'normalRate' => 0.12],
            ],
    
            // Wales - Standard
            'wales' => [
                ['start' => 0, 'end' => 225000, 'normalRate' => 0],
                ['start' => 225000, 'end' => 400000, 'normalRate' => 0.06],
                ['start' => 400000, 'end' => 750000, 'normalRate' => 0.075],
                ['start' => 750000, 'end' => 1500000, 'normalRate' => 0.10],
                ['start' => 1500000, 'end' => PHP_INT_MAX, 'normalRate' => 0.12],
            ],
    
            // Wales - Additional Property
            'wales_add' => [
                ['start' => 0, 'end' => 180000, 'normalRate' => 0, 'additionalRate' => 0.04],
                ['start' => 180000, 'end' => 250000, 'normalRate' => 0, 'additionalRate' => 0.075],
                ['start' => 250000, 'end' => 400000, 'normalRate' => 0, 'additionalRate' => 0.09],
                ['start' => 400000, 'end' => 750000, 'normalRate' => 0, 'additionalRate' => 0.115],
                ['start' => 750000, 'end' => 1500000, 'normalRate' => 0, 'additionalRate' => 0.14],
                ['start' => 1500000, 'end' => PHP_INT_MAX, 'normalRate' => 0, 'additionalRate' => 0.16],
            ],
        ];
    }

    private function getRegionDisplayName($regionKey)
    {
        $regionNames = [
            'scot' => 'Scotland',
            'scot_ftb' => 'Scotland',
            'england' => 'England & Northern Ireland',
            'england_ftb' => 'England & Northern Ireland',
            'wales' => 'Wales',
            'wales_add' => 'Wales',
        ];
    
        return $regionNames[$regionKey] ?? 'Unknown Region';
    }
    
    private function getBuyerType($regionKey)
    {
        $buyerTypes = [
            'scot' => 'Standard Buyer',
            'scot_ftb' => 'First-Time Buyer',
            'england' => 'Standard Buyer',
            'england_ftb' => 'First-Time Buyer',
            'wales' => 'Standard Buyer',
            'wales_add' => 'Additional Property',
        ];
    
        return $buyerTypes[$regionKey] ?? 'Unknown Buyer Type';
    }
    
    private function calculateTax($amount, &$bands)
    {
        $totalNormalTax = 0;
        $totalAdditionalTax = 0;
    
        foreach ($bands as &$band) {
            // Ensure the keys exist to avoid undefined array key errors
            $band['normalTax'] = 0;
            $band['additionalTax'] = 0;
    
            if ($amount <= $band['start']) continue;
    
            $taxableAmount = min($amount, $band['end']) - $band['start'];
    
            // Calculate normal tax and additional tax for this band
            $band['normalTax'] = $taxableAmount * ($band['normalRate'] ?? 0);
            $band['additionalTax'] = isset($band['additionalRate']) ? $taxableAmount * $band['additionalRate'] : 0;
    
            // Sum up the total taxes
            $totalNormalTax += $band['normalTax'];
            $totalAdditionalTax += $band['additionalTax'];
        }
    
        return [$totalNormalTax, $totalAdditionalTax];
    }

    public function contact() {

        return view('contact');
    }

    public function about() {

        return view('about');
    }
}
