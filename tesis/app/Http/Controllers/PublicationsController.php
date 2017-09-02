<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Publication;
use Illuminate\Http\Request;
use Session;
use DB;

class PublicationsController extends Controller
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
        	if (!isset($_GET['admin'])) {
        		$publications = Publication::where($searchBy, 'LIKE', "%$keyword%")
				->where('publish',1)->paginate($perPage);
        	} else {
        		$publications = Publication::where($searchBy, 'LIKE', "%$keyword%")
				->paginate($perPage);
        	}
            
        } else {
            if (!isset($_GET['admin'])) {
        		$publications = Publication::where('publish',1)->paginate($perPage);
        	} else {
        		$publications = Publication::paginate($perPage);
        	}
        }

        $n = 1;

        if (isset($_GET['page'])) {
            $n = 20*($_GET['page'] - 1) + 1;
        }


        return view('publications.index', compact('publications','keyword','searchBy','n'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('publications.create');
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
			'file' => 'required',
			'lampiran' => 'required'
		]);
        $requestData = $request->all();

        if ($request->hasFile('cover')) {
            $file = $request['cover'];
            $uploadPath = public_path('uploads/publications');

            $extension = $file->getClientOriginalExtension();
            $fileName = $request['email'] . '_' . 'cover_publikasi' . '.' . $extension;

            $file->move($uploadPath, $fileName);
            $requestData['cover'] = $fileName;
        }


        if ($request->hasFile('file')) {
            $file = $request['file'];
            $uploadPath = public_path('uploads/publications');

            $extension = $file->getClientOriginalExtension();
            $fileName = $request['email'] . '_' . 'file_publikasi' . '.' . $extension;

            $file->move($uploadPath, $fileName);
            $requestData['file'] = $fileName;       
        }

        if ($request->hasFile('lampiran')) {
            $file = $request['lampiran'];
            $uploadPath = public_path('uploads/publications');

            $extension = $file->getClientOriginalExtension();
            $fileName = $request['email'] . '_' . 'lampiran_publikasi' . '.' . $extension;

            $file->move($uploadPath, $fileName);
            $requestData['lampiran'] = $fileName;
        }

        Publication::create($requestData);

        Session::flash('flash_message', 'Publication successfully submitted!');

        return redirect('publications');
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
        $publication = Publication::findOrFail($id);

        return view('publications.show', compact('publication'));
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
        $publication = Publication::findOrFail($id);

        return view('publications.edit', compact('publication'));
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
            $uploadPath = public_path('uploads/publications');

            $extension = $file->getClientOriginalExtension();
            $fileName = $request['email'] . '_' . 'cover_publikasi' . '.' . $extension;

            $file->move($uploadPath, $fileName);
            $requestData['cover'] = $fileName;
        }


        if ($request->hasFile('file')) {
            $file = $request['file'];
            $uploadPath = public_path('uploads/publications');

            $extension = $file->getClientOriginalExtension();
            $fileName = $request['email'] . '_' . 'file_publikasi' . '.' . $extension;

            $file->move($uploadPath, $fileName);
            $requestData['file'] = $fileName;       
        }

        if ($request->hasFile('lampiran')) {
            $file = $request['lampiran'];
            $uploadPath = public_path('uploads/publications');

            $extension = $file->getClientOriginalExtension();
            $fileName = $request['email'] . '_' . 'lampiran_publikasi' . '.' . $extension;

            $file->move($uploadPath, $fileName);
            $requestData['lampiran'] = $fileName;
        }

        $publication = Publication::findOrFail($id);
        $publication->update($requestData);

        Session::flash('flash_message', 'This Publication updated!');

        return redirect('publications');
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
        Publication::destroy($id);

        Session::flash('flash_message', 'Publication deleted!');

        return redirect('publications');
    }

    public function publish(Request $request)
    {
        //toggle publish

        //$publication = Publication::findOrFail($id);

       
    }
}
