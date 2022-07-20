<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Traits\UrlTrait;
use Illuminate\Support\Facades\Http;
use Validator;

class PublicController extends Controller
{
    use UrlTrait;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $categoryData = Http::get($this->url_dynamic() . 'category');
        $categoryData = json_decode($categoryData->body());
        $categoryData = $categoryData->data;

        $category = $request->input('category');
        $category = str_replace("-"," ", $category);
        $page = $request->input('page');
        $page = $page > 0 ? $page - 1 : $page;
        $page = $page == null ? 0 : $page;
        $currentPage = $page + 1;

        $response = Http::get($this->url_dynamic() . 'menu?page=' . $page . '&category=' . $category);
        $response = json_decode($response->body());
        $totalPages = $response->totalPages;
        $menu = $response->data;

        $previousPage = $currentPage == 1 ? 1 : $currentPage - 1;
        $previousPageURL = "/?page=" . $previousPage;
        $nextPage = $currentPage == $totalPages ? $totalPages : $currentPage + 1;
        $nextPageURL = "/?page=" . $nextPage;

        $categoryURL = "";
        if($category) {
            $categoryURL = "&category=" . $category;
            $previousPageURL = "/?page=" . $previousPage . $categoryURL;
            $nextPageURL = "/?page=" . $nextPage . $categoryURL;
        }

        $pagination = (object) [
            'previousPageURL' => $previousPageURL,
            'nextPageURL' => $nextPageURL,
            'totalPages' => $totalPages,
            'currentPage' => $currentPage,
            'categoryURL' => $categoryURL
        ];

        if($response->success) {
            return view('pages.index', compact('pagination', 'menu', 'categoryData'));
        } else {
            return redirect()->back()->with('error', $response->message);
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($slug)
    {
        $menuName = str_replace("-"," ", $slug);
        $response = Http::get($this->url_dynamic() . 'menu?name=' . $menuName);
        $response = json_decode($response->body());
        $menu = $response->data[0];
        
        return view('pages.detail', compact('menu'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }


    public function cart()
    {
        return view('pages.cart');
    }
    
    public function testing()
    {
        return view('pages.testing');
    }
}
