<!-- Date Filter Component -->
<div class="card card-shadcn border-info mb-4">
    <div class="card-body">
        <div class="row align-items-center">
            <div class="col-md-4">
                <div class="input-group">
                    <span class="input-group-text bg-light border-end-0">
                        <i class="fas fa-filter text-info"></i>
                    </span>
                    <select class="form-select" id="dateFilter" onchange="applyFilter()">
                        <option value="daily" {{ request()->get('filter') == 'daily' ? 'selected' : '' }}>Harian</option>
                        <option value="weekly" {{ request()->get('filter') == 'weekly' ? 'selected' : '' }}>Mingguan</option>
                        <option value="monthly" {{ request()->get('filter') == 'monthly' ? 'selected' : '' }}>Bulanan</option>
                        <option value="yearly" {{ request()->get('filter') == 'yearly' ? 'selected' : '' }}>Tahunan</option>
                        <option value="custom" {{ request()->get('filter') == 'custom' ? 'selected' : '' }}>Kustom</option>
                    </select>
                </div>
            </div>

            <div class="col-md-4" id="customDateContainer" style="display: {{ request()->get('filter') == 'custom' ? 'block' : 'none' }};">
                <input type="date" class="form-control" id="customDate"
                       value="{{ request()->get('custom_date') ?? now()->format('Y-m-d') }}"
                       onchange="applyFilter()">
            </div>

            <div class="col-md-4">
                <div class="btn-group" role="group">
                    <button type="button" class="btn btn-outline-info btn-sm" onclick="setQuickPreset('today')">
                        <i class="fas fa-calendar-day me-1"></i>Hari Ini
                    </button>
                    <button type="button" class="btn btn-outline-info btn-sm" onclick="setQuickPreset('this_week')">
                        <i class="fas fa-calendar-week me-1"></i>Minggu Ini
                    </button>
                    <button type="button" class="btn btn-outline-info btn-sm" onclick="setQuickPreset('this_month')">
                        <i class="fas fa-calendar-alt me-1"></i>Bulan Ini
                    </button>
                </div>
            </div>
        </div>

        <!-- Date Range Display -->
        <div class="mt-3 text-center">
            <span class="badge bg-info fs-6">
                <i class="fas fa-calendar-alt me-2"></i>
                {{ $dateRangeText }}
            </span>
        </div>
    </div>
</div>

<script>
function applyFilter() {
    const filter = document.getElementById('dateFilter').value;
    const customDate = document.getElementById('customDate').value;

    // Show/hide custom date field
    const customDateContainer = document.getElementById('customDateContainer');
    if (filter === 'custom') {
        customDateContainer.style.display = 'block';
    } else {
        customDateContainer.style.display = 'none';
    }

    // Get current URL and add filter parameters
    const url = new URL(window.location);
    url.searchParams.set('filter', filter);

    if (filter === 'custom') {
        url.searchParams.set('custom_date', customDate);
    } else {
        url.searchParams.delete('custom_date');
    }

    // Keep page number to go back to first page
    url.searchParams.delete('page');

    // Reload page with new filter
    window.location.href = url.toString();
}

function setQuickPreset(preset) {
    const presets = {
        today: { filter: 'daily', date: new Date() },
        this_week: { filter: 'weekly', date: new Date() },
        this_month: { filter: 'monthly', date: new Date() },
        // Add more presets as needed
    };

    if (presets[preset]) {
        document.getElementById('dateFilter').value = presets[preset].filter;

        if (presets[preset].filter === 'custom') {
            document.getElementById('customDate').value = presets[preset].date.toISOString().split('T')[0];
            document.getElementById('customDateContainer').style.display = 'block';
        } else {
            document.getElementById('customDateContainer').style.display = 'none';
        }

        applyFilter();
    }
}

// Auto-update custom date visibility on filter change
document.getElementById('dateFilter')?.addEventListener('change', function() {
    if (this.value === 'custom') {
        document.getElementById('customDateContainer').style.display = 'block';
    } else {
        document.getElementById('customDateContainer').style.display = 'none';
    }
});

// Initialize filter from URL parameters
document.addEventListener('DOMContentLoaded', function() {
    const urlParams = new URLSearchParams(window.location.search);
    const filter = urlParams.get('filter');
    const customDate = urlParams.get('custom_date');

    if (filter) {
        document.getElementById('dateFilter').value = filter;

        if (filter === 'custom' && customDate) {
            document.getElementById('customDate').value = customDate;
            document.getElementById('customDateContainer').style.display = 'block';
        }
    }
});
</script>