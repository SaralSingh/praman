<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Workspace | Praman v2</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    
    <style>
        :root {
            --bs-primary: #0f172a; /* Slate 900 */
            --bs-secondary: #64748b; /* Slate 500 */
            --bs-body-bg: #f1f5f9; /* Slate 100 */
            --bs-border-color: #e2e8f0;
        }

        body {
            background-color: var(--bs-body-bg);
            font-family: 'Segoe UI', system-ui, -apple-system, sans-serif;
            color: #334155;
        }

        /* Navbar */
        .navbar {
            background-color: #ffffff;
            border-bottom: 1px solid var(--bs-border-color);
            box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
        }

        /* Cards */
        .card {
            border: 1px solid var(--bs-border-color);
            border-radius: 12px;
            box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1);
            background-color: #fff;
        }

        /* People List */
        .list-group-item {
            border-left: none;
            border-right: none;
            border-color: #f1f5f9;
            padding: 1rem 1.25rem;
            display: flex;
            align-items: center;
        }
        .list-group-item:first-child { border-top: none; }
        
        .avatar-initial {
            width: 36px; height: 36px;
            background-color: #e2e8f0;
            color: #475569;
            border-radius: 8px;
            display: flex; align-items: center; justify-content: center;
            font-weight: 600; font-size: 0.9rem;
            margin-right: 1rem;
        }

        /* Session List Styling */
        .session-item {
            transition: background-color 0.2s;
            border-bottom: 1px solid var(--bs-border-color);
        }
        .session-item:last-child { border-bottom: none; }
        .session-item:hover { background-color: #f8fafc; }

        /* Buttons */
        .btn-primary {
            background-color: var(--bs-primary);
            border-color: var(--bs-primary);
            padding: 10px 20px;
        }
        .btn-primary:hover { background-color: #1e293b; }
        .btn-dark { background-color: #334155; border-color: #334155; }

        /* Suggestion Chips */
        .suggestion-chip {
            cursor: pointer;
            border: 1px solid #e2e8f0;
            background-color: #ffffff;
            color: #64748b;
            padding: 6px 14px;
            border-radius: 50px;
            font-size: 0.85rem;
            font-weight: 500;
            transition: all 0.2s ease;
            user-select: none;
        }
        .suggestion-chip:hover {
            background-color: #f8fafc;
            color: #0f172a;
            border-color: #cbd5e1;
            transform: translateY(-1px);
        }
    </style>
</head>
<body>

    <nav class="navbar navbar-expand-lg sticky-top">
        <div class="container">
            <a class="navbar-brand fw-bold text-dark" href="/dashboard">
                <i class="fa-solid fa-check-double me-2"></i> Praman <span class="badge bg-dark ms-1" style="font-size: 0.5em; vertical-align: top;">v2.0</span>
            </a>
            <div class="ms-auto">
                <button class="btn btn-outline-secondary btn-sm" onclick="goBack()">
                    <i class="fa-solid fa-arrow-left me-1"></i> Dashboard
                </button>
            </div>
        </div>
    </nav>

    <div class="container py-5">
        
        <div class="row align-items-center mb-5">
            <div class="col-md-8">
                <span class="text-uppercase text-secondary small fw-bold ls-1">Workspace</span>
                <h2 id="list-title" class="fw-bold text-dark mt-1 display-6">Loading...</h2>
            </div>
            <div class="col-md-4 text-md-end mt-3 mt-md-0">
                <button class="btn btn-primary shadow-sm" data-bs-toggle="modal" data-bs-target="#startSessionModal">
                    <i class="fa-solid fa-play me-2"></i> Start Session
                </button>
            </div>
        </div>

        <div class="row g-4">
            
            <div class="col-lg-4 order-lg-2">
                <div class="card p-4 mb-4 border-0 shadow-sm bg-primary text-white">
                    <h6 class="fw-bold text-white-50 text-uppercase small mb-2">Total Enrolled</h6>
                    <div class="d-flex align-items-baseline">
                        <h1 class="fw-bold mb-0 me-2" id="count-display">0</h1>
                        <span class="text-white-50">people</span>
                    </div>
                </div>

                <div class="card p-4 mb-4 border-0 shadow-sm">
                    <h6 class="fw-bold text-secondary text-uppercase small mb-3">Manage List</h6>
                    <button class="btn btn-dark w-100 mb-3 py-2" data-bs-toggle="modal" data-bs-target="#addPeopleModal">
                        <i class="fa-solid fa-user-plus me-2"></i> Add People
                    </button>
<button class="btn btn-outline-danger w-100 py-2" onclick="deleteCurrentList()">
    <i class="fa-solid fa-trash me-2"></i> Delete List
</button>


                </div>

                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-white py-3 border-bottom">
                        <h6 class="fw-bold text-dark mb-0">Previous Sessions</h6>
                    </div>
                    <div class="card-body p-0" style="max-height: 400px; overflow-y: auto;">
                        <div id="sessions-list">
                            <div class="text-center py-4 text-secondary small">Loading history...</div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-8 order-lg-1">
                <div class="card shadow-sm h-100">
                    <div class="card-header bg-white py-3 border-bottom d-flex justify-content-between align-items-center">
                        <h5 class="fw-bold mb-0 text-dark">People</h5>
                        <div class="small text-secondary"><i class="fa-solid fa-circle-check text-success me-1"></i> Synced</div>
                    </div>

                    <div class="card-body p-0">
                        <div id="empty-state" class="text-center py-5 d-none">
                            <i class="fa-solid fa-users-slash text-secondary mb-3" style="font-size: 2rem;"></i>
                            <p class="text-secondary mb-0">No people in this list yet.</p>
                        </div>

                        <ul id="people-list" class="list-group list-group-flush">
                            </ul>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <div class="modal fade" id="startSessionModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg overflow-hidden">
                <div class="modal-header border-0 pb-0 pt-4 px-4 bg-white">
                    <div>
                        <h5 class="modal-title fw-bold text-dark">Start New Session</h5>
                        <p class="text-secondary small mb-0 mt-1">Create a new attendance record.</p>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body px-4 pt-4 pb-2">
                    <div class="form-floating mb-4">
                        <input type="text" id="session-title-input" class="form-control form-control-lg border-secondary-subtle" placeholder="Session Title">
                        <label for="session-title-input" class="text-secondary">Session Title (e.g., DBMS Lec 5)</label>
                    </div>
                    <div class="mb-4">
                        <label class="small fw-bold text-uppercase text-secondary ls-1 mb-2" style="font-size: 0.75rem;">
                            Recent / Suggestions
                        </label>
                        <div id="suggestion-chips-container" class="d-flex flex-wrap gap-2">
                            <span class="text-muted small fst-italic">Loading history...</span>
                        </div>
                    </div>
<div class="alert alert-light border small text-secondary"> 
    <i class="fa-solid fa-info-circle me-1"></i>
     A new session will be created for today's date. </div>
                </div>
                <div class="modal-footer border-0 px-4 pb-4 pt-3">
                    <button class="btn btn-light fw-medium text-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button class="btn btn-primary px-4 py-2 shadow-sm" onclick="startSession()">
                        <i class="fa-solid fa-play me-2"></i> Start Tracking
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="addPeopleModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg">
                <div class="modal-header border-bottom-0 pb-0">
                    <h5 class="modal-title fw-bold">Bulk Add People</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p class="text-secondary small mb-3">Enter names below, one per line.</p>
                    <textarea id="people-textarea" class="form-control bg-light" rows="8" 
                        placeholder="Amit Sharma&#10;Rahul Verma&#10;Sneha Gupta"
                        style="font-family: monospace; border: 1px solid #cbd5e1;"></textarea>
                </div>
                <div class="modal-footer border-top-0 pt-0">
                    <button type="button" class="btn btn-link text-secondary text-decoration-none" data-bs-dismiss="modal">Cancel</button>
                    <button class="btn btn-primary px-4" onclick="submitPeople()">
                        <i class="fa-solid fa-check me-2"></i> Import Names
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        const token = localStorage.getItem('praman_token');
        const listId = localStorage.getItem('current_list_id');
        const listName = localStorage.getItem('current_list_name');

        // --- AUTH CHECK ---
        if (!token || !listId) {
             // window.location.href = '/login'; 
             console.warn('Dev Mode: Token check skipped');
        }

        if(listName) {
            document.getElementById('list-title').innerText = listName;
        }

        // --- EVENT LISTENERS ---
        
        // Modal Show: Load Suggestions
        const modalEl = document.getElementById('startSessionModal');
        modalEl.addEventListener('show.bs.modal', function () {
            fetch(`/api/lists/${listId}/session-titles`, {
                headers: {
                    'Authorization': `Bearer ${token}`,
                    'Accept': 'application/json'
                }
            })
            .then(res => res.json())
            .then(data => {
                const container = document.getElementById('suggestion-chips-container');
                const input = document.getElementById('session-title-input');

                container.innerHTML = '';
                
                // Get unique titles, take top 5
                const uniqueTitles = [...new Set(data.sessions.map(s => s.title))].slice(0, 5);

                if(uniqueTitles.length === 0) {
                    container.innerHTML = '<small class="text-secondary">No recent sessions.</small>';
                    return;
                }

                uniqueTitles.forEach(title => {
                    const chip = document.createElement('div');
                    chip.className = 'suggestion-chip';
                    chip.innerHTML = `<i class="fa-solid fa-clock-rotate-left me-2"></i>${title}`;
                    
                    chip.addEventListener('click', () => {
                        input.value = title;
                        input.focus();
                    });
                    
                    container.appendChild(chip);
                });
            })
            .catch(err => console.error("Error loading suggestions:", err));
        });


        // --- CORE FUNCTIONS ---

        function goBack() {
            window.location.href = '/dashboard';
        }

        function loadPeople() {
            fetch(`/api/lists/${listId}/people`, {
                headers: {
                    'Authorization': `Bearer ${token}`,
                    'Accept': 'application/json'
                }
            })
            .then(res => res.json())
            .then(data => {
                const ul = document.getElementById('people-list');
                const emptyState = document.getElementById('empty-state');
                const countDisplay = document.getElementById('count-display');
                
                ul.innerHTML = '';
                const count = data.data ? data.data.length : 0;
                if(countDisplay) countDisplay.innerText = count;

                if (count === 0) {
                    emptyState.classList.remove('d-none');
                } else {
                    emptyState.classList.add('d-none');
                    data.data.forEach(person => {
                        const initials = person.name.slice(0, 2).toUpperCase();
                        ul.innerHTML += `
                            <li class="list-group-item">
                                <div class="avatar-initial">${initials}</div>
                                <div class="fw-medium text-dark">${person.name}</div>
                            </li>
                        `;
                    });
                }
            })
            .catch(err => console.error('Error loading people:', err));
        }

        function submitPeople() {
            const text = document.getElementById('people-textarea').value.trim();
            if (!text) return;
            const names = text.split('\n').map(n => n.trim()).filter(n => n);

            fetch(`/api/lists/${listId}/people/bulk`, {
                method: 'POST',
                headers: {
                    'Authorization': `Bearer ${token}`,
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ names })
            })
            .then(res => res.json())
            .then(() => {
                document.getElementById('people-textarea').value = '';
                loadPeople();
                const modal = bootstrap.Modal.getInstance(document.getElementById('addPeopleModal'));
                modal.hide();
            })
            .catch(err => {
                alert('Failed to add people');
                console.error(err);
            });
        }

        async function startSession() {
            const title = document.getElementById('session-title-input').value.trim();
            if (!title) {
                alert('Enter session title');
                return;
            }

            try {
                const res = await fetch(`/api/lists/${listId}/sessions`, {
                    method: 'POST',
                    headers: {
                        'Authorization': `Bearer ${token}`,
                        'Content-Type': 'application/json',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({ title })
                });

                const data = await res.json();
                const sessionId = data.data.id;
                
                const modal = bootstrap.Modal.getInstance(document.getElementById('startSessionModal'));
                modal.hide();

                window.location.href = `/session/${sessionId}`;
            } catch (err) {
                console.error(err);
                alert('Failed to start session');
            }
        }

        function loadSessions() {
            fetch(`/api/lists/${listId}/sessions`, {
                headers: {
                    'Authorization': `Bearer ${token}`,
                    'Accept': 'application/json'
                }
            })
            .then(res => res.json())
            .then(data => {
                const div = document.getElementById('sessions-list');
                div.innerHTML = '';

                if(data.data.length === 0) {
                    div.innerHTML = '<div class="text-center py-4 text-secondary small">No past sessions found.</div>';
                    return;
                }

                data.data.forEach(s => {
                    const d = new Date(s.session_date);
                    const dateStr = d.toLocaleDateString();

                    div.innerHTML += `
                        <div class="session-item p-3 d-flex justify-content-between align-items-center">
                            <div class="d-flex align-items-center">
                                <div class="bg-light rounded p-2 text-secondary me-3">
                                    <i class="fa-regular fa-calendar-check"></i>
                                </div>
                                <div>
                                    <div class="fw-semibold text-dark small">${s.title}</div>
                                    <div class="text-secondary" style="font-size: 0.75rem;">${dateStr}</div>
                                </div>
                            </div>
                            <a href="/session-view/${s.id}" class="btn btn-sm btn-outline-dark">
                                View <i class="fa-solid fa-angle-right ms-1"></i>
                            </a>
                        </div>
                    `;
                });
            });
        }

 async function deleteCurrentList() {

    if (!listId || !token) {
        alert('Missing list or auth data');
        return;
    }

    const confirmDelete = confirm(`Delete list "${listName}" ?`);
    if (!confirmDelete) return;

    try {
        const response = await fetch(`/api/lists/${listId}`, {
            method: 'DELETE',
            headers: {
                'Authorization': 'Bearer ' + token,
                'Accept': 'application/json'
            }
        });

        const data = await response.json();

        if (response.ok) {
            
            // cleanup
            localStorage.removeItem('current_list_id');
            localStorage.removeItem('current_list_name');

            window.location.href = '/dashboard';
        } else {
            alert(data.message || 'Delete failed');
        }

    } catch (err) {
        console.error(err);
        alert('Server error');
    }
}

        // --- INIT ---
        if(listId) {
            loadPeople();
            loadSessions();
        }
    </script>

</body>
</html>