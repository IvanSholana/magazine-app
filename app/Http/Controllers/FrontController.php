<?php

namespace App\Http\Controllers;

use App\Models\ArticleNews;
use App\Models\Author;
use App\Models\BannerAds;
use App\Models\Category;
use Illuminate\Http\Request;

class FrontController extends Controller
{
    //
    public function index(){
        $categories = Category::all();

        $articles = ArticleNews::with(['category'])
        ->where('is_featured','not_featured')
        ->latest()
        ->take(3)
        ->get();

        $featured_articles = ArticleNews::with(['category'])
        ->where('is_featured','featured')
        ->inRandomOrder()
        ->take(3)
        ->get();

        $bannerads = BannerAds::where('is_active', 'active')
                      ->where('type', 'banner')
                      ->first();

        $entertreiment_featured = ArticleNews::whereHas('category', function ($query) {
        $query->where('name', 'Entertreiment');
        })
        ->where('is_featured', 'featured')
        ->latest()
        ->first(); 

        // return view(dd($entertreiment_featured));
        $entertreiment_not_featured = ArticleNews::whereHas('category', function ($query) {
        $query->where('name', 'Entertreiment');
        })
        ->where('is_featured', 'not_featured')
        ->latest() // Mengurutkan berdasarkan tanggal terbaru
        ->take(6)
        ->get();


        $business_not_featured = ArticleNews::whereHas('category', function ($query) {
            $query->where('name', 'Business');
            })
            ->where('is_featured', 'not_featured')
            ->latest() // Mengurutkan berdasarkan tanggal terbaru
            ->take(6)
            ->get();

        $business_featured = ArticleNews::whereHas('category', function ($query) {
            $query->where('name', 'Business');
            })
            ->where('is_featured', 'featured')
            ->latest() // Mengurutkan berdasarkan tanggal terbaru
            ->first();

        $otomotif_featured = ArticleNews::whereHas('category', function ($query) {
            $query->where('name', 'Otomotif');
            })
            ->where('is_featured', 'featured')
            ->latest() // Mengurutkan berdasarkan tanggal terbaru
            ->first();
        
        // return view(dd($otomotif_featured));
        $otomotif_not_featured = ArticleNews::whereHas('category', function ($query) {
            $query->where('name', 'Otomotif');
            })
            ->where('is_featured', 'not_featured')
            ->latest() // Mengurutkan berdasarkan tanggal terbaru
            ->take(6)
            ->get();
            
                    
        $authors = Author::all();

        return view("front.index",compact('categories','articles','authors','featured_articles','bannerads','entertreiment_featured','entertreiment_not_featured','business_featured','business_not_featured','otomotif_featured','otomotif_not_featured'));
    }

    public function category(Category $category) {
        $categories = Category::all();
        $articles = ArticleNews::whereHas('category', function ($query) use ($category) {
            $query->where('name', $category->name);
        })->get(); // Ensure to execute the query
        
        $banner = BannerAds::where('type', 'banner')
                   ->where('is_active', 'active')
                   ->inRandomOrder() // Mengacak urutan data
                   ->first(); // Mengambil satu data secara acak

        return view("front.category", compact("category", "categories", "articles",'banner'));
    }
    

    public function author(Author $author) {
        
        $category = Category::All();
        $articles = ArticleNews::whereHas('author', function ($query) use ($author) {  
            $query->where('name', $author->name);
        })->get();

        $current_author = Author::where('name',$author->name)->first();

        $bannerads = BannerAds::where('type','banner')->where('is_active','active')->inRandomOrder()->first();

        // return view(dd($articles));

        return view('front.author',compact('category','articles','current_author','bannerads'));
    }

    public function search(Request $request) {
        $request->validate([
            'keyword' => ['required','string','max:255'],
        ]);

        $categories = Category::all();

        $keyword = $request->keyword;

        $articles = ArticleNews::with(['category','author'])
        ->where('name','like','%'. $keyword .'%')->paginate(6);

        return view('front.search', compact('articles','keyword','categories'));
    }

    public function details(ArticleNews $article_news) {
        // Fetch all categories
        $categories = Category::all();
    
        // Fetch the related articles with their category and author
        $articles_news = ArticleNews::with('category')
            ->where('name', $article_news->name)
            ->get();
    
        // Fetch a random square banner ad that is active
        $banneradssquare = BannerAds::where('is_active', 'active')
            ->where('type', 'square')
            ->inRandomOrder()
            ->first();
    
        // Fetch a random banner ad that is active
        $bannerads = BannerAds::where('is_active', 'active')
            ->where('type', 'banner')
            ->inRandomOrder()
            ->first();
    
        // Fetch another random article by the same author excluding the current article
        $otherArticle = ArticleNews::whereHas('author', function ($query) use ($article_news) {
            $query->where('name', $article_news->author->name);
        })
        ->where('id', '!=', $article_news->id)
        ->inRandomOrder()
        ->get();

        $allArticles = ArticleNews::take(3)->get();
    
        // Return the view with the compacted variables
        // return view(dd($otherArticle));
        return view('front.details', compact('article_news', 'categories', 'bannerads', 'banneradssquare', 'otherArticle','allArticles'));
    }    
}
