<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <title>Dashboard | Praman v2</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    
    <style>
        :root {
            --bs-primary: #0f172a; /* Slate 900 */
            --bs-secondary: #64748b; /* Slate 500 */
            --bs-body-bg: #f1f5f9; /* Slate 100 */
            --bs-card-border: #e2e8f0;
        }

        body {
            background-color: var(--bs-body-bg);
            font-family: 'Segoe UI', system-ui, -apple-system, sans-serif;
            color: #334155;
        }

        /* Navbar Styling */
        .navbar {
            background-color: #ffffff;
            border-bottom: 1px solid #e2e8f0;
            box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
        }

        /* Card Styling */
        .card {
            border: 1px solid var(--bs-card-border);
            border-radius: 12px;
            box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1);
            transition: transform 0.2s, box-shadow 0.2s;
        }

        /* Hover effect only for interactive list cards */
        .list-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
            border-color: #cbd5e1;
            cursor: pointer;
        }

        .stat-card {
            border-left: 4px solid var(--bs-primary);
        }

        .btn-primary {
            background-color: var(--bs-primary);
            border-color: var(--bs-primary);
        }

        .btn-primary:hover {
            background-color: #1e293b;
        }

        /* Empty State */
        .empty-state-icon {
            font-size: 4rem;
            color: #cbd5e1;
            margin-bottom: 1rem;
        }
    </style>
</head>
<body>

    <nav class="navbar navbar-expand-lg sticky-top">
        <div class="container">
            <a class="navbar-brand fw-bold text-dark" href="#">
                <i class="fa-solid fa-check-double me-2"></i> Praman <span class="badge bg-dark ms-1" style="font-size: 0.5em; vertical-align: top;">v2.0</span>
            </a>
            
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navContent">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navContent">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item"><a class="nav-link active fw-semibold" href="#">Dashboard</a></li>
                    <li class="nav-item"><a class="nav-link" href="#">Sessions</a></li>
                    <li class="nav-item"><a class="nav-link" href="#">Settings</a></li>
                </ul>
                <div class="d-flex align-items-center">
                    <span class="me-3 text-secondary small" id="user-display-name">Loading...</span>
                    <button class="btn btn-outline-danger btn-sm" id="logoutBtn">
                        <i class="fa-solid fa-right-from-bracket"></i>
                    </button>
                </div>
            </div>
        </div>
    </nav>

    <div class="container py-5">
        
        <div class="d-flex justify-content-between align-items-center mb-5">
            <div>
                <h2 class="fw-bold text-dark mb-1">Dashboard</h2>
                <p class="text-secondary mb-0">Manage your lists and track presence.</p>
            </div>
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createListModal">
                <i class="fa-solid fa-plus me-2"></i> New List
            </button>
        </div>

        <div class="row g-4 mb-5">
            <div class="col-md-4">
                <div class="card stat-card h-100 p-3">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <p class="text-uppercase text-secondary small fw-bold mb-1">Total Lists</p>
                            <h3 class="fw-bold mb-0" id="stat-lists">0</h3>
                        </div>
                        <div class="bg-light p-2 rounded text-primary">
                            <i class="fa-solid fa-layer-group fa-lg"></i>
                        </div>
                    </div>
                </div>
            </div>
            {{-- <div class="col-md-4">
                <div class="card stat-card h-100 p-3" style="border-left-color: #475569;">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <p class="text-uppercase text-secondary small fw-bold mb-1">Total People</p>
                            <h3 class="fw-bold mb-0" id="stat-people">0</h3>
                        </div>
                        <div class="bg-light p-2 rounded text-secondary">
                            <i class="fa-solid fa-users fa-lg"></i>
                        </div>
                    </div>
                </div>
            </div> --}}
            {{-- <div class="col-md-4">
                <div class="card stat-card h-100 p-3" style="border-left-color: #94a3b8;">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <p class="text-uppercase text-secondary small fw-bold mb-1">Sessions Held</p>
                            <h3 class="fw-bold mb-0" id="stat-sessions">0</h3>
                        </div>
                        <div class="bg-light p-2 rounded text-secondary">
                            <i class="fa-solid fa-calendar-check fa-lg"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div> --}}

        <h4 class="fw-bold text-dark mb-4">Your Lists</h4>
        
        <div id="loader" class="text-center py-5">
            <div class="spinner-border text-primary" role="status"></div>
            <p class="mt-2 text-secondary">Loading your data...</p>
        </div>

        <div id="empty-state" class="text-center py-5 d-none bg-white rounded border border-dashed">
            <div class="empty-state-icon">
                <i class="fa-regular fa-folder-open"></i>
            </div>
            <h5 class="fw-bold">No lists found</h5>
            <p class="text-secondary mb-4">You haven't created any presence lists yet.</p>
            <button class="btn btn-outline-dark" data-bs-toggle="modal" data-bs-target="#createListModal">
                Create First List
            </button>
        </div>

        <div id="lists-container" class="row g-4 d-none">
            </div>

    </div>

    <div class="modal fade" id="createListModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content border-0 shadow">
                <div class="modal-header border-bottom-0">
                    <h5 class="modal-title fw-bold">Create New List</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="createListForm">
                        <div class="mb-3">
                            <label class="form-label fw-semibold">List Name</label>
                            <input type="text" class="form-control" id="new-list-name" placeholder="e.g. BCA 3rd Sem, Cricket Team" required>
                            <div class="form-text">This will be the main container for your people.</div>
                        </div>
                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary">Create List</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
const token = localStorage.getItem('praman_token');

document.addEventListener('DOMContentLoaded', async () => {

    // Redirect if not logged in
    if (!token) {
        window.location.href = '/login';
        return;
    }

    const loader = document.getElementById('loader');
    const emptyState = document.getElementById('empty-state');
    const container = document.getElementById('lists-container');

    try {
        const response = await fetch('/api/lists', {
            method: 'GET',
            headers: {
                'Authorization': `Bearer ${token}`,
                'Accept': 'application/json'
            }
        });

        const result = await response.json();

        loader.classList.add('d-none');

        if (!response.ok) {
            throw new Error(result.message || 'Failed to load lists');
        }

        const lists = result.data;

        if (lists.length === 0) {
            emptyState.classList.remove('d-none');
            return;
        }

        container.classList.remove('d-none');

        lists.forEach(list => {
            const card = `
                <div class="col-md-4">
                    <div class="card shadow-sm border-0 h-100">
                        <div class="card-body">
                            <h5 class="fw-bold">${list.name}</h5>
                            <p class="text-secondary small mb-3">
                                List ID: ${list.id}
                            </p>
                            <button onclick="openList(${list.id}, '${list.name}')" class="btn btn-sm btn-dark w-100">
                                Open List
                            </button>
                        </div>
                    </div>
                </div>
            `;
            container.insertAdjacentHTML('beforeend', card);
        });

    } catch (error) {
        loader.classList.add('d-none');
        emptyState.classList.remove('d-none');
        console.error(error.message);
    }

});


document.getElementById('createListForm').addEventListener('submit', async function(e) {
    e.preventDefault();

    const name = document.getElementById('new-list-name').value;

    const response = await fetch('/api/lists', {
        method: 'POST',
        headers: {
            'Authorization': `Bearer ${token}`,
            'Accept': 'application/json',
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({ name })
    });

    const data = await response.json();

    if (response.ok) {
        location.reload();
    } else {
        alert(data.message || 'Error creating list');
    }
});

// 4. Logout Logic
        document.getElementById('logoutBtn').addEventListener('click', async () => {
           fetch('/api/logout', {
    method: 'POST',
    headers: {
        'Authorization': `Bearer ${token}`,
        'Accept': 'application/json'
    }
}).then(() => {
    localStorage.removeItem('praman_token');
    window.location.href = '/login';
});
        });

fetch('/api/user', {
    method: 'GET',
    headers: {
        'Accept': 'application/json',
        'Authorization': `Bearer ${token}`
    }
})
.then(res => res.json())
.then(data => console.log(data));

function openList(id, name) {
    localStorage.setItem('current_list_id', id);
    localStorage.setItem('current_list_name', name);
    window.location.href = '/list-workspace';
}


    </script>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>