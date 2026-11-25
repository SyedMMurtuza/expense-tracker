@extends('layouts.dashboard')

@section('content')

    <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-4">
        <h3 class="fw-bold m-0">Welcome, {{ Auth::user()->full_name }}!</h3>
        <form id="filterForm" method="GET" action="{{ route('dashboard') }}">
            <select name="filter" class="form-select w-auto" onchange="this.form.submit()">
                <option value="">All</option>
                <option value="month"
                    @isset($_GET['filter']) {{ $_GET['filter'] == 'month' ? 'selected' : '' }} @endisset>This
                    Month</option>
                <option value="last"
                    @isset($_GET['filter']) {{ $_GET['filter'] == 'last' ? 'selected' : '' }} @endisset>Last
                    Month</option>
                <option value="year"
                    @isset($_GET['filter']) {{ $_GET['filter'] == 'year' ? 'selected' : '' }} @endisset>This
                    Year</option>
            </select>
        </form>
    </div>

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

    <div class="row g-3 mb-4">
        <div class="col-6 col-xl-3">
            <div class="panel stat d-flex gap-3 align-items-center">
                <div class="ico"><i class="bi bi-wallet2"></i></div>
                <div><small>Total Income</small>
                    <h4>$
                        {{ number_format($totalIncome) }}
                    </h4>
                </div>
            </div>
        </div>
        <div class="col-6 col-xl-3">
            <div class="panel stat d-flex gap-3 align-items-center">
                <div class="ico"><i class="bi bi-cash-stack"></i></div>
                <div><small>Total Expenses</small>
                    <h4>$
                        {{ number_format($totalExpense) }}
                    </h4>
                </div>
            </div>
        </div>
        <div class="col-6 col-xl-3">
            <div class="panel stat d-flex gap-3 align-items-center">
                <div class="ico"><i class="bi bi-piggy-bank"></i></div>
                <div><small>Balance</small>
                    <h4>
                        ${{ number_format($balance) }}

                    </h4>
                </div>
            </div>
        </div>
        <div class="col-6 col-xl-3">
            <div class="panel stat d-flex gap-3 align-items-center">
                <div class="ico"><i class="bi bi-receipt"></i></div>
                <div><small>Transactions</small>
                    <h4>
                        {{ number_format($totalCount) }}
                    </h4>
                </div>
            </div>
        </div>
    </div>

    <div class="panel">
        <div class="d-flex flex-wrap gap-2 justify-content-between align-items-center p-3 border-bottom">
            <h5 class="fw-bold m-0">Transactions</h5>
            <div class="d-flex flex-wrap gap-2">
                <form method="GET" action="{{ url()->current() }}">
                    <div class="input-group" style="max-width:240px">
                        <span class="input-group-text bg-white"><i class="bi bi-search"></i></span>
                        <input class="form-control" name="t" value="{{ $word }}"
                            placeholder="Search transactions...">
                    </div>
                </form>
                <form id="filterForm" method="GET" action="{{ route('dashboard') }}">

                    <select name="type" form="filterForm" class="form-select w-auto" onchange="this.form.submit()">
                        <option value="all" {{ $type == 'all' ? 'selected' : '' }}>All Types</option>
                        <option value="income" {{ $type == 'income' ? 'selected' : '' }}>Income</option>
                        <option value="expense" {{ $type == 'expense' ? 'selected' : '' }}>Expense</option>
                    </select>
                </form>
                <button class="btn btn-ink" data-bs-toggle="modal" data-bs-target="#addTxn"><i
                        class="bi bi-plus-lg me-1"></i>Add Transaction</button>
            </div>
        </div>
        <div class="table-responsive">
            <table class="table mb-0">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Description</th>
                        <th>Category</th>
                        <th>Amount</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>

                    @foreach ($data as $item)
                        <tr class="{{ $item->type === 'income' ? 'table-success' : 'table-danger' }}">
                            <td>{{ substr($item->date, 0, 10) }}</td>
                            <td>
                                @if (empty($item->notes))
                                    -
                                @else
                                    {{ $item->notes }}
                                @endif
                            </td>
                            <td>
                                @if ($item->category)
                                    <span class="dot me-2" style="background:{{ $item->category->color }}"></span>
                                    {{ $item->category->category_name }}
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td>${{ $item->amount }}</td>
                            <td class="text-end text-nowrap">
                                <button type="button" class="btn btn-sm btn-light edit-btn" data-bs-toggle="modal"
                                    data-bs-target="#editTx" data-id="{{ $item->id }}" data-type="{{ $item->type }}"
                                    data-category="{{ $item->category_id }}" data-amount="{{ $item->amount }}"
                                    data-date="{{ substr($item->date, 0, 10) }}" data-notes="{{ $item->notes }}">
                                    <i class="bi bi-pencil"></i>
                                </button>

                                <form action="{{ route('transactions.destroy', $item->id) }}" method="POST"
                                    class="d-inline" onsubmit="return confirm('Are you sure you want to delete this?')">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-sm btn-light text-danger"><i class="bi bi-trash"></i></button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="d-flex justify-content-between align-items-center p-3 border-top pagination-wrap">
            <small class="text-secondary">
                @if ($data->total() > 0)
                    Showing {{ $data->firstItem() }} to {{ $data->lastItem() }} of {{ $data->total() }} results
                @else
                    No results found
                @endif
            </small>

            {{ $data->links() }}
        </div>
    </div>

    <div class="modal fade" id="addTxn" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <form class="modal-content" id="addForm" action="{{ route('transactions.store') }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title fw-bold">Add Transaction</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <label class="form-label fw-semibold">Type</label>
                    <div class="btn-group w-100 type-toggle mb-3">
                        <input type="radio" class="btn-check" name="type" id="tExp" value="expense" checked>
                        <label class="btn btn-outline-danger" for="tExp">Expense</label>

                        <input type="radio" class="btn-check" name="type" id="tInc" value="income">
                        <label class="btn btn-outline-success" for="tInc">Income</label>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold" id="amountLabel">Amount</label>
                        <div class="input-group">
                            <span class="input-group-text">$</span>
                            <input type="number" step="0.01" name="amount" class="form-control" placeholder="0.00"
                                required>
                        </div>
                    </div>

                    <div class="row g-3 mb-3">
                        <div class="col-sm-6">
                            <label class="form-label fw-semibold">Category</label>
                            <select class="form-select" name="category_id" id="categorySelect" required>
                                <option value="">Select Category</option>
                                @foreach ($categories as $item)
                                    <option value="{{ $item->id }}" data-type="{{ $item->type }}">
                                        {{ $item->category_name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-sm-6">
                            <label class="form-label fw-semibold">Date</label>
                            <input type="date" name="date" class="form-control" value="{{ date('Y-m-d') }}"
                                id="datepicker" min="{{ date('Y-m-01', strtotime('first day of last month')) }}"
                                max="{{ date('Y-m-t') }}" required>
                        </div>
                    </div>

                    <label class="form-label fw-semibold">Notes (optional)</label>
                    <textarea class="form-control" rows="2" placeholder="Add a note..." name="notes"></textarea>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                    <button class="btn btn-ink">Add Transaction</button>
                </div>
            </form>
        </div>
    </div>

    <div class="modal fade" id="editTx" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <form class="modal-content" id="editForm" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-header">
                    <h5 class="modal-title fw-bold">Edit Transaction</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <label class="form-label fw-semibold">Type</label>
                    <div class="btn-group w-100 type-toggle mb-3">
                        <input type="radio" class="btn-check" name="type" id="eExp" value="expense">
                        <label class="btn btn-outline-danger" for="eExp">Expense</label>

                        <input type="radio" class="btn-check" name="type" id="eInc" value="income">
                        <label class="btn btn-outline-success" for="eInc">Income</label>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Amount</label>
                        <div class="input-group">
                            <span class="input-group-text">$</span>
                            <input type="number" step="0.01" name="amount" id="eAmount" class="form-control"
                                required>
                        </div>
                    </div>

                    <div class="row g-3 mb-3">
                        <div class="col-sm-6">
                            <label class="form-label fw-semibold">Category</label>
                            <select class="form-select" name="category_id" id="eCategory" required>
                                <option value="">Select Category</option>
                                @foreach ($categories as $item)
                                    <option value="{{ $item->id }}" data-type="{{ $item->type }}">
                                        {{ $item->category_name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-sm-6">
                            <label class="form-label fw-semibold">Date</label>
                            <input type="date" name="date" id="eDate" class="form-control" required>
                        </div>
                    </div>

                    <label class="form-label fw-semibold">Notes</label>
                    <textarea name="notes" id="eNotes" class="form-control" rows="2"></textarea>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-ink">Update</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function setupCategoryFilter(form, select) {
            const options = Array.from(select.querySelectorAll('option[data-type]'));

            function filter(selectedId = '') {
                const checked = form.querySelector('input[name="type"]:checked');
                if (!checked) return;

                select.innerHTML = '<option value="">Select Category</option>';
                options.forEach(opt => {
                    if (opt.dataset.type === checked.value) select.appendChild(opt);
                });
                select.value = selectedId;
            }

            form.querySelectorAll('input[name="type"]').forEach(radio => {
                radio.addEventListener('change', () => filter());
            });

            return filter;
        }

        const addForm = document.getElementById('addForm');
        const filterAdd = setupCategoryFilter(addForm, document.getElementById('categorySelect'));
        filterAdd();

        const amountLabel = document.getElementById('amountLabel');
        document.getElementById('tExp').addEventListener('change', () => amountLabel.innerText = 'Amount');
        document.getElementById('tInc').addEventListener('change', () => amountLabel.innerText = 'Salary');

        const editForm = document.getElementById('editForm');
        const filterEdit = setupCategoryFilter(editForm, document.getElementById('eCategory'));

        document.querySelectorAll('.edit-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                editForm.action = "{{ url('/transactions') }}/" + btn.dataset.id;
                document.getElementById('eAmount').value = btn.dataset.amount;
                document.getElementById('eDate').value = btn.dataset.date;
                document.getElementById('eNotes').value = btn.dataset.notes;
                document.getElementById(btn.dataset.type === 'income' ? 'eInc' : 'eExp').checked = true;

                filterEdit(btn.dataset.category);
            });
        });

        $('#datepicker').datepicker({
            format: 'yyyy-mm-dd',
            endDate: '0d', // Disables all dates after today
            autoclose: true
        });
    </script>
@endsection
