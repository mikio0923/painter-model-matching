@extends('admin.layouts.app')

@section('content')
<div class="mb-8 flex items-center justify-between">
    <h1 class="text-3xl font-bold text-secondary-900">お知らせ編集</h1>
    <a href="{{ route('admin.information.index') }}" class="btn-secondary">一覧に戻る</a>
</div>

<div class="card">
    <div class="card-body">
        <form action="{{ route('admin.information.update', $information) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="space-y-6">
                <div>
                    <label for="title" class="form-label">タイトル <span class="text-error-600">*</span></label>
                    <input type="text" id="title" name="title" value="{{ old('title', $information->title) }}" 
                           class="form-input @error('title') border-error-500 @enderror" required>
                    @error('title')
                        <p class="form-error">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="type" class="form-label">タイプ <span class="text-error-600">*</span></label>
                    <select id="type" name="type" class="form-input @error('type') border-error-500 @enderror" required>
                        <option value="">選択してください</option>
                        <option value="information" {{ old('type', $information->type) === 'information' ? 'selected' : '' }}>お知らせ</option>
                        <option value="press_release" {{ old('type', $information->type) === 'press_release' ? 'selected' : '' }}>プレスリリース</option>
                    </select>
                    @error('type')
                        <p class="form-error">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="content" class="form-label">内容 <span class="text-error-600">*</span></label>
                    <textarea id="content" name="content" rows="10" 
                              class="form-input @error('content') border-error-500 @enderror" required>{{ old('content', $information->content) }}</textarea>
                    @error('content')
                        <p class="form-error">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="published_at" class="form-label">公開日</label>
                    <input type="date" id="published_at" name="published_at" 
                           value="{{ old('published_at', $information->published_at ? $information->published_at->format('Y-m-d') : '') }}" 
                           class="form-input @error('published_at') border-error-500 @enderror">
                    @error('published_at')
                        <p class="form-error">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="flex items-center">
                        <input type="checkbox" name="is_published" value="1" 
                               {{ old('is_published', $information->is_published) ? 'checked' : '' }} class="mr-2">
                        <span class="text-sm text-secondary-700">公開する</span>
                    </label>
                </div>

                <div class="flex gap-4">
                    <button type="submit" class="btn-primary">更新</button>
                    <a href="{{ route('admin.information.index') }}" class="btn-secondary">キャンセル</a>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection

