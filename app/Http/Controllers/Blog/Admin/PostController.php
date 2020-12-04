<?php

namespace App\Http\Controllers\Blog\Admin;

use App\Jobs\BlogPostAfterCreateJob;
use App\Jobs\BlogPostAfterDeleteJob;
use App\Models\BlogPost;
use App\Repositories\BlogPostRepository;
use App\Repositories\BlogCategoryRepository;
use App\Http\Requests\BlogPostCreateRequest;
use App\Http\Requests\BlogPostUpdateRequest;

/**
 * Class PostController
 * Управление статьями блога
 *
 * @package App\Http\Controllers\Blog\Admin
 */
class PostController extends BaseController
{
    /**
     * @var BlogPostRepository
     */
    private $blogPostRepository;

    /**
     * @var BlogCategoryRepository
     */
    private $blogCategoryRepository;

    /**
     * PostController constructor.
     */
    public function __construct()
    {
        parent::__construct();

        $this->blogPostRepository = app(BlogPostRepository::class);
        $this->blogCategoryRepository = app(BlogCategoryRepository::class);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $paginator = $this->blogPostRepository->getAllWithPaginate(25);
        return view('blog.admin.posts.index',
            compact('paginator'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $item = BlogPost::make();
        $categoryList = $this->blogCategoryRepository->getForComboBox();

        return view('blog.admin.posts.edit',
            compact('item', 'categoryList'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  BlogPostCreateRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(BlogPostCreateRequest $request)
    {
        $data = $request->input();
        $item = BlogPost::create($data);

        if ( $item ) {

            $job = new BlogPostAfterCreateJob($item);
            $this->dispatch($job);

            return redirect()->route('blog.admin.posts.edit', [$item->id])
                ->with(['success' => 'Saved successfully']);
        } else {
            return back()->withErrors(['msg' => 'Save error'])
                ->withInput();
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {

        $item = $this->blogPostRepository->getEdit($id);
        if ( empty( $item ) ){
            abort(404);
        }
        $categoryList = $this->blogCategoryRepository->getForComboBox();

        return view('blog.admin.posts.edit',
            compact('item', 'categoryList'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  BlogPostUpdateRequest  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(BlogPostUpdateRequest $request, $id)
    {
        $item = $this->blogPostRepository->getEdit($id);

        if ( empty( $item ) ){
            return back()
                ->withErrors(['msg' => 'Post id=[{$id}] is not defined'])
                ->withInput();
        }
        $data = $request->all();

//         ушло в обсервер
//        if ( empty( $data['slug'] ) ) {
//            $data['slug'] = \Str::slug($data['title']);
//        }
//        if ( empty( $item->published_at ) && $data['is_published'] ) {
//            $data['published_at'] = Carbon::now();
//        }
        $result = $item->update($data);
        if ( $result ) {
            return redirect()
                ->route('blog.admin.posts.edit', $item->id)
                ->with(['success' => 'Saved successfully']);
        } else {
            return back()
                ->withErrors(['msg' => 'Save error'])
                ->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //софт-удаление, в бд остается
        $result = BlogPost::destroy($id);

        //полное удаление из бд
//        $result = BlogPost::find($id)->forceDelete();

        if ( $result ) {

            BlogPostAfterDeleteJob::dispatch($id)->delay(20); // с отстрочкой 20сек

            // > Варианты запуска:

            //BlogPostAfterDeleteJob::dispatchNow($id);

            //dispatch(new BlogPostAfterDeleteJob($id));
            //dispatch_now(new BlogPostAfterDeleteJob($id));

            //$this->>dispatch(BlogPostAfterDeleteJob($id));
            //$this->>dispatch_now(BlogPostAfterDeleteJob($id));

            //$job = new BlogPostAfterDeleteJob($id);
            //$job->handle();

            // < Варианты запуска

            return redirect()
                ->route('blog.admin.posts.index')
                ->with(['success' => "Post id = [$id] was deleted", 'restore' => $id]);
        } else{
            return back()->withErrors(['msg' => 'Delete error']);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function restore($id)
    {
        $item = $this->blogPostRepository->getTreashedPost($id);
        $result = $item->restore();

        if ( $result ) {
            return back()
                ->with(['success' => "Post id = [$id] was successfully restored"]);
        } else{
            return back()->withErrors(['msg' => 'Delete restore post']);
        }
    }
}
