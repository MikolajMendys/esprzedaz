<!DOCTYPE html>
<html>
<head>
    <title>View Pet</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h1>Pet Details</h1>

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

        @if(isset($pet))
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">{{ $pet->name }}</h5>
                    <p class="card-text">
                        <strong>ID:</strong> {{ $pet->id }}<br>
                        <strong>Status:</strong> {{ $pet->status }}<br>
                        <strong>Photos:</strong>
                        <ul>
                            @foreach($pet->photoUrls as $url)
                                <li><a href="{{ $url }}" target="_blank">{{ $url }}</a></li>
                            @endforeach
                        </ul>
                    </p>

                    <div class="btn-group">
                        <a href="{{ route('pets.edit', $pet->id) }}" class="btn btn-primary">Edit</a>
                        <form action="{{ route('pets.destroy', $pet->id) }}" method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure?')">Delete</button>
                        </form>
                    </div>
                </div>
            </div>
        @endif

        <a href="{{ route('pets.index') }}" class="btn btn-secondary mt-3">Back to List</a>
    </div>
</body>
</html>