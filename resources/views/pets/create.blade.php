<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add New Pet</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h1>Add New Pet</h1>

        @if(session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
        @endif

        <form action="{{ route('pets.store') }}" method="POST">
            @csrf
            <div class="mb-3">
                <label for="name" class="form-label">Name</label>
                <input type="text" class="form-control" id="name" name="name" value="{{ old('name') }}" required>
            </div>

            <div class="mb-3">
                <label for="category" class="form-label">Category Name</label>
                <input type="text" class="form-control" id="category" name="category[name]" value="{{ old('category.name') }}">
            </div>

            <div class="mb-3">
                <label for="status" class="form-label">Status</label>
                <select class="form-control" id="status" name="status" required>
                    <option value="available" {{ old('status') == 'available' ? 'selected' : '' }}>Available</option>
                    <option value="pending" {{ old('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="sold" {{ old('status') == 'sold' ? 'selected' : '' }}>Sold</option>
                </select>
            </div>

            <div class="mb-3">
                <label for="tags" class="form-label">Tags</label>
                <ul id="tagsList" class="list-group">
                </ul>
                <div id="tagsContainer">
                    <div id="tagContainer" class="input-group mt-2">
                        <input type="text" id="tagInput" name="tags[]" class="form-control" placeholder="Add a tag">
                    </div>
                </div>
                <button type="button" id="addTagBtn" class="btn btn-success">Add Tag</button>
            </div>

            <button type="submit" class="btn btn-primary">Create Pet</button>
            <a href="{{ route('pets.index') }}" class="btn btn-secondary">Cancel</a>
        </form>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            const tagsContainer = $('#tagsContainer');
            const tagInput = $('#tagInput');
            const tagsList = $('#tagsList');
            const addTagBtn = $('#addTagBtn');
            const tagContainer = $('#tagContainer');

            addTagBtn.on('click', function() {
                const tagValue = tagInput.val().trim();
                if (tagValue !== "") {
                    const newTag = tagContainer.clone();
                    tagsContainer.append(tagContainer.clone());
                    tagInput.val('');
                }
            });

            $('form').on('submit', function() {
            });
        });
    </script>
</body>
</html>
