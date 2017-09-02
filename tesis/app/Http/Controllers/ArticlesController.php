<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Article;
use Illuminate\Http\Request;
use Session;
use DB;

class ArticlesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        $keyword = $request->get('search');
        $searchBy = $request->get('searchBy');
        $perPage = 20;

        if (!empty($keyword)) {
            $articles = Article::where($searchBy, 'LIKE', "%$keyword%")
				->paginate($perPage);
        } else {
            $articles = Article::paginate($perPage);
        }

        $n = 1;

        if (isset($_GET['page'])) {
            $n = 20*($_GET['page'] - 1) + 1;
        }


        return view('articles.index', compact('articles','keyword','searchBy','n'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('articles.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function store(Request $request)
    {
        $this->validate($request, [
			'title' => 'required',
			'author' => 'required',
			'supervisor' => 'required',
			'email' => 'required',
			'abstract_en' => 'required',
			'abstract_id' => 'required',
			'keyword' => 'required',
			'cover' => 'required',
			'bab_1' => 'required',
			'bab_2' => 'required',
			'bab_3' => 'required',
			'bab_4' => 'required',
			'bab_5' => 'required',
			'bab_6' => 'required',
			'lampiran' => 'required'
		]);
        $requestData = $request->all();

        if ($request->hasFile('cover')) {
            $file = $request['cover'];
            $uploadPath = public_path('uploads');

            $extension = $file->getClientOriginalExtension();
            $fileName = $request['email'] . '_' . 'cover' . '.' . $extension;

            $file->move($uploadPath, $fileName);
            $requestData['cover'] = $fileName;
        }


        if ($request->hasFile('bab_1')) {
            $file = $request['bab_1'];
            $uploadPath = public_path('uploads');

            $extension = $file->getClientOriginalExtension();
            $fileName = $request['email'] . '_' . 'bab_1' . '.' . $extension;

            $file->move($uploadPath, $fileName);
            $requestData['bab_1'] = $fileName;       
        }

        if ($request->hasFile('bab_2')) {
            $file = $request['bab_2'];
            $uploadPath = public_path('uploads');

            $extension = $file->getClientOriginalExtension();
            $fileName = $request['email'] . '_' . 'bab_2' . '.' . $extension;

            $file->move($uploadPath, $fileName);
            $requestData['bab_2'] = $fileName;        
        }

        if ($request->hasFile('bab_3')) {
            $file = $request['bab_3'];
            $uploadPath = public_path('uploads');

            $extension = $file->getClientOriginalExtension();
            $fileName = $request['email'] . '_' . 'bab_3' . '.' . $extension;

            $file->move($uploadPath, $fileName);
            $requestData['bab_3'] = $fileName;
        }


        if ($request->hasFile('bab_4')) {
            $file = $request['bab_4'];
            $uploadPath = public_path('uploads');

            $extension = $file->getClientOriginalExtension();
            $fileName = $request['email'] . '_' . 'bab_4' . '.' . $extension;

            $file->move($uploadPath, $fileName);
            $requestData['bab_4'] = $fileName;
        }


        if ($request->hasFile('bab_5')) {
            $file = $request['bab_5'];
            $uploadPath = public_path('uploads');

            $extension = $file->getClientOriginalExtension();
            $fileName = $request['email'] . '_' . 'bab_5' . '.' . $extension;

            $file->move($uploadPath, $fileName);
            $requestData['bab_5'] = $fileName;
        }


        if ($request->hasFile('bab_6')) {
            $file = $request['bab_6'];
            $uploadPath = public_path('uploads');

            $extension = $file->getClientOriginalExtension();
            $fileName = $request['email'] . '_' . 'bab_6' . '.' . $extension;

            $file->move($uploadPath, $fileName);
            $requestData['bab_6'] = $fileName;       
        }


        if ($request->hasFile('lampiran')) {
            $file = $request['lampiran'];
            $uploadPath = public_path('uploads');

            $extension = $file->getClientOriginalExtension();
            $fileName = $request['email'] . '_' . 'lampiran' . '.' . $extension;

            $file->move($uploadPath, $fileName);
            $requestData['lampiran'] = $fileName;
        }

        Article::create($requestData);

        Session::flash('flash_message', 'Thesis successfully submitted!');

        return redirect('articles');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     *
     * @return \Illuminate\View\View
     */
    public function show($id)
    {
        $article = Article::findOrFail($id);

        return view('articles.show', compact('article'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     *
     * @return \Illuminate\View\View
     */
    public function edit($id)
    {
        $article = Article::findOrFail($id);

        return view('articles.edit', compact('article'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function update($id, Request $request)
    {

        $this->validate($request, [
			'title' => 'required',
			'author' => 'required',
			'supervisor' => 'required',
			'email' => 'required',
			'abstract_en' => 'required',
			'abstract_id' => 'required',
			'keyword' => 'required',
		]);
        $requestData = $request->all();
        

        if ($request->hasFile('cover')) {
            $file = $request['cover'];
            $uploadPath = public_path('uploads');

            $extension = $file->getClientOriginalExtension();
            $fileName = $request['email'] . '_' . 'cover' . '.' . $extension;

            $file->move($uploadPath, $fileName);
            $requestData['cover'] = $fileName;
        }


        if ($request->hasFile('bab_1')) {
            $file = $request['bab_1'];
            $uploadPath = public_path('uploads');

            $extension = $file->getClientOriginalExtension();
            $fileName = $request['email'] . '_' . 'bab_1' . '.' . $extension;

            $file->move($uploadPath, $fileName);
            $requestData['bab_1'] = $fileName;       
        }

        if ($request->hasFile('bab_2')) {
            $file = $request['bab_2'];
            $uploadPath = public_path('uploads');

            $extension = $file->getClientOriginalExtension();
            $fileName = $request['email'] . '_' . 'bab_2' . '.' . $extension;

            $file->move($uploadPath, $fileName);
            $requestData['bab_2'] = $fileName;        
        }

        if ($request->hasFile('bab_3')) {
            $file = $request['bab_3'];
            $uploadPath = public_path('uploads');

            $extension = $file->getClientOriginalExtension();
            $fileName = $request['email'] . '_' . 'bab_3' . '.' . $extension;

            $file->move($uploadPath, $fileName);
            $requestData['bab_3'] = $fileName;
        }


        if ($request->hasFile('bab_4')) {
            $file = $request['bab_4'];
            $uploadPath = public_path('uploads');

            $extension = $file->getClientOriginalExtension();
            $fileName = $request['email'] . '_' . 'bab_4' . '.' . $extension;

            $file->move($uploadPath, $fileName);
            $requestData['bab_4'] = $fileName;
        }


        if ($request->hasFile('bab_5')) {
            $file = $request['bab_5'];
            $uploadPath = public_path('uploads');

            $extension = $file->getClientOriginalExtension();
            $fileName = $request['email'] . '_' . 'bab_5' . '.' . $extension;

            $file->move($uploadPath, $fileName);
            $requestData['bab_5'] = $fileName;
        }


        if ($request->hasFile('bab_6')) {
            $file = $request['bab_6'];
            $uploadPath = public_path('uploads');

            $extension = $file->getClientOriginalExtension();
            $fileName = $request['email'] . '_' . 'bab_6' . '.' . $extension;

            $file->move($uploadPath, $fileName);
            $requestData['bab_6'] = $fileName;       
        }


        if ($request->hasFile('lampiran')) {
            $file = $request['lampiran'];
            $uploadPath = public_path('uploads');

            $extension = $file->getClientOriginalExtension();
            $fileName = $request['email'] . '_' . 'lampiran' . '.' . $extension;

            $file->move($uploadPath, $fileName);
            $requestData['lampiran'] = $fileName;
        }

        $article = Article::findOrFail($id);
        $article->update($requestData);

        Session::flash('flash_message', 'This Thesis updated!');

        return redirect('articles');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function destroy($id)
    {
        Article::destroy($id);

        Session::flash('flash_message', 'Thesis deleted!');

        return redirect('articles');
    }

    public function publish(Request $request)
    {
        //toggle publish

        //$article = Article::findOrFail($id);

       
    }
}
