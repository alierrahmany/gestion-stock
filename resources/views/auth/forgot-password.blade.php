<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Réinitialisation mot de passe | Gestion de Stock</title>
    <!-- Favicon -->
    <link rel="icon" href="data:image/svg+xml,<svg xmlns=%22http://www.w3.org/2000/svg%22 viewBox=%220 0 100 100%22><text y=%22.9em%22 font-size=%2290%22>📦</text></svg>">

    <!-- Fonts and Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: {
                            50: '#f0f9ff',
                            100: '#e0f2fe',
                            200: '#bae6fd',
                            300: '#7dd3fc',
                            400: '#38bdf8',
                            500: '#0ea5e9',
                            600: '#0284c7',
                            700: '#0369a1',
                            800: '#075985',
                            900: '#0c4a6e',
                        }
                    },
                    fontFamily: {
                        sans: ['Inter', 'sans-serif'],
                    },
                }
            }
        }
    </script>
</head>
<body class="bg-gradient-to-br from-primary-50 to-primary-100 font-sans min-h-screen flex items-center justify-center p-4">
    <div class="w-full max-w-md">
        <div class="bg-white rounded-2xl shadow-xl overflow-hidden">
            <!-- Header with gradient background -->
            <div class="bg-gradient-to-r from-primary-600 to-primary-800 p-6 text-center">
                <div class="flex justify-center mb-3">
                    <div class="bg-white/20 p-3 rounded-full">
                        <i class="fas fa-key text-white text-2xl"></i>
                    </div>
                </div>
                <h2 class="text-2xl font-bold text-white">Réinitialisation du mot de passe</h2>
                <p class="text-primary-100 mt-1">Entrez votre email pour recevoir un lien de réinitialisation</p>
            </div>

            <!-- Form section -->
            <div class="p-6 sm:p-8">
                @if (session('status'))
                    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-5 text-sm flex items-center">
                        <i class="fas fa-check-circle mr-2"></i> {{ session('status') }}
                    </div>
                @endif

                <form method="POST" action="{{ route('password.email') }}">
                    @csrf
                    
                    <!-- Email Input -->
                    <div class="mb-5">
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Adresse Email</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fas fa-envelope text-primary-500"></i>
                            </div>
                            <input id="email" type="email" name="email" value="{{ old('email') }}" required
                                   class="w-full pl-10 pr-4 py-3 rounded-lg border border-gray-300 focus:border-primary-500 focus:ring-2 focus:ring-primary-200 text-sm transition-all outline-none"
                                   placeholder="votre@email.com">
                        </div>
                        @error('email')
                            <div class="text-red-500 text-xs mt-1 flex items-center">
                                <i class="fas fa-exclamation-circle mr-1"></i> {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <!-- Submit Button -->
                    <button type="submit" class="w-full bg-gradient-to-r from-primary-600 to-primary-700 hover:from-primary-700 hover:to-primary-800 text-white py-3 px-4 rounded-lg font-medium text-sm shadow-md hover:shadow-lg transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2">
                        <i class="fas fa-paper-plane mr-2"></i> Envoyer le lien
                    </button>
                </form>

                <!-- Footer -->
                <div class="mt-6 text-center text-sm text-gray-500">
                    <p>Vous vous souvenez de votre mot de passe? <a href="{{ route('login') }}" class="font-medium text-primary-600 hover:text-primary-500">Connectez-vous ici</a></p>
                </div>
            </div>
        </div>
    </div>
</body>
</html>