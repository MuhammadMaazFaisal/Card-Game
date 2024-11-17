<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Card Game</title>
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome for Icons -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <!-- GSAP for Animations -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.11.5/gsap.min.js"></script>
    <!-- SweetAlert2 for Interactive Alerts -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <!-- Custom CSS -->
    <style>
        :root {
            --bg-color: #0d1117;
            --primary-color: #58a6ff;
            --secondary-color: #1f6feb;
            --accent-color: #f78166;
            --text-color: #c9d1d9;
            --card-width: 50px;
            --card-height: 70px;
            --border-radius: 8px;
        }

        body {
            background-color: var(--bg-color);
            color: var(--text-color);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            overflow: hidden;
        }

        /* Navbar */
        .navbar {
            background-color: #161b22;
        }

        .navbar-brand {
            font-weight: bold;
            font-size: 1.2em;
        }

        .btn-outline-light {
            color: var(--primary-color);
            border-color: var(--primary-color);
        }

        .btn-outline-light:hover {
            background-color: var(--primary-color);
            color: var(--bg-color);
        }

        /* Left Screen */
        #left-screen {
            overflow-y: auto;
            padding: 10px;
            display: flex;
            justify-content: center;
        }

        .card-grid {
            display: grid;
            grid-gap: 10px;
            justify-content: center;
            align-items: center;
        }

        /* Card Placeholder Styling */
        .card-placeholder {
            width: var(--card-width);
            height: var(--card-height);
            border-radius: var(--border-radius);
            transition: transform 0.3s, box-shadow 0.3s;
            cursor: pointer;
            overflow: hidden;
            background-size: contain;
            background-position: center;
            box-shadow: 0 4px 15px rgba(88, 166, 255, 0.3);
            border: 1px solid var(--primary-color);
            padding: 3px;
            box-sizing: border-box;
            background-repeat: no-repeat;
        }

        .card-placeholder:hover {
            transform: translateY(-3px);
            box-shadow: 0 4px 10px rgba(88, 166, 255, 0.2);
        }

        .card-placeholder.collected {
            background-color: white;
        }

        /* Buttons */
        .btn-custom {
            background: linear-gradient(135deg, var(--secondary-color), var(--primary-color));
            color: #ffffff;
            border: none;
            width: 100%;
            margin-bottom: 10px;
            padding: 8px;
            font-size: 0.9em;
            border-radius: var(--border-radius);
            transition: transform 0.2s, box-shadow 0.2s;
            position: relative;
            overflow: hidden;
        }

        .btn-custom::after {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle at center, rgba(255, 255, 255, 0.2), transparent 70%);
            transform: translateX(-50%) translateY(-50%) scale(0);
            opacity: 0;
            transition: transform 0.5s, opacity 0.5s;
        }

        .btn-custom:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 15px rgba(88, 166, 255, 0.3);
        }

        .btn-custom:hover::after {
            transform: translateX(-50%) translateY(-50%) scale(1);
            opacity: 1;
        }

        /* Modals */
        .modal-content {
            background-color: #161b22;
            color: var(--text-color);
            border: none;
            border-radius: var(--border-radius);
        }

        .form-control {
            background-color: #0d1117;
            color: var(--text-color);
            border: 1px solid #30363d;
            border-radius: 6px;
        }

        .form-control:focus {
            border-color: var(--primary-color);
            box-shadow: none;
        }

        .form-label,
        .form-text {
            color: #8b949e;
        }

        /* Right Screen */
        #right-screen {
            display: flex;
            flex-direction: column;
            padding: 10px;
            overflow-y: auto;
            align-items: center;
            justify-content: center;
        }

        /* Right Screen Cards */
        .card-grid-right {
            display: grid;
            grid-gap: 10px;
            padding: 10px;
            overflow-y: auto;
            flex: 1;
        }

        .card {
            width: var(--card-width);
            height: var(--card-height);
            border-radius: var(--border-radius);
            background-size: contain;
            background-position: center;
            background-repeat: no-repeat;
            cursor: grab;
        }

        .card:active {
            cursor: grabbing;
        }

        /* Drag Over Effect */
        .card-placeholder.dragover {
            border: 2px dashed var(--accent-color);
        }

        /* Separator */
        .separator {
            border-left: 1px solid #30363d;
            height: calc(100vh - 56px);
        }

        /* Responsive Adjustments */

        /* Laptop and Bigger Screens */
        @media (min-width: 992px) {
            #left-screen {
                height: 80vh;
            }

            .card-grid {
                grid-template-columns: repeat(11, var(--card-width));
                /* 10 cards per row */
            }

            #right-screen {
                height: 80vh;
            }

            .card-grid-right {
                grid-template-columns: repeat(4, var(--card-width));
                /* 4 columns in right screen */
            }
        }

        /* Tablet and Mobile Screens */
        @media (max-width: 991px) {
            #left-screen {
                height: 40vh;
                /* 50% of the viewport height */
            }

            .card-grid {
                grid-template-columns: repeat(13, var(--card-width));
                /* 13 cards per row */
            }

            #right-screen {
                height: 45vh;
                /* 50% of the viewport height */
            }

            .card-grid-right {
                grid-template-columns: repeat(14, var(--card-width));
                /* 13 columns in right screen */
            }
        }

        /* Small Mobile Screens (Portrait) */
        @media (max-width: 576px) {

            .card-placeholder,
            .card {
                width: 40px;
                /* Smaller card size for mobile */
                height: 60px;
            }

            #left-screen,
            #right-screen {
                padding: 5px;
            }

            .btn-custom {
                font-size: 0.8em;
                padding: 5px;
                margin-bottom: 5px;
            }

            .navbar {
                flex-direction: column;
                padding: 5px;
            }
        }
    </style>
</head>

<body>
    <!-- Navigation Bar -->
    <nav class="navbar navbar-dark bg-dark">
        <div class="container-fluid">
            <a class="navbar-brand d-flex align-items-center" href="#">
                <i class="fas fa-gamepad me-2"></i>
                <span>WIN BIG</span>
            </a>

            <div class="mx-auto text-center">
                <h2 class="text-warning mb-0">BILLION $ PRIZE FUND</h2>
                <p class="lead mb-0">PUT THE CARDS IN THE CORRECT ORDER TO WIN</p>
            </div>

            <div class="d-flex">
                <!-- Authentication Logic (Assuming you're using Laravel Blade syntax) -->
                @if (Auth::check() && Auth::user()->userType == 1)
                    <div class="dropdown">
                        <a class="btn btn-outline-light dropdown-toggle" href="#" role="button"
                            id="dropdownMenuLink" data-bs-toggle="dropdown" aria-expanded="false">
                            {{ Auth::user()->name }}
                        </a>
                        <ul class="dropdown-menu dropdown-menu-dark" aria-labelledby="dropdownMenuLink">
                            <li>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button class="dropdown-item" type="submit">
                                        <i class="fas fa-sign-out-alt"></i> Logout
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </div>
                @else
                    <button class="btn btn-outline-light" data-bs-toggle="modal" data-bs-target="#loginModal">
                        <i class="fas fa-sign-in-alt"></i> Login
                    </button>
                @endif
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="container-fluid">
        <div class="row">
            <!-- Left Screen -->
            <div class="col-xl-8 col-lg-8 col-md-12" id="left-screen">
                <div class="card-grid">
                    <!-- Card Placeholders will be generated here -->
                </div>
            </div>
            <!-- Right Screen -->
            <div class="col-xl-3 col-lg-4 col-md-12" id="right-screen">
                <div class="card-grid-right">
                    <!-- Cards will be generated here -->
                </div>
                <!-- Buttons and Remaining Attempts -->
                <div class="p-3">
                    <p class="mt-2 text-center">Remaining Attempts: <span id="remaining-attempts">0</span></p>
                    <button class="btn btn-custom" id="buy-card-btn"><i class="fas fa-shopping-cart"></i> Buy More
                        Attempts</button>
                    <button class="btn btn-custom" id="contact-details-btn"><i class="fas fa-phone-alt"></i> Contact
                        Details</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Login Modal -->
    <div class="modal fade" id="loginModal" tabindex="-1" aria-labelledby="loginModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="fas fa-sign-in-alt"></i> Login</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form method="POST" action="{{ route('login') }}">
                        @csrf

                        <div class="mb-3">
                            <label for="loginEmail" class="form-label">Email address</label>
                            <input type="email" class="form-control" id="loginEmail" name="email" required>
                        </div>
                        <div class="mb-3">
                            <label for="loginPassword" class="form-label">Password</label>
                            <input type="password" class="form-control" id="loginPassword" name="password" required>
                        </div>
                        <button type="submit" class="btn btn-custom w-100 mb-2">Login</button>
                    </form>
                    <div class="text-center">
                        <p>Don't have an account? <a href="#" id="showRegister">Register here</a></p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Register Modal -->
    <div class="modal fade" id="registerModal" tabindex="-1" aria-labelledby="registerModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="fas fa-user-plus"></i> Register</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form method="POST" action="{{ route('register') }}">
                        @csrf
                        <div class="mb-3">
                            <label for="registerName" class="form-label">Full Name</label>
                            <input type="text" class="form-control" id="registerName" name="name" required>
                        </div>
                        <div class="mb-3">
                            <label for="registerEmail" class="form-label">Email address</label>
                            <input type="email" class="form-control" id="registerEmail" name="email" required>
                        </div>
                        <div class="mb-3">
                            <label for="registerPassword" class="form-label">Password</label>
                            <input type="password" class="form-control" id="registerPassword" name="password"
                                required>
                        </div>
                        <div class="mb-3">
                            <label for="registerConfirmPassword" class="form-label">Confirm Password</label>
                            <input type="password" class="form-control" id="registerConfirmPassword"
                                name="password_confirmation" required>
                        </div>
                        <button type="submit" class="btn btn-custom w-100 mb-2">Register</button>
                    </form>
                    <div class="text-center">
                        <p>Already have an account? <a href="#" id="showLogin">Login here</a></p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS and Dependencies -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Font Awesome JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/js/all.min.js"></script>
    <!-- Custom Script -->
    <script>
        // Authentication check (Assuming you're using Laravel Blade syntax)
        const isAuthenticated = {{ Auth::check() ? 'true' : 'false' }};
        let remainingAttempts = {{ Auth::check() ? Auth::user()->remainingAttempts : 0 }};
        document.getElementById('remaining-attempts').innerText = remainingAttempts;

        const collectedCards = {!! json_encode($gameState->collected_cards ?? []) !!};
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        // Define the hardcoded sequence of 52 cards (including duplicates)
        const deckSequence = [
            // First 10 cards as specified
            {
                value: 'ace',
                suit: 'diamonds'
            },
            {
                value: 'jack',
                suit: 'diamonds'
            },
            {
                value: '2',
                suit: 'hearts'
            },
            {
                value: '10',
                suit: 'spades'
            },
            {
                value: '8',
                suit: 'spades'
            },
            {
                value: '7',
                suit: 'spades'
            },
            {
                value: 'queen',
                suit: 'clubs'
            },
            {
                value: 'jack',
                suit: 'spades'
            },
            {
                value: '2',
                suit: 'diamonds'
            }, // Duplicate
            {
                value: 'queen',
                suit: 'diamonds'
            },
            // Remaining cards to complete the deck (no duplicates)
            // ... [Add the rest of the cards to complete 52 cards]
            // For brevity, I will include the remaining cards here
            {
                value: 'king',
                suit: 'diamonds'
            },
            {
                value: '3',
                suit: 'diamonds'
            },
            {
                value: '4',
                suit: 'diamonds'
            },
            {
                value: '5',
                suit: 'diamonds'
            },
            {
                value: '6',
                suit: 'diamonds'
            },
            {
                value: '7',
                suit: 'diamonds'
            },
            {
                value: '8',
                suit: 'diamonds'
            },
            {
                value: '9',
                suit: 'diamonds'
            },
            {
                value: '10',
                suit: 'diamonds'
            },
            {
                value: 'king',
                suit: 'clubs'
            },
            {
                value: 'ace',
                suit: 'clubs'
            },
            {
                value: '2',
                suit: 'clubs'
            },
            {
                value: '3',
                suit: 'clubs'
            },
            {
                value: '4',
                suit: 'clubs'
            },
            {
                value: '5',
                suit: 'clubs'
            },
            {
                value: '6',
                suit: 'clubs'
            },
            {
                value: '7',
                suit: 'clubs'
            },
            {
                value: '8',
                suit: 'clubs'
            },
            {
                value: '9',
                suit: 'clubs'
            },
            {
                value: '10',
                suit: 'clubs'
            },
            {
                value: 'jack',
                suit: 'clubs'
            },
            {
                value: 'king',
                suit: 'hearts'
            },
            {
                value: 'ace',
                suit: 'hearts'
            },
            {
                value: '3',
                suit: 'hearts'
            },
            {
                value: '4',
                suit: 'hearts'
            },
            {
                value: '5',
                suit: 'hearts'
            },
            {
                value: '6',
                suit: 'hearts'
            },
            {
                value: '7',
                suit: 'hearts'
            },
            {
                value: '8',
                suit: 'hearts'
            },
            {
                value: '9',
                suit: 'hearts'
            },
            {
                value: '10',
                suit: 'hearts'
            },
            {
                value: 'jack',
                suit: 'hearts'
            },
            {
                value: 'queen',
                suit: 'hearts'
            },
            {
                value: 'king',
                suit: 'spades'
            },
            {
                value: 'ace',
                suit: 'spades'
            },
            {
                value: '2',
                suit: 'spades'
            },
            {
                value: '3',
                suit: 'spades'
            },
            {
                value: '4',
                suit: 'spades'
            },
            {
                value: '5',
                suit: 'spades'
            },
            {
                value: '6',
                suit: 'spades'
            },
            {
                value: '9',
                suit: 'spades'
            },
            {
                value: 'queen',
                suit: 'spades'
            },
            {
                value: 'king',
                suit: 'hearts'
            },
        ];

        // Generate the card placeholders using deckSequence
        const cardGrid = document.querySelector('.card-grid');
        deckSequence.forEach(card => {
            const placeholderDiv = document.createElement('div');
            placeholderDiv.classList.add('card-placeholder');
            placeholderDiv.id = `placeholder-${card.value}-of-${card.suit}`;
            cardGrid.appendChild(placeholderDiv);
        });

        // Generate the cards on the right screen in normal order, including duplicates if necessary
        const cardGridRight = document.querySelector('.card-grid-right');

        // First, create a map to count how many times each card appears in the placeholders
        const cardCounts = {};
        deckSequence.forEach(card => {
            const cardName = `${card.value}-of-${card.suit}`;
            if (cardCounts[cardName]) {
                cardCounts[cardName]++;
            } else {
                cardCounts[cardName] = 1;
            }
        });

        // Now generate the cards on the right in normal order, including duplicates as needed
        const suits = ['clubs', 'diamonds', 'hearts', 'spades'];
        const values = ['ace', '2', '3', '4', '5', '6', '7', '8', '9', '10', 'jack', 'queen', 'king'];

        for (let suit of suits) {
            for (let value of values) {
                const cardName = `${value}-of-${suit}`;
                const count = cardCounts[cardName] || 0;

                // Add the card as many times as it appears in the placeholders
                for (let i = 0; i < count; i++) {
                    if (!collectedCards.includes(cardName)) {
                        const cardDiv = document.createElement('div');
                        cardDiv.classList.add('card', 'draggable-card');
                        cardDiv.id = `card-${cardName}-${i}`; // Include index to make IDs unique
                        const imageName = `${value}_of_${suit}`;
                        cardDiv.style.backgroundImage =
                            `url('https://raw.githubusercontent.com/hayeah/playing-cards-assets/master/png/${imageName}.png')`;
                        cardDiv.draggable = true;
                        cardDiv.dataset.cardName = cardName; // Store the card name for reference
                        cardGridRight.appendChild(cardDiv);
                    }
                }
            }
        }

        // Add drag event listeners to the cards
        const draggableCards = document.querySelectorAll('.draggable-card');
        draggableCards.forEach(card => {
            card.addEventListener('dragstart', dragStart);
        });

        function dragStart(e) {
            if (!isAuthenticated) {
                e.preventDefault();
                // Open login modal
                var loginModalInstance = new bootstrap.Modal(document.getElementById('loginModal'));
                loginModalInstance.show();
                return;
            }
            if (remainingAttempts <= 0) {
                e.preventDefault();
                Swal.fire({
                    icon: 'warning',
                    title: 'No Attempts Left',
                    text: 'You have no remaining attempts. Please buy more attempts.',
                });
                return;
            }
            e.dataTransfer.setData('text/plain', e.target.id);
        }

        // Add drag event listeners to the placeholders
        const cardPlaceholders = document.querySelectorAll('.card-placeholder');
        cardPlaceholders.forEach(placeholder => {
            placeholder.addEventListener('dragover', dragOver);
            placeholder.addEventListener('drop', dropCard);
            placeholder.addEventListener('dragenter', dragEnter);
            placeholder.addEventListener('dragleave', dragLeave);
        });

        function dragOver(e) {
            if (e.target.classList.contains('collected')) {
                return;
            }
            e.preventDefault();
            e.dataTransfer.dropEffect = 'move';
        }

        function dragEnter(e) {
            if (e.target.classList.contains('collected')) {
                return;
            }
            e.preventDefault();
            e.target.classList.add('dragover');
        }

        function dragLeave(e) {
            e.target.classList.remove('dragover');
        }

        function dropCard(e) {
            e.preventDefault();
            e.target.classList.remove('dragover');
            if (e.target.classList.contains('collected')) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Already Filled',
                    text: 'This placeholder already has a card.',
                });
                return;
            }
            const cardId = e.dataTransfer.getData('text/plain');
            const cardElement = document.getElementById(cardId);
            const placeholderId = e.target.id;

            // Check if the card matches the placeholder
            const cardName = cardElement.dataset.cardName;
            const placeholderName = placeholderId.replace('placeholder-', '');

            // Decrease remaining attempts
            remainingAttempts--;
            document.getElementById('remaining-attempts').innerText = remainingAttempts;

            if (cardName === placeholderName) {
                // Correct match
                e.target.style.backgroundImage = cardElement.style.backgroundImage;
                e.target.classList.add('collected');

                // Remove the card from the right screen
                cardElement.parentNode.removeChild(cardElement);

                // Add to collectedCards
                collectedCards.push(cardName);

                // Send AJAX request to update game state
                updateGameState();

                // Check if all cards are collected
                if (document.querySelectorAll('.draggable-card').length === 0) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Congratulations!',
                        text: 'You have collected all cards!',
                    });
                }
            } else {
                // Incorrect match
                Swal.fire({
                    icon: 'error',
                    title: 'Incorrect Placement',
                    text: 'That card does not belong here.',
                });

                // Update remainingAttempts in the database
                updateGameState();
            }

            // Check if attempts are exhausted
            if (remainingAttempts <= 0) {
                // Disable dragging for all cards
                const remainingCards = document.querySelectorAll('.draggable-card');
                remainingCards.forEach(card => {
                    card.draggable = false;
                });
            }
        }

        function updateGameState() {
            fetch('{{ route('game.update') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json',
                    },
                    body: JSON.stringify({
                        collectedCards: collectedCards,
                        remainingAttempts: remainingAttempts,
                    }),
                })
                .then(response => response.json())
                .then(data => {
                    if (!data.success) {
                        console.error('Failed to update game state.');
                    }
                })
                .catch(error => console.error('Error:', error));
        }

        // Restore game state on page load
        window.onload = function() {
            // Restore collected cards
            collectedCards.forEach(cardName => {
                const placeholderId = `placeholder-${cardName}`;
                const placeholderElement = document.getElementById(placeholderId);
                if (placeholderElement) {
                    const imageName = cardName.replace(/-/g, '_');
                    placeholderElement.style.backgroundImage =
                        `url('https://raw.githubusercontent.com/hayeah/playing-cards-assets/master/png/${imageName}.png')`;
                    placeholderElement.classList.add('collected');
                }

                // Remove the collected card(s) from the right screen
                const cardElements = document.querySelectorAll(`.draggable-card[data-card-name='${cardName}']`);
                cardElements.forEach(cardElement => {
                    cardElement.parentNode.removeChild(cardElement);
                });
            });

            // If attempts are exhausted, disable dragging
            if (remainingAttempts <= 0) {
                const remainingCards = document.querySelectorAll('.draggable-card');
                remainingCards.forEach(card => {
                    card.draggable = false;
                });
            }
        };

        // Buy More Attempts
        document.getElementById('buy-card-btn').addEventListener('click', function() {
            if (!isAuthenticated) {
                // Open login modal
                var loginModalInstance = new bootstrap.Modal(document.getElementById('loginModal'));
                loginModalInstance.show();
                return;
            }

            // Show bank details for payment
            Swal.fire({
                title: 'Bank Details',
                html: '<strong>Bank Name:</strong> Bank of America<br><strong>Account Number:</strong> 1234567890<br><strong>IFSC Code:</strong> ABCD123456',
                icon: 'info',
                confirmButtonText: 'Close',
                customClass: {
                    confirmButton: 'btn btn-custom'
                },
                buttonsStyling: false
            });
        });

        // Modal Switching Logic
        var loginModal = new bootstrap.Modal(document.getElementById('loginModal'));
        var registerModal = new bootstrap.Modal(document.getElementById('registerModal'));

        document.getElementById('showRegister').addEventListener('click', function(e) {
            e.preventDefault();
            loginModal.hide();
            registerModal.show();
        });

        document.getElementById('showLogin').addEventListener('click', function(e) {
            e.preventDefault();
            registerModal.hide();
            loginModal.show();
        });

        // Contact Details Button Alert
        document.getElementById('contact-details-btn').addEventListener('click', function() {
            Swal.fire({
                title: 'Admin Contact Details',
                html: '<strong>Name:</strong> John Doe<br><strong>Email:</strong> admin@example.com<br><strong>Phone:</strong> +1234567890',
                icon: 'info',
                confirmButtonText: 'Close',
                customClass: {
                    confirmButton: 'btn btn-custom'
                },
                buttonsStyling: false
            });
        });
    </script>
</body>

</html>
