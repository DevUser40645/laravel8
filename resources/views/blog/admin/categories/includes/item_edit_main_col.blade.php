@php
    /** @var \App\Models\BlogCategory $item */
    /** @var \App\Models\BlogCategory $categoryList */
@endphp
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <div class="card-title"></div>
                    <ul class="nav nav-tabs" role="tablist">
                        <li class="nav-item">
                            <a href="#maindata" role="tab" data-toggle="tab" class="nav-link active">Main data</a>
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
                                <lable for="slug">Slug</lable>
                                <input type="text" name="slug" value="{{ $item->slug }}"
                                       id="slug"
                                       class="form-control">
                            </div>
                            <div class="form-group">
                                <lable for="parent_id">Parent category</lable>
                                <select name="parent_id"
                                        id="parent_id"
                                        class="form-control"
                                        placeholder="Choose category
                                        required">
                                    @foreach($categoryList as $categoryOption)
                                        <option value="{{ $categoryOption->id }}"
                                                @if($categoryOption->id == $item->parent_id) selected @endif>
                                            {{--{{ $category->id }}. {{ $category->title }}--}}
                                            {{ $categoryOption->id_title }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <lable for="description">Description</lable>
                                <textarea name="description" id="description" class="form-control" rows="3">
                                    {{ old('description', $item->description) }}
                                </textarea>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
