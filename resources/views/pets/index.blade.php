<!DOCTYPE html>
<html>
<head>
    <link href="{{ asset('css/app.css') }}?v=1.1" rel="stylesheet">
    <title>Pets Management</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h1>Pets Management</h1>
        
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

        <div class="mb-3">
            <a href="{{ route('pets.create') }}" class="btn btn-primary">Add New Pet</a>
        </div>

        <div class="card">
            <div class="card-body">
                <!-- Status Filter Form -->
                <form action="{{ url('/pets') }}" method="GET" class="mb-3"> <!-- Change this to /pets -->
                    <div class="input-group">
                        <select name="status" class="form-select" onchange="this.form.submit()">
                            <option value="available" {{ $status == 'available' ? 'selected' : '' }}>Available</option>
                            <option value="pending" {{ $status == 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="sold" {{ $status == 'sold' ? 'selected' : '' }}>Sold</option>
                        </select>
                    </div>
                </form>
            </div>
        </div>

        <div class="mt-4">
            <h3>Pets List (Status: {{ ucfirst($status) }})</h3>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Name</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($pets as $pet)
                        <tr>
                            <td>{{ $pet->id }}</td>
                            <td>{{ $pet->name }}</td>
                            <td>{{ $pet->status }}</td>
                            <td>
                                <a href="{{ route('pets.show', $pet->id) }}" class="btn btn-info btn-sm">View</a>
                                <a href="{{ route('pets.edit', $pet->id) }}" class="btn btn-warning btn-sm">Edit</a>
                                <form action="{{ route('pets.destroy', $pet->id) }}" method="POST" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <div class="d-flex justify-content-center pagination">
                {{ $pets->appends(['status' => $status])->links() }}
            </div>
        </div>
    </div>
</body>
</html>
