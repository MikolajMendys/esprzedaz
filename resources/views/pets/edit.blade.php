<!DOCTYPE html>
<html>
<head>
    <title>Edit Pet</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<body>
    <div class="container mt-5">
        <h1>Edit Pet</h1>

        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
        @endif

        <form action="{{ route('pets.update', $pet->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="mb-3">
                <label for="name" class="form-label">Name</label>
                <input type="text" class="form-control" id="name" name="name" value="{{ old('name', $pet->name) }}" required>
            </div>

            <div class="mb-3">
                <label for="category_name" class="form-label">Category Name</label>
                <input type="text" class="form-control" id="category_name" name="category[name]" value="{{ old('category.name', $pet->category['name'] ?? '') }}">
            </div>

            <div class="mb-3">
                <label for="status" class="form-label">Status</label>
                <select class="form-control" id="status" name="status" required>
                    <option value="available" {{ old('status', $pet->status) == 'available' ? 'selected' : '' }}>Available</option>
                    <option value="pending" {{ old('status', $pet->status) == 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="sold" {{ old('status', $pet->status) == 'sold' ? 'selected' : '' }}>Sold</option>
                </select>
            </div>

            <div class="mb-3">
                <label for="tags" class="form-label">Tags</label>
                <ul id="tagsList" class="list-group">
                    @foreach(old('tags', $pet->tags) as $tag)
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <input name="tags[]" class="form-control" value="{{ old('tags[]', $tag['name'] ?? '') }}"/>
                            <button type="button" class="btn btn-danger btn-sm remove-tag">Remove</button>
                        </li>
                    @endforeach
                </ul>
                <div class="input-group mt-2">
                    <input type="text" id="tagInput" name="tags[]" class="form-control" placeholder="Add a tag">
                    <button type="button" id="addTagBtn" class="btn btn-success">Add Tag</button>
                </div>
            </div>

            <div class="mb-3">
                <label for="photoUrls" class="form-label">Photo URLs (one per line)</label>
                <textarea class="form-control" id="photoUrls" name="photoUrls" rows="3">{{ old('photoUrls', implode("\n", $pet->photoUrls)) }}</textarea>
            </div>

            <div class="mb-3">
                <label for="imageUpload" class="form-label">Upload Image</label>
                <input type="file" class="form-control" id="imageUpload" name="photo" accept="image/*">
                <button type="button" id="uploadImageBtn" class="btn btn-info mt-2">Upload Image</button>
            </div>

            <button type="submit" class="btn btn-primary">Update Pet</button>
            <a href="{{ route('pets.index') }}" class="btn btn-secondary">Cancel</a>
        </form>
        <form action="{{ route('pets.destroy', $pet->id) }}" method="POST" style="display:inline;">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-danger btn-sm">Delete</button>
        </form>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            const tagInput = $('#tagInput');
            const tagsList = $('#tagsList');
            const addTagBtn = $('#addTagBtn');
            const uploadImageBtn = $('#uploadImageBtn');
            const imageUploadInput = $('#imageUpload');
            const photoUrlsTextarea = $('#photoUrls');
            const petId = "{{ $pet->id }}";  // Get the pet ID from Laravel blade

            addTagBtn.on('click', function() {
                const tagValue = tagInput.val().trim();
                if (tagValue !== "") {
                    const newTag = $('<li class="list-group-item d-flex justify-content-between align-items-center"></li>');
                    newTag.append('<input name="tags[]" class="form-control" value="' + tagValue + '"/>');
                    newTag.append('<button type="button" class="btn btn-danger btn-sm remove-tag">Remove</button>');
                    tagsList.append(newTag);
                    tagInput.val('');
                }
            });

            $(document).on('click', '.remove-tag', function() {
                $(this).closest('li').remove();
            });

            uploadImageBtn.on('click', function() {
                const formData = new FormData();
                formData.append('photo', imageUploadInput[0].files[0]);

                const csrfToken = $('meta[name="csrf-token"]').attr('content');

                $.ajax({
                    url: '{{ route("pet.uploadImage", ":id") }}'.replace(':id', petId),
                    type: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    headers: {
                        'X-CSRF-TOKEN': csrfToken
                    },
                    success: function(response) {
                        console.log(response);
                        if (response.success && response.url) {
                            const currentUrls = photoUrlsTextarea.val();
                            const newUrl = response.url.message.replace(/\r?\n|\r/, "");
                            photoUrlsTextarea.val(currentUrls + '\n' + newUrl);
                        } else {
                            alert('Failed to upload image. Please try again.');
                        }
                    },
                    error: function(xhr, status, error) {
                        alert('An error occurred: ' + error);
                    }
                });
            });
        });
    </script>
</body>
</html>
