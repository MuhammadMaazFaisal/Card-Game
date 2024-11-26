<x-app-layout>
    <x-slot name="header">
        <h2 class="text-2xl font-semibold text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Admin Dashboard') }}
        </h2>
    </x-slot>

    <style>
        /* Custom styles for the dashboard */
        .dashboard-container {
            background-color: #1f2937;
            color: #d1d5db;
            border-radius: 8px;
            padding: 20px;
            box-shadow: 0px 4px 12px rgba(0, 0, 0, 0.15);
        }

        .table-header {
            background: linear-gradient(90deg, #6366f1, #4f46e5);
            color: #ffffff;
            font-weight: bold;
            text-transform: uppercase;
            font-size: 14px;
        }

        .table-header th {
            padding: 16px;
        }

        .table-row {
            color: #d1d5db;
        }

        .table-row:hover {
            background-color: #374151;
        }

        .badge {
            background-color: #818cf8;
            color: #111827;
            padding: 4px 12px;
            border-radius: 9999px;
            font-weight: bold;
            font-size: 14px;
            display: inline-block;
            text-align: center;
        }

        .action-form {
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .action-form input[type="number"] {
            width: 60px;
            padding: 8px;
            border-radius: 6px;
            border: 1px solid #4b5563;
            text-align: center;
            font-size: 14px;
            margin-right: 10px;
            background-color: #111827;
            color: #d1d5db;
        }

        .action-button {
            background-color: #6366f1;
            color: #ffffff;
            padding: 8px 16px;
            border-radius: 6px;
            font-weight: bold;
            font-size: 14px;
            border: none;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .action-button:hover {
            background-color: #4f46e5;
        }

        /* Success message styling */
        .alert-success {
            background-color: #d1fae5;
            border-left: 4px solid #10b981;
            color: #065f46;
            padding: 12px 20px;
            border-radius: 6px;
            margin-bottom: 20px;
            font-size: 14px;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        /* Error message styling */
        .alert-error {
            background-color: #fee2e2;
            border-left: 4px solid #ef4444;
            color: #991b1b;
            padding: 12px 20px;
            border-radius: 6px;
            margin-bottom: 20px;
            font-size: 14px;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        /* Icons for success and error messages */
        .alert-icon {
            font-size: 18px;
        }

        /* Responsive styles */
        @media (max-width: 768px) {

            .table-header,
            .table-row {
                display: block;
                text-align: left;
            }

            .table-header th,
            .table-row td {
                display: block;
                padding: 10px 16px;
                border-bottom: 1px solid #374151;
            }

            .table-header th {
                padding-top: 16px;
                font-size: 12px;
                text-transform: uppercase;
            }

            .table-row {
                padding: 12px;
                margin-bottom: 8px;
                background-color: #1f2937;
                border-radius: 8px;
            }

            .table-row td {
                font-size: 14px;
                padding: 8px 16px;
            }

            .action-form {
                flex-direction: column;
                align-items: stretch;
            }

            .action-form input[type="number"],
            .action-button {
                width: 100%;
                margin: 5px 0;
            }
        }
    </style>

    <div class="py-12">
        @if (session('success'))
            <div class="max-w-7xl mx-auto">
                <div class="alert-success d-flex justify-between">
                    <div class="">
                        <span class="alert-icon">✔</span>
                        <span>{{ session('success') }}</span>
                    </div>
                    <button class="close-button" onclick="this.parentElement.style.display='none';">✖</button>
                </div>
            </div>
        @endif

        @if (session('error'))
            <div class="max-w-7xl mx-auto">
                <div class="alert-error d-flex justify-between">
                    <div class="">
                        <span class="alert-icon">✖</span>
                        <span>{{ session('error') }}</span>
                    </div>
                    <button class="close-button" onclick="this.parentElement.style.display='none';">✖</button>
                </div>
            </div>
        @endif

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="dashboard-container">
                <h3 class="text-xl font-semibold mb-4">Manage Users</h3>
                <p class="text-sm mb-6">Easily manage user attempts and actions from this dashboard.</p>

                <div class="overflow-x-auto">
                    <table class="w-full rounded-lg">
                        <thead>
                            <tr class="table-header">
                                <th class="text-left">User ID</th>
                                <th class="text-center">Name</th>
                                <th class="text-center">Email</th>
                                <th class="text-center">Cards Collected</th>
                                <th class="text-center">Remaining Attempts</th>
                                <th class="text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if ($users->isEmpty())
                                <tr class="table-row">
                                    <td class="px-6 py-4" colspan="5">
                                        <div class="text-sm text-center">No users found.</div>
                                    </td>
                                </tr>
                            @endif
                            @foreach ($users as $user)
                                <tr class="table-row">
                                    <td class="px-6 py-4">
                                        <div class="text-sm font-medium text-center">U-{{ $user->id }}</div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="text-sm font-medium text-center">{{ $user->name }}</div>
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        <div class="text-sm">{{ $user->email }}</div>
                                    </td>
                                    <td class="px-6 py-4 text-center"> 
                                        @php
                                            $correctCards = collect($user->gameState->collected_cards ?? [])->filter(function ($card) {
                                                return $card['isCorrect'] ?? false; // Ensure the key exists and is true
                                            });
                                        @endphp
                                        <span class="badge">{{ $correctCards->count() }}</span>
                                    </td>
                                    
                                    <td class="px-6 py-4 text-center">
                                        <span class="badge">{{ $user->remainingAttempts }}</span>
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        <form method="POST" action="{{ route('admin.increaseAttempts', $user->id) }}"
                                            class="action-form">
                                            @csrf
                                            <input type="number" name="attempts" value="10" min="1">
                                            <button type="submit" class="action-button">
                                                Increase
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
