@php
    /** @var \App\Models\BlogPost $item */
@endphp
<div class="row justify-content-center">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                @if($item->is_published)
                    Published
                @else
                    Draft
                @endif
            </div>
            <div class="card-body">
                <div class="card-title"></div>
                <div class="card-subtitle mb-2 text-muted"></div>
                <ul class="nav nav-tabs" role="tablist">
                    <li class="nav-item">
                        <a href="#maindata" class="nav-link active" data-toggle="tab" role="tab">Main data</a>
                    </li>
                    <li class="nav-item">
                        <a href="#adddata" class="nav-link" data-toggle="tab" role="tab">Additional data</a>
                    </li>
                </ul>
                <br>
                <div class="tab-content">
                    <div class="tab-pane active" id="maindata" role="tabpanel">
                        <div class="form-group">
                            <label for="title">Title</label>
                            <input type="text" name="title" value="{{ $item->title }}"
                                   id="title"
                                   class="form-control"
                                   minlength="3"
                                   required>
                        </div>
                        <div class="form-group">
                            <lable for="content_raw">Article</lable>
                            <textarea name="content_raw"
                                      id="content_raw"
                                      class="form-control"
                                      rows="20">{{ old('content_raw', $item->content_raw) }}</textarea>
                        </div>
                    </div>
                    <div class="tab-pane" id="adddata" role="tabpanel">
                        <div class="form-group">
                            <lable for="category_id">Category</lable>
                            <select name="category_id"
                                    id="category_id"
                                    class="form-control"
                                    placeholder="Choose category
                                        required">
                                @foreach($categoryList as $categoryOption)
                                    <option value="{{ $categoryOption->id }}"
                                            @if($categoryOption->id == $item->category_id) selected @endif>
                                        {{ $categoryOption->id_title }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <lable for="slug">Slug</lable>
                            <input type="text" name="slug" value="{{ $item->slug }}"
                                   id="slug"
                                   class="form-control">
                        </div>
                        <div class="form-group">
                            <lable for="excerpt">Excerpt</lable>
                            <textarea name="excerpt"
                                      id="excerpt"
                                      class="form-control"
                                      rows="3">{{ old('excerpt', $item->excerpt) }}</textarea>
                        </div>
                        <div class="form-check">
                            <input name="is_published"
                                type="hidden"
                                value="0">
                            <input name="is_published"
                                   type="checkbox"
                                   class="form-check-input"
                                   value="1"
                                   @if($item->is_published)
                                       checked="checked"
                                   @endif
                            >
                            <label for="is_published" class="form-check-label">Published</label>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
