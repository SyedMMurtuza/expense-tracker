@extends('layouts.dashboard')

@section('content')
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-4">
        <h3 class="fw-bold m-0">Categories</h3>
        <button class="btn btn-ink" data-bs-toggle="modal" data-bs-target="#addCat"><i class="bi bi-plus-lg me-1"></i>Add
            Category</button>
    </div>

    {{-- Success / Error messages --}}
    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="panel">
        <div class="table-responsive">
            <table class="table mb-0">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Type</th>
                        <th>Transactions</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($categories as $item)
                        <tr>
                            <td>
                                <span class="dot me-2" style="background:{{ $item->color }}"></span>
                                <span class="fw-semibold">{{ $item->category_name }}</span>
                            </td>
                            <td>
                                @if ($item->type == 'expense')
                                    <span class="badge text-bg-danger">Expense</span>
                                @else
                                    <span class="badge text-bg-success">Income</span>
                                @endif
                            </td>
                            <td>{{ $item->transaction_count }}</td>
                            <td class="text-end text-nowrap">
                                <button class="btn btn-sm btn-light"><i class="bi bi-pencil"></i></button>
                                <button class="btn btn-sm btn-light text-danger"><i class="bi bi-trash"></i></button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center text-muted py-4">No categories yet</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="modal fade" id="addCat" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <form class="modal-content" action="{{ route('categories.store') }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title fw-bold">Add Category</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Name</label>
                        <input class="form-control" name="category_name" value="{{ old('category_name') }}"
                            placeholder="e.g. Groceries" required>
                    </div>

                    <label class="form-label fw-semibold">Type</label>
                    <div class="btn-group w-100 type-toggle mb-3">
                        <input type="radio" class="btn-check" name="type" id="cExp" value="expense" checked>
                        <label class="btn" for="cExp">Expense</label>
                        <input type="radio" class="btn-check" name="type" id="cInc" value="income">
                        <label class="btn" for="cInc">Income</label>
                    </div>

                    <label class="form-label fw-semibold">Color</label>
                    <input type="color" name="color" class="form-control form-control-color" value="#2f7d6d">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-ink">Add Category</button>
                </div>
            </form>
        </div>
    </div>



@endsection
