<?php

namespace App\Http\Controllers\Blog\Admin;

use App\Http\Controllers\Blog\Admin\BaseController;
use App\Http\Requests\BlogCategoryUpdateRequest;
use App\Http\Requests\BlogCategoryCreateRequest;
use App\Models\BlogCategory;
use App\Repositories\BlogCategoryRepository;

/**
 * Class CategoryController
 *
 * Управление категориями блога
 *
 * @package App\Http\Controllers\Blog\Admin
 */
class CategoryController extends BaseController
{
    /**
     * @var BlogCategoryRepository
     */
    private $blogCategoryRepository;

    public function __construct()
    {
        parent::__construct();

        $this->blogCategoryRepository = app(BlogCategoryRepository::class);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
//        $paginator = BlogCategory::paginate(15);
        $paginator = $this->blogCategoryRepository->getAllWithPaginate(15);
        return view('blog.admin.categories.index',
            compact('paginator'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
		$item = BlogCategory::make();
//		$categoryList = BlogCategory::all();
		$categoryList = $this->blogCategoryRepository->getForComboBox();

        return view('blog.admin.categories.edit',
            compact('item', 'categoryList'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(BlogCategoryCreateRequest $request)
    {
        $data = $request->input();
//        ушло в обсервер
//        if (empty($data['slug'])) {
//            $data['slug'] = \Str::slug($data['title']);
//        }

        // создаст объект, но не добавит в бд - 1 способ
//        $item = new BlogCategory($data);
//		$item->save(); // добавит в бд; в переменной будет true/false

        // создаст объект и добавит в бд - 2 способ
        $item = BlogCategory::create($data); // в переменной будет объект/false

		if($item){
		    return redirect()->route('blog.admin.categories.edit', [$item->id])
                ->with(['success' => 'Saved successfully']);
        }else{
            return back()
                ->withErrors(['msg' => 'Save error'])
                ->withInput();
    }
    }

//	/**
//	 * Display the specified resource.
//	 *
//	 * @param  int  $id
//	 * @return \Illuminate\Http\Response
//	 */
//	public function show($id)
//	{
//		dd(__METHOD__);
//	}
    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     *
     * @return \Illuminate\Http\Response
     */
//    public function edit($id)
//    {
////		$item = BlogCategory::find($id);
////		$item = BlogCategory::where('id', '<=>', $id)->first();
////		$item = BlogCategory::where('id', $id)->get(); // get collection
//		$item = BlogCategory::findOrFail($id);
//		$categoryList = BlogCategory::all();
//
//		return view('blog.admin.categories.edit',compact('item', 'categoryList'));
//    }
    public function edit($id)
    {

        $item = $this->blogCategoryRepository->getEdit($id);

        $v['title_before'] = $item->title;
        $item->title = 'Test title';

//        $v['title_after'] = $item->title;
//        $v['getAttribute'] = $item->getAttribute('title');
//        $v['attributesToArray'] = $item->attributesToArray();
////        $v['attributes'] = $item->attributes('title');
//        $v['getAttributeValue'] = $item->getAttributeValue('title');
//        $v['getMutatedAttributes'] = $item->getMutatedAttributes();
//        $v['hasGetMutator_for_title'] = $item->hasGetMutator('title');
//        $v['toArray'] = $item->toArray();
//
//        dd($v, $item);

        if(empty($item)){
            abort(404);
        }
        $categoryList = $this->blogCategoryRepository->getForComboBox();

        return view('blog.admin.categories.edit',
            compact('item', 'categoryList'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  BlogCategoryUpdateRequest  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(BlogCategoryUpdateRequest $request, $id)
    {
//        $rules = [
//            'title'       => 'required|min:5|max:200',
//            'slug'        => 'max:200',
//            'description' => 'string|max:500|min:3',
//            'parent_id'   => 'required|integer|exists:blog_categories,id',
//        ];
//        $validateedData = $this->validate($request, $rules); // 1вариант
//        $validateedData = $request->validate($rules); // 2вариант
//        $validator = \Validator::make($request->all(), $rules); // 3вариант
//        $validateedData[] = $validator->passes(); // выполнит проверку и вернет true\false
//        $validateedData[] = $validator->validate(); // редирект
//        $validateedData[] = $validator->valid(); // получение валидных данных
//        $validateedData[] = $validator->failed(); // данные в которых ошибка
//        $validateedData[] = $validator->errors(); // ошибки в данных
//        $validateedData[] = $validator->fails(); // true\false
//        dd($validateedData);

//		$item = BlogCategory::find($id);
		$item = $this->blogCategoryRepository->getEdit($id);

		if(empty($item)){
			return back()
				->withErrors(['msg' => "Post id=[{$id}] is not defined"])
				->withInput();
		}
		$data = $request->all();
//		ушло в обсервер
//        if (empty($data['slug'])) {
//            $data['slug'] = \Str::slug($data['title']);
//        }
//		$result = $item->fill($data)->save();
		$result = $item->update($data);
		if($result){
			return redirect()
				->route('blog.admin.categories.edit', $item->id)
				->with(['success' => 'Saved successfully']);
		}else{
			return back()
				->withErrors(['msg' => 'Save error'])
				->withInput();
		}
    }
//	/**
//	 * Remove the specified resource from storage.
//	 *
//	 * @param  int  $id
//	 * @return \Illuminate\Http\Response
//	 */
//	public function destroy($id)
//	{
//		dd(__METHOD__);
//	}
}
