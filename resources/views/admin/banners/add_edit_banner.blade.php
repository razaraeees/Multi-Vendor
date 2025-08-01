@extends('admin.layout.layout')

@section('content')
    <!-- Page Header -->
    <div class="page-header">
        <h1 class="page-title">{{ empty($banner['id']) ? 'Add New Banner' : 'Edit Banner' }}</h1>
        <div class="page-actions">
            <a href="{{ url('admin/banners') }}" class="btn btn-primary btn-sm">
                <i class="fas fa-arrow-left"></i> Back to List
            </a>
        </div>
    </div>

    <!-- Alerts -->
    @if (Session::has('success_message'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <strong>Success:</strong> {{ Session::get('success_message') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if (Session::has('error_message'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <strong>Error:</strong> {{ Session::get('error_message') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if ($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <strong>Please fix the following errors:</strong>
            <ul class="mb-0 mt-2">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <!-- Banner Form -->
    <div class="card shadow-sm border-0">
        <div class="card-body p-4">
            <form
                action="{{ empty($banner['id']) ? url('admin/add-edit-banner') : url('admin/add-edit-banner/' . $banner['id']) }}"
                method="POST" enctype="multipart/form-data" class="needs-validation" novalidate>
                @csrf

                <div class="row g-4">
                    <!-- Left Column -->
                    <div class="col-md-6">
                        <div class="form-group mb-4">
                            <label for="type">Banner Type</label>
                            <select class="form-control @error('type') is-invalid @enderror" id="type" name="type"
                                required>
                                <option value="" disabled selected>Select Type</option>
                                <option value="Slider"
                                    {{ !empty($banner['type']) && $banner['type'] == 'Slider' ? 'selected' : '' }}>
                                    Slider
                                </option>
                                <option value="Fix"
                                    {{ !empty($banner['type']) && $banner['type'] == 'Fix' ? 'selected' : '' }}>
                                    Fix
                                </option>
                            </select>
                            <div class="invalid-feedback">Please select a banner type.</div>
                        </div>

                        <div class="form-group mb-4">
                            <label for="image">Banner Image</label>
                            <input type="file" class="form-control @error('image') is-invalid @enderror" id="image"
                                name="image" onchange="previewImage(event)">
                            <div class="form-text">Max size: 2MB, formats: jpg, png, webp</div>

                            <!-- Show existing banner image (if editing) -->
                            @if (!empty($banner['image']))
                                <div class="mt-3">
                                    <strong>Current Image:</strong><br>
                                    <img src="{{ asset('front/images/banner_images/' . $banner['image']) }}"
                                        alt="Current Banner" class="img-thumbnail" style="max-width: 120px;">
                                    &nbsp;|&nbsp;
                                    <a href="javascript:void(0)" class="text-danger confirmDelete" module="banner-image"
                                        moduleid="{{ $banner['id'] }}">
                                        Delete Image
                                    </a>
                                </div>
                            @endif

                            <!-- New Image Preview -->
                            <div class="mt-3">
                                <img id="imagePreview" src="" alt="Image Preview"
                                    style="max-width: 120px; display: none;" class="img-thumbnail">
                            </div>

                            <div class="invalid-feedback">
                                @error('image')
                                    {{ $message }}
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Right Column -->
                    <div class="col-md-6">
                        <div class="form-group mb-4">
                            <label for="link">Banner Link</label>
                            <input type="text" class="form-control @error('link') is-invalid @enderror" id="link"
                                name="link" placeholder="Enter Banner Link"
                                value="{{ old('link', $banner['link'] ?? '') }}">
                            <div class="invalid-feedback">
                                @error('link')
                                    {{ $message }}
                                @enderror
                            </div>
                        </div>

                        <div class="form-group mb-4">
                            <label for="title">Banner Title</label>
                            <input type="text" class="form-control @error('title') is-invalid @enderror" id="title"
                                name="title" placeholder="Enter Banner Title"
                                value="{{ old('title', $banner['title'] ?? '') }}">
                            <div class="invalid-feedback">
                                @error('title')
                                    {{ $message }}
                                @enderror
                            </div>
                        </div>

                        <div class="form-group mb-4">
                            <label for="alt">Banner Alternate Text (Alt for SEO)</label>
                            <input type="text" class="form-control @error('alt') is-invalid @enderror" id="alt"
                                name="alt" placeholder="Enter Banner Alternate Text"
                                value="{{ old('alt', $banner['alt'] ?? '') }}">
                            <div class="invalid-feedback">
                                @error('alt')
                                    {{ $message }}
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <div class="d-flex gap-2 mt-4">
                    <button type="submit" class="btn btn-primary">
                        {{ empty($banner['id']) ? 'Add Banner' : 'Update Banner' }}
                    </button>
                    <button type="reset" class="btn btn-light">Cancel</button>
                </div>
            </form>
        </div>
    </div>
@endsection


<script>
    function previewImage(event) {
        const input = event.target;
        const preview = document.getElementById('imagePreview');

        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                preview.src = e.target.result;
                preview.style.display = 'block';
            };
            reader.readAsDataURL(input.files[0]);
        } else {
            preview.style.display = 'none';
        }
    }
</script>
