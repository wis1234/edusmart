<x-app-layout>
    <div class="content-wrapper">
        <div class="card">
            <div class="card-header bg-white py-3">
                <div class="d-flex justify-content-between align-items-center">
                    <div class="d-flex align-items-center">
                        <i class="fas fa-university fa-2x text-primary me-3"></i>
                        <div>
                            <h4 class="mb-0">Institutions</h4>
                            <small class="text-muted">Manage your educational institutions</small>
                        </div>
                    </div>
                    <div class="d-flex gap-2">
                        <div class="btn-group">
                            <button type="button" class="btn btn-light active" id="tableViewBtn" title="Table View">
                                <i class="fas fa-table"></i>
                            </button>
                            <button type="button" class="btn btn-light" id="gridViewBtn" title="Grid View">
                                <i class="fas fa-th-large"></i>
                            </button>
                        </div>
                        <a href="{{ route('schools.create') }}" class="btn btn-primary">
                            <i class="fas fa-plus me-2"></i>Add Institution
                        </a>
                    </div>
                </div>
            </div>

            <!-- Search and Filters -->
            <div class="card-body border-bottom">
                <div class="row g-3">
                    <div class="col-md-4">
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0">
                                <i class="fas fa-search text-muted"></i>
                            </span>
                            <input type="text" class="form-control border-start-0" id="searchInput" placeholder="Search institutions...">
                        </div>
                    </div>
                    <div class="col-md-2">
                        <select class="form-select" id="statusFilter">
                            <option value="">All Status</option>
                            <option value="active">Active</option>
                            <option value="inactive">Inactive</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <select class="form-select" id="typeFilter">
                            <option value="">All Types</option>
                            <option value="primary">Primary</option>
                            <option value="secondary">Secondary</option>
                            <option value="high">High School</option>
                            <option value="university">University</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <select class="form-select" id="countryFilter">
                            <option value="">All Countries</option>
                            @foreach($schools->pluck('country')->unique() as $country)
                                <option value="{{ $country }}">{{ $country }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <button class="btn btn-light w-100" id="resetFilters">
                            <i class="fas fa-undo me-2"></i>Reset
                        </button>
                    </div>
                </div>
            </div>

            <!-- Table View -->
            <div class="card-body p-0" id="tableView">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th class="px-4 py-3" style="width: 30%">Name</th>
                                <th class="px-4 py-3" style="width: 25%">Address</th>
                                <th class="px-4 py-3" style="width: 15%">Phone</th>
                                <th class="px-4 py-3" style="width: 20%">Email</th>
                                <th class="px-4 py-3" style="width: 5%">Status</th>
                                <th class="px-4 py-3 text-end" style="width: 5%">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($schools as $school)
                                <tr>
                                    <td class="px-4 py-3">
                                        <div class="d-flex align-items-center">
                                            <div class="flex-grow-1">
                                                <h6 class="mb-0">{{ $school->name }}</h6>
                                                <small class="text-muted">{{ $school->type }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-4 py-3">
                                        <div class="d-flex flex-column">
                                            <span class="text-truncate" style="max-width: 200px;">{{ $school->address }}</span>
                                            <small class="text-muted">{{ $school->city }}, {{ $school->country }}</small>
                                        </div>
                                    </td>
                                    <td class="px-4 py-3">{{ $school->phone }}</td>
                                    <td class="px-4 py-3">
                                        <span class="text-truncate d-inline-block" style="max-width: 200px;">{{ $school->email }}</span>
                                    </td>
                                    <td class="px-4 py-3">
                                        <span class="badge bg-{{ $school->is_active ? 'success' : 'danger' }}">
                                            {{ $school->is_active ? 'Active' : 'Inactive' }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-3 text-end">
                                        <x-action-icons
                                            :viewRoute="route('schools.show', $school)"
                                            :editRoute="route('schools.edit', $school)"
                                            :deleteRoute="route('schools.destroy', $school)"
                                            :canEdit="true"
                                            :canDelete="true"
                                            deleteConfirmMessage="Are you sure you want to delete this institution?"
                                        />
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-4 py-3 text-center">
                                        <div class="text-center py-4">
                                            <i class="fas fa-university fa-3x text-muted mb-3"></i>
                                            <h5 class="text-muted">No institutions found</h5>
                                            <p class="text-muted mb-0">Get started by adding your first institution</p>
                                            <a href="{{ route('schools.create') }}" class="btn btn-primary mt-3">
                                                <i class="fas fa-plus me-2"></i>Add Institution
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Grid View -->
            <div class="card-body d-none" id="gridView">
                <div class="row g-4">
                    @forelse ($schools as $school)
                        <div class="col-md-6 col-lg-4">
                            <div class="card h-100">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-start mb-3">
                                        <div>
                                            <h5 class="card-title mb-1">{{ $school->name }}</h5>
                                            <span class="badge bg-{{ $school->is_active ? 'success' : 'danger' }}">
                                                {{ $school->is_active ? 'Active' : 'Inactive' }}
                                            </span>
                                        </div>
                                        <x-action-icons
                                            :viewRoute="route('schools.show', $school)"
                                            :editRoute="route('schools.edit', $school)"
                                            :deleteRoute="route('schools.destroy', $school)"
                                            :canEdit="true"
                                            :canDelete="true"
                                            deleteConfirmMessage="Are you sure you want to delete this institution?"
                                        />
                                    </div>
                                    <div class="mb-3">
                                        <small class="text-muted d-block mb-1">
                                            <i class="fas fa-map-marker-alt me-2"></i>{{ $school->city }}, {{ $school->country }}
                                        </small>
                                        <small class="text-muted d-block mb-1">
                                            <i class="fas fa-phone me-2"></i>{{ $school->phone }}
                                        </small>
                                        <small class="text-muted d-block">
                                            <i class="fas fa-envelope me-2"></i>{{ $school->email }}
                                        </small>
                                    </div>
                                    <p class="card-text small text-muted mb-0">
                                        {{ Str::limit($school->address, 100) }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="col-12">
                            <div class="text-center py-4">
                                <i class="fas fa-university fa-3x text-muted mb-3"></i>
                                <h5 class="text-muted">No institutions found</h5>
                                <p class="text-muted mb-0">Get started by adding your first institution</p>
                                <a href="{{ route('schools.create') }}" class="btn btn-primary mt-3">
                                    <i class="fas fa-plus me-2"></i>Add Institution
                                </a>
                            </div>
                        </div>
                    @endforelse
                </div>
            </div>

            @if(method_exists($schools, 'links'))
                <div class="card-footer bg-white py-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="text-muted">
                            Showing {{ $schools->firstItem() }} to {{ $schools->lastItem() }} of {{ $schools->total() }} entries
                        </div>
                        <div>
                            {{ $schools->links() }}
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>

    @push('styles')
    <style>
        .content-wrapper {
            padding: 0;
            background: #f8fafc;
        }

        /* Header Styles */
        .card-header {
            background: linear-gradient(to right, #ffffff, #f8fafc);
            border-bottom: 1px solid #e2e8f0;
            padding: 1.5rem;
        }

        .card-header h4 {
            color: #1e293b;
            font-weight: 600;
            letter-spacing: -0.025em;
        }

        .card-header small {
            color: #64748b;
            font-size: 0.875rem;
        }

        /* Button Styles */
        .btn-primary {
            background: linear-gradient(to right, #3b82f6, #2563eb);
            border: none;
            padding: 0.625rem 1.25rem;
            font-weight: 500;
            letter-spacing: 0.025em;
            transition: all 0.3s ease;
        }

        .btn-primary:hover {
            background: linear-gradient(to right, #2563eb, #1d4ed8);
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(37, 99, 235, 0.2);
        }

        .btn-light {
            background: #ffffff;
            border: 1px solid #e2e8f0;
            color: #64748b;
            padding: 0.625rem 1.25rem;
            font-weight: 500;
            transition: all 0.3s ease;
        }

        .btn-light:hover {
            background: #f8fafc;
            border-color: #cbd5e1;
            color: #1e293b;
        }

        .btn-group .btn.active {
            background: #3b82f6;
            border-color: #3b82f6;
            color: #ffffff;
        }

        /* Search and Filters */
        .input-group-text {
            background: #ffffff;
            border: 1px solid #e2e8f0;
            color: #94a3b8;
        }

        .form-control, .form-select {
            border: 1px solid #e2e8f0;
            padding: 0.625rem 1rem;
            font-size: 0.875rem;
            color: #1e293b;
        }

        .form-control:focus, .form-select:focus {
            border-color: #3b82f6;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
        }

        /* Table Styles */
        .table thead th {
            background: #f8fafc;
            color: #475569;
            font-weight: 600;
            text-transform: uppercase;
            font-size: 0.75rem;
            letter-spacing: 0.05em;
            padding: 1rem 1.5rem;
            border-bottom: 2px solid #e2e8f0;
        }

        .table tbody td {
            padding: 1rem 1.5rem;
            color: #1e293b;
            font-size: 0.875rem;
            border-bottom: 1px solid #e2e8f0;
        }

        .table tbody tr:hover {
            background-color: #f8fafc;
        }

        /* Badge Styles */
        .badge {
            padding: 0.5em 0.75em;
            font-weight: 500;
            font-size: 0.75rem;
            border-radius: 0.375rem;
        }

        .badge.bg-success {
            background: linear-gradient(to right, #10b981, #059669) !important;
        }

        .badge.bg-danger {
            background: linear-gradient(to right, #ef4444, #dc2626) !important;
        }

        /* Grid View Styles */
        #gridView .card {
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 0.75rem;
            transition: all 0.3s ease;
        }

        #gridView .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1);
            border-color: #cbd5e1;
        }

        #gridView .card-title {
            color: #1e293b;
            font-weight: 600;
            font-size: 1.125rem;
            margin-bottom: 0.5rem;
        }

        #gridView .card-text {
            color: #64748b;
            font-size: 0.875rem;
            line-height: 1.5;
        }

        /* Action Icons */
        .flex.space-x-3 {
            gap: 0.75rem;
        }

        .flex.space-x-3 svg {
            transition: all 0.3s ease;
        }

        .flex.space-x-3 a:hover svg,
        .flex.space-x-3 button:hover svg {
            transform: scale(1.1);
        }

        /* Pagination */
        .pagination {
            margin: 0;
        }

        .pagination .page-item .page-link {
            padding: 0.5rem 0.75rem;
            border: 1px solid #e2e8f0;
            color: #64748b;
            font-size: 0.875rem;
            transition: all 0.3s ease;
        }

        .pagination .page-item.active .page-link {
            background: #3b82f6;
            border-color: #3b82f6;
            color: #ffffff;
        }

        .pagination .page-item .page-link:hover {
            background: #f8fafc;
            border-color: #cbd5e1;
            color: #1e293b;
        }

        /* Empty State */
        .text-center i.fa-university {
            color: #cbd5e1;
            margin-bottom: 1rem;
        }

        .text-center h5 {
            color: #475569;
            font-weight: 600;
            margin-bottom: 0.5rem;
        }

        .text-center p {
            color: #64748b;
            font-size: 0.875rem;
        }

        /* Card Styles */
        .card {
            border: none;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
            border-radius: 0.75rem;
            margin: 0;
        }

        .card-footer {
            background: #ffffff;
            border-top: 1px solid #e2e8f0;
            padding: 1rem 1.5rem;
        }

        /* Responsive Adjustments */
        @media (max-width: 768px) {
            .card-header {
                padding: 1rem;
            }

            .btn-group {
                margin-bottom: 1rem;
            }

            .table-responsive {
                margin: 0 -1rem;
            }
        }
    </style>
    @endpush

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const tableView = document.getElementById('tableView');
            const gridView = document.getElementById('gridView');
            const tableViewBtn = document.getElementById('tableViewBtn');
            const gridViewBtn = document.getElementById('gridViewBtn');
            const searchInput = document.getElementById('searchInput');
            const statusFilter = document.getElementById('statusFilter');
            const typeFilter = document.getElementById('typeFilter');
            const countryFilter = document.getElementById('countryFilter');
            const resetFilters = document.getElementById('resetFilters');

            // View Toggle
            tableViewBtn.addEventListener('click', function() {
                tableView.classList.remove('d-none');
                gridView.classList.add('d-none');
                tableViewBtn.classList.add('active');
                gridViewBtn.classList.remove('active');
            });

            gridViewBtn.addEventListener('click', function() {
                tableView.classList.add('d-none');
                gridView.classList.remove('d-none');
                tableViewBtn.classList.remove('active');
                gridViewBtn.classList.add('active');
            });

            // Search and Filter Functions
            function filterTable() {
                const searchTerm = searchInput.value.toLowerCase();
                const statusValue = statusFilter.value.toLowerCase();
                const typeValue = typeFilter.value.toLowerCase();
                const countryValue = countryFilter.value.toLowerCase();

                const rows = tableView.querySelectorAll('tbody tr');
                const gridItems = gridView.querySelectorAll('.col-md-6');

                rows.forEach(row => {
                    const name = row.querySelector('h6').textContent.toLowerCase();
                    const type = row.querySelector('small').textContent.toLowerCase();
                    const status = row.querySelector('.badge').textContent.toLowerCase();
                    const country = row.querySelector('small:nth-child(2)').textContent.toLowerCase();

                    const matchesSearch = name.includes(searchTerm);
                    const matchesStatus = !statusValue || status.includes(statusValue);
                    const matchesType = !typeValue || type.includes(typeValue);
                    const matchesCountry = !countryValue || country.includes(countryValue);

                    row.style.display = matchesSearch && matchesStatus && matchesType && matchesCountry ? '' : 'none';
                });

                gridItems.forEach(item => {
                    const name = item.querySelector('.card-title').textContent.toLowerCase();
                    const type = item.querySelector('.badge').textContent.toLowerCase();
                    const status = item.querySelector('.badge').textContent.toLowerCase();
                    const country = item.querySelector('small:nth-child(1)').textContent.toLowerCase();

                    const matchesSearch = name.includes(searchTerm);
                    const matchesStatus = !statusValue || status.includes(statusValue);
                    const matchesType = !typeValue || type.includes(typeValue);
                    const matchesCountry = !countryValue || country.includes(countryValue);

                    item.style.display = matchesSearch && matchesStatus && matchesType && matchesCountry ? '' : 'none';
                });
            }

            // Event Listeners
            searchInput.addEventListener('input', filterTable);
            statusFilter.addEventListener('change', filterTable);
            typeFilter.addEventListener('change', filterTable);
            countryFilter.addEventListener('change', filterTable);

            resetFilters.addEventListener('click', function() {
                searchInput.value = '';
                statusFilter.value = '';
                typeFilter.value = '';
                countryFilter.value = '';
                filterTable();
            });
        });
    </script>
    @endpush
</x-app-layout>
