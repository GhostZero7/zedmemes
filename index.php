<?php
session_start();
$isLoggedIn = isset($_SESSION['user_id']);
$username = $_SESSION['username'] ?? '';
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    colors: {
                        'zambian-green': '#228B22',
                        'zambian-red': '#DC143C',
                        'zambian-black': '#000000',
                        'zambian-orange': '#FF8C00',
                    }
                }
            }
        }
    </script>
    <title>ZedMemes - Zambia's Premier Meme Hub</title>
    <style>
        .zambian-gradient {
            background: linear-gradient(45deg, #000000 0%, #228B22 25%, #DC143C 50%, #FF8C00 75%, #000000 100%);
        }
        
        .zambian-animated-bg {
            background: linear-gradient(-45deg, #000000, #228B22, #DC143C, #FF8C00);
            background-size: 400% 400%;
            animation: gradientWave 6s ease-in-out infinite;
        }
        
        @keyframes gradientWave {
            0% { background-position: 0% 50%; }
            25% { background-position: 100% 0%; }
            50% { background-position: 100% 100%; }
            75% { background-position: 0% 100%; }
            100% { background-position: 0% 50%; }
        }
        
        .meme-card {
            transition: all 0.3s ease;
            backdrop-filter: blur(10px);
        }
        
        .meme-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
        }
        
        .dark .meme-card:hover {
            box-shadow: 0 20px 40px rgba(255, 255, 255, 0.1);
        }
        
        .reaction-btn {
            transition: all 0.2s ease;
        }
        
        .reaction-btn:hover {
            transform: scale(1.1);
        }
        
        .logo-text {
            background: linear-gradient(45deg, #000000, #228B22, #DC143C, #FF8C00);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        
        .floating-upload {
            background: linear-gradient(135deg, #228B22, #FF8C00);
            animation: float 3s ease-in-out infinite;
        }
        
        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-10px); }
        }
        
        .modal-backdrop {
            backdrop-filter: blur(5px);
        }
        
        .theme-toggle {
            transition: all 0.3s ease;
        }
        
        .eagle-emblem {
            filter: drop-shadow(0 0 10px rgba(255, 140, 0, 0.5));
        }

        .modal-enter {
            animation: modalEnter 0.3s ease-out;
        }

        @keyframes modalEnter {
            0% { opacity: 0; transform: scale(0.9); }
            100% { opacity: 1; transform: scale(1); }
        }

        .welcome-banner {
            transition: all 0.5s ease-out;
        }

        .welcome-banner.slide-out {
            transform: translateY(-100%);
            opacity: 0;
            max-height: 0;
            margin-bottom: 0;
            padding: 0;
            overflow: hidden;
        }

        /* Fixed image sizing for meme cards */
        .meme-image {
            width: 100%;
            object-fit: contain;/*changed*/
            object-position: center;
            background-color: #f8f9fa;
            border-radius: 12px;
            display: block;
            max-height: 300px;/*changed*/
            min-height: 200px;/*changed*/
        }

        .dark .meme-image {
            background-color: #374151;
        }

        /* Password strength indicator */
        .password-strength {
            height: 4px;
            border-radius: 2px;
            transition: all 0.3s ease;
        }

        .strength-weak { background-color: #DC143C; width: 25%; }
        .strength-fair { background-color: #FF8C00; width: 50%; }
        .strength-good { background-color: #228B22; width: 75%; }
        .strength-strong { background-color: #228B22; width: 100%; }

        /* Mobile responsive improvements */
        @media (max-width: 640px) {
            .mobile-stack {
                flex-direction: column;
                gap: 0.5rem;
            }
            
            .mobile-stack button {
                width: 100%;
                margin: 0 !important;
            }
            
            .header-content {
                padding: 0.5rem 1rem;
            }
            
            .logo-section {
                flex-shrink: 0;
            }
            
            .auth-section {
                min-width: 0;
            }
        }
    </style>
</head>

<body class="bg-gradient-to-br from-gray-50 to-gray-100 dark:from-gray-900 dark:to-gray-800 min-h-screen transition-colors duration-300">
    <header class="sticky top-0 z-40 bg-white/90 dark:bg-gray-900/90 backdrop-blur-md border-b border-gray-200 dark:border-gray-700 shadow-sm transition-colors duration-300">
        <div class="max-w-4xl mx-auto header-content">
            <div class="flex justify-between items-center h-auto sm:h-16 py-3 sm:py-0">
                <div class="flex items-center space-x-2 sm:space-x-3 logo-section">
                    <div class="relative">
                        <div class="w-8 h-8 sm:w-10 sm:h-10 zambian-gradient rounded-lg flex items-center justify-center shadow-lg">
                            <span class="text-white text-lg sm:text-xl eagle-emblem">😒</span>
                        </div>
                    </div>
                    <div>
                        <h1 class="text-xl sm:text-2xl font-bold logo-text">ZedMemes</h1>
                        <p class="text-xs text-gray-500 dark:text-gray-400 hidden sm:block">Zambia's Meme Hub</p>
                    </div>
                </div>

                <div class="flex items-center space-x-2 sm:space-x-3 auth-section">
                    <button id="themeToggle" class="theme-toggle p-2 rounded-lg bg-gray-100 dark:bg-gray-800 text-gray-600 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-700 flex-shrink-0">
                        <i class="fas fa-sun dark:hidden"></i>
                        <i class="fas fa-moon hidden dark:inline"></i>
                    </button>
                    
                    <div id="authButtons" class="flex mobile-stack">
                        <button id="loginBtn" class="bg-zambian-green hover:bg-green-600 text-white px-3 py-2 sm:px-4 rounded-lg transition-all duration-200 shadow-md hover:shadow-lg text-sm sm:text-base">
                            <i class="fas fa-sign-in-alt mr-1 sm:mr-2"></i>Login
                        </button>
                        <button id="signupBtn" class="bg-zambian-orange hover:bg-orange-600 text-white px-3 py-2 sm:px-4 rounded-lg transition-all duration-200 shadow-md hover:shadow-lg ml-2 text-sm sm:text-base">
                            <i class="fas fa-user-plus mr-1 sm:mr-2"></i>Sign Up
                        </button>
                    </div>
                    
                    <div id="userProfile" class="hidden flex items-center space-x-2 sm:space-x-3">
                        <span class="text-xs sm:text-sm font-medium text-gray-700 dark:text-gray-300">Welcome, <span id="username"></span>!</span>
                        <button id="logoutBtn" class="bg-zambian-red hover:bg-red-600 text-white px-3 py-2 sm:px-4 rounded-lg transition-all duration-200 shadow-md hover:shadow-lg text-sm sm:text-base">
                            <i class="fas fa-sign-out-alt mr-1 sm:mr-2"></i>Logout
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <main class="max-w-lg mx-auto px-4 py-6">
        <div id="welcomeBanner" class="welcome-banner zambian-animated-bg rounded-2xl p-6 sm:p-8 mb-8 text-white relative overflow-hidden">
            <div class="relative z-10">
                <div class="flex items-center justify-center mb-4">
                    <h2 class="text-xl sm:text-2xl md:text-3xl font-bold text-center">Welcome to ZedMemes!</h2>
                </div>
                <p class="text-center text-base sm:text-lg opacity-90">Discover, share, and enjoy the funniest memes from Zambia and beyond!</p>
            </div>
        </div>

        <!-- Filter Buttons (Hidden by default) -->
        <div id="memeFilterPanel" class="hidden flex flex-wrap justify-center gap-2 sm:gap-3 mb-6">
    <button id="filter-all" class="filter-btn bg-zambian-green text-white px-3 py-1.5 text-sm rounded-full font-medium shadow transition-all duration-200" data-filter="all">All</button>
    <button id="filter-new" class="filter-btn bg-gray-200 text-gray-700 dark:bg-gray-700 dark:text-gray-300 px-3 py-1.5 text-sm rounded-full font-medium shadow hover:bg-gray-300 dark:hover:bg-gray-600 transition-all duration-200" data-filter="new">New</button>
    <button id="filter-trending" class="filter-btn bg-gray-200 text-gray-700 dark:bg-gray-700 dark:text-gray-300 px-3 py-1.5 text-sm rounded-full font-medium shadow hover:bg-gray-300 dark:hover:bg-gray-600 transition-all duration-200" data-filter="trending">Trending</button>
    <button id="filter-popular" class="filter-btn bg-gray-200 text-gray-700 dark:bg-gray-700 dark:text-gray-300 px-3 py-1.5 text-sm rounded-full font-medium shadow hover:bg-gray-300 dark:hover:bg-gray-600 transition-all duration-200" data-filter="popular">Popular</button>
    <button id="filter-hot" class="filter-btn bg-gray-200 text-gray-700 dark:bg-gray-700 dark:text-gray-300 px-3 py-1.5 text-sm rounded-full font-medium shadow hover:bg-gray-300 dark:hover:bg-gray-600 transition-all duration-200" data-filter="hot">Hot</button>
    <button id="filter-my" class="filter-btn bg-gray-200 text-gray-700 dark:bg-gray-700 dark:text-gray-300 px-3 py-1.5 text-sm rounded-full font-medium shadow hover:bg-gray-300 dark:hover:bg-gray-600 transition-all duration-200" data-filter="my">My Memes</button>
</div>

       <div id="memeGrid" class="grid grid-cols-1 sm:grid-cols-2 gap-6 px-4">



            <!-- Memes will be loaded here -->
        </div>

        <div class="text-center mt-8 mb-20">
            <button id="loadMoreBtn" class="bg-zambian-green hover:bg-green-600 text-white px-8 py-3 rounded-xl font-semibold transition-all duration-200 shadow-lg hover:shadow-xl">
                <i class="fas fa-plus mr-2"></i>Load More Memes
            </button>
        </div>
    </main>

    <button id="uploadMemeBtn" class="floating-upload fixed bottom-8 right-8 text-white px-6 py-4 rounded-full shadow-2xl hover:shadow-3xl transition-all duration-300 z-30">
        <i class="fas fa-plus mr-2"></i>Upload
    </button>

    <!-- Login Modal -->
    <div id="loginModal" class="hidden fixed inset-0 bg-black/50 modal-backdrop flex items-center justify-center z-50 p-4">
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-2xl w-full max-w-md transform transition-all modal-enter">
            <div class="zambian-gradient p-6 rounded-t-2xl">
                <h2 class="text-2xl font-bold text-white flex items-center">
                    <i class="fas fa-sign-in-alt mr-3"></i>Welcome Back!
                </h2>
                <p class="text-white/80 mt-2">Sign in to interact with memes</p>
            </div>
            <form id="loginForm" class="p-6 space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Username</label>
                    <input type="text" name="username" placeholder="Enter your username" required
                           class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg focus:ring-2 focus:ring-zambian-green focus:border-transparent transition-all">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Password</label>
                    <input type="password" name="password" placeholder="Enter your password" required
                           class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg focus:ring-2 focus:ring-zambian-green focus:border-transparent transition-all">
                </div>
                <button type="submit" class="w-full bg-zambian-green hover:bg-green-600 text-white py-3 rounded-lg font-semibold transition-all duration-200 shadow-md hover:shadow-lg">
                    <i class="fas fa-sign-in-alt mr-2"></i>Sign In
                </button>
            </form>
            <div class="px-6 pb-6">
                <button id="closeLogin" class="w-full text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-200 py-2 transition-colors">
                    Cancel
                </button>
            </div>
        </div>
    </div>

    <!-- Signup Modal -->
    <div id="signupModal" class="hidden fixed inset-0 bg-black/50 modal-backdrop flex items-center justify-center z-50 p-4">
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-2xl w-full max-w-md transform transition-all modal-enter">
            <div class="zambian-gradient p-6 rounded-t-2xl">
                <h2 class="text-2xl font-bold text-white flex items-center">
                    <i class="fas fa-user-plus mr-3"></i>Join ZedMemes!
                </h2>
                <p class="text-white/80 mt-2">Create your account and start sharing</p>
            </div>
            <form id="signupForm" class="p-6 space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Username</label>
                    <input type="text" name="username" placeholder="Choose a username" required
                           class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg focus:ring-2 focus:ring-zambian-orange focus:border-transparent transition-all">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Email</label>
                    <input type="email" name="email" placeholder="Enter your email" required
                           class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg focus:ring-2 focus:ring-zambian-orange focus:border-transparent transition-all">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Password</label>
                    <input type="password" name="password" id="signupPassword" placeholder="Create a strong password" required
                           class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg focus:ring-2 focus:ring-zambian-orange focus:border-transparent transition-all">
                    <div class="mt-2">
                        <div class="flex justify-between text-xs text-gray-500 dark:text-gray-400 mb-1">
                            <span>Password Strength</span>
                            <span id="strengthText">Weak</span>
                        </div>
                        <div class="w-full bg-gray-200 dark:bg-gray-600 rounded-full h-1">
                            <div id="strengthBar" class="password-strength strength-weak rounded-full h-1"></div>
                        </div>
                        <div class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                            <span id="passwordRequirements">Must be at least 8 characters with uppercase, lowercase, number, and special character</span>
                        </div>
                    </div>
                </div>
                <button type="submit" id="signupSubmit" class="w-full bg-zambian-orange hover:bg-orange-600 text-white py-3 rounded-lg font-semibold transition-all duration-200 shadow-md hover:shadow-lg disabled:opacity-50 disabled:cursor-not-allowed">
                    <i class="fas fa-user-plus mr-2"></i>Create Account
                </button>
            </form>
            <div class="px-6 pb-6">
                <button id="closeSignup" class="w-full text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-200 py-2 transition-colors">
                    Cancel
                </button>
            </div>
        </div>
    </div>

    <!-- Upload Modal -->
    <div id="uploadModal" class="hidden fixed inset-0 bg-black/50 modal-backdrop flex items-center justify-center z-50 p-4">
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-2xl w-full max-w-md transform transition-all modal-enter">
            <div class="zambian-gradient p-6 rounded-t-2xl">
                <h2 class="text-2xl font-bold text-white flex items-center">
                    <i class="fas fa-upload mr-3"></i>Share Your Meme
                </h2>
                <p class="text-white/80 mt-2">Upload and share your funny content</p>
            </div>
            <form id="uploadForm" class="p-6 space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Choose Image</label>
                    <div class="border-2 border-dashed border-gray-300 dark:border-gray-600 rounded-lg p-6 text-center hover:border-zambian-green dark:hover:border-zambian-green transition-colors">
                        <i class="fas fa-cloud-upload-alt text-4xl text-gray-400 dark:text-gray-500 mb-4"></i>
                        <input type="file" name="memeImage" accept="image/*" class="hidden" id="fileInput">
                        <label for="fileInput" class="cursor-pointer">
                            <span class="text-zambian-green font-semibold">Click to upload</span>
                            <span class="text-gray-500 dark:text-gray-400"> or drag and drop</span>
                        </label>
                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-2">PNG, JPG, GIF up to 10MB</p>
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Meme Title</label>
                    <input type="text" name="memeTitle" placeholder="Give your meme a catchy title" required
                           class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg focus:ring-2 focus:ring-zambian-green focus:border-transparent transition-all">
                </div>
                <button type="submit" class="w-full bg-zambian-green hover:bg-green-600 text-white py-3 rounded-lg font-semibold transition-all duration-200 shadow-md hover:shadow-lg">
                    <i class="fas fa-upload mr-2"></i>Upload Meme
                </button>
            </form>
            <div class="px-6 pb-6">
                <button id="closeUpload" class="w-full text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-200 py-2 transition-colors">
                    Cancel
                </button>
            </div>
        </div>
    </div>

    <!-- Edit Meme Modal -->
    <div id="editMemeModal" class="hidden fixed inset-0 bg-black/50 modal-backdrop flex items-center justify-center z-50 p-4">
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-2xl w-full max-w-md transform transition-all modal-enter">
            <div class="zambian-gradient p-6 rounded-t-2xl">
                <h2 class="text-2xl font-bold text-white flex items-center">
                    <i class="fas fa-edit mr-3"></i>Edit Your Meme
                </h2>
                <p class="text-white/80 mt-2">Update your meme's title or image</p>
            </div>
            <form id="editForm" class="p-6 space-y-4">
                <input type="hidden" name="memeId" id="editMemeId">
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Meme Title</label>
                    <input type="text" name="memeTitle" id="editMemeTitle" placeholder="Update your meme's title" required
                           class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg focus:ring-2 focus:ring-zambian-green focus:border-transparent transition-all">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Change Image (Optional)</label>
                    <div class="border-2 border-dashed border-gray-300 dark:border-gray-600 rounded-lg p-6 text-center hover:border-zambian-green dark:hover:border-zambian-green transition-colors">
                        <i class="fas fa-cloud-upload-alt text-4xl text-gray-400 dark:text-gray-500 mb-4"></i>
                        <input type="file" name="memeImage" accept="image/*" class="hidden" id="editFileInput">
                        <label for="editFileInput" class="cursor-pointer">
                            <span class="text-zambian-green font-semibold">Click to upload new image</span>
                            <span class="text-gray-500 dark:text-gray-400"> or drag and drop</span>
                        </label>
                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-2">PNG, JPG, GIF up to 10MB</p>
                    </div>
                </div>
                <button type="submit" class="w-full bg-zambian-green hover:bg-green-600 text-white py-3 rounded-lg font-semibold transition-all duration-200 shadow-md hover:shadow-lg">
                    <i class="fas fa-save mr-2"></i>Save Changes
                </button>
            </form>
            <div class="px-6 pb-6">
                <button id="closeEdit" class="w-full text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-200 py-2 transition-colors">
                    Cancel
                </button>
            </div>
        </div>
    </div>

    <!-- Comments Modal -->
    <div id="commentsModal" class="hidden fixed inset-0 bg-black/50 modal-backdrop flex items-center justify-center z-50 p-4">
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-2xl w-full max-w-md transform transition-all modal-enter">
            <div class="zambian-gradient p-6 rounded-t-2xl">
                <h2 class="text-2xl font-bold text-white flex items-center">
                    <i class="fas fa-comments mr-3"></i>Comments
                </h2>
            </div>
            <div class="p-6">
                <div id="commentsList" class="space-y-4 h-64 overflow-y-auto mb-4 pr-2">
                    <!-- Comments will be loaded here -->
                </div>
                <form id="addCommentForm" class="flex flex-col sm:flex-row space-y-2 sm:space-y-0 sm:space-x-2">
                    <input type="hidden" id="commentMemeId">
                    <input type="text" id="commentInput" placeholder="Add a comment..." required
                           class="flex-grow px-4 py-3 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg focus:ring-2 focus:ring-zambian-green focus:border-transparent transition-all">
                    <button type="submit" class="bg-zambian-green hover:bg-green-600 text-white px-5 py-3 rounded-lg font-semibold transition-all duration-200 shadow-md hover:shadow-lg flex-shrink-0">
                        <i class="fas fa-paper-plane mr-2"></i>Post
                    </button>
                </form>
            </div>
            <div class="px-6 pb-6">
                <button id="closeComments" class="w-full text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-200 py-2 transition-colors">
                    Close
                </button>
            </div>
        </div>
    </div>

    <script>
        // Declare the $ variable to avoid linting errors
        const $ = window.$
        let isLoggedIn = false
        let currentUser = '' // To store the logged-in username
        let currentPage = 0
        const memesPerPage = 5 // Matches default limit in fetch_memes.php
        let totalMemes = 0 // To be updated by fetch_memes.php response
        let currentUserId = null
        

        $(document).ready(() => {
           $.getJSON('check_session.php', function(res) {
    if (res.loggedIn) {
        isLoggedIn = true
        currentUser = res.username
        currentUserId = res.user_id  // <--- THIS IS CRUCIAL
        updateAuthState(true, res.username)
        hideWelcomeBanner()
    }
})
            // Password strength checker
            function checkPasswordStrength(password) {
                let strength = 0
                let feedback = []

                // Length check
                if (password.length >= 8) strength += 1
                else feedback.push("at least 8 characters")

                // Uppercase check
                if (/[A-Z]/.test(password)) strength += 1
                else feedback.push("uppercase letter")

                // Lowercase check
                if (/[a-z]/.test(password)) strength += 1
                else feedback.push("lowercase letter")

                // Number check
                if (/\d/.test(password)) strength += 1
                else feedback.push("number")

                // Special character check
                if (/[!@#$%^&*(),.?":{}|<>]/.test(password)) strength += 1
                else feedback.push("special character")

                return { strength, feedback }
            }
            $("#filter-my").on("click", function () {
              $("#memeGrid").empty()
              $("#loadMoreBtn").hide()

             $.getJSON('fetch_user_memes.php', function (res) {
             if (res.success && res.memes.length > 0) {
            res.memes.forEach(meme => renderMemeCard(meme))
            showNotification("Your memes loaded! 🧍", "success")
              } else {
            showNotification("You haven’t uploaded any memes yet.", "info")
        }
    })
})        
$(document).on('click', '.menu-btn', function (e) {
    e.stopPropagation();
    $(".menu-dropdown").not($(this).siblings('.menu-dropdown')).hide(); // Hide others
    $(this).siblings('.menu-dropdown').toggle(); // Toggle this one
});

// Hide dropdown when clicking outside
$(document).on('click', function () {
    $(".menu-dropdown").hide();
});


            // Password strength indicator
            $("#signupPassword").on("input", function() {
                const password = $(this).val()
                const { strength, feedback } = checkPasswordStrength(password)
                const strengthBar = $("#strengthBar")
                const strengthText = $("#strengthText")
                const requirements = $("#passwordRequirements")
                const submitBtn = $("#signupSubmit")

                // Update strength bar
                strengthBar.removeClass("strength-weak strength-fair strength-good strength-strong")
                
                if (strength <= 2) {
                    strengthBar.addClass("strength-weak")
                    strengthText.text("Weak").css("color", "#DC143C")
                } else if (strength === 3) {
                    strengthBar.addClass("strength-fair")
                    strengthText.text("Fair").css("color", "#FF8C00")
                } else if (strength === 4) {
                    strengthBar.addClass("strength-good")
                    strengthText.text("Good").css("color", "#228B22")
                } else if (strength === 5) {
                    strengthBar.addClass("strength-strong")
                    strengthText.text("Strong").css("color", "#228B22")
                }

                // Update requirements
                if (feedback.length > 0) {
                    requirements.text(`Missing: ${feedback.join(", ")}`)
                    submitBtn.prop("disabled", true)
                } else {
                    requirements.text("✓ Password meets all requirements")
                    submitBtn.prop("disabled", false)
                }
            })

            // Theme toggle functionality
            const themeToggle = $("#themeToggle")
            const html = $("html")

            // Check for saved theme preference or default to light mode
            const savedTheme = localStorage.getItem("theme") || "light"
            if (savedTheme === "dark") {
                html.addClass("dark")
            }

            themeToggle.click(() => {
                html.toggleClass("dark")
                const isDark = html.hasClass("dark")
                localStorage.setItem("theme", isDark ? "dark" : "light")

                // Add a subtle animation to the toggle
                themeToggle.addClass("scale-110")
                setTimeout(() => themeToggle.removeClass("scale-110"), 150)
            })

            // Modal functionality
            const modals = {
                login: $("#loginModal"),
                signup: $("#signupModal"),
                upload: $("#uploadModal"),
                comments: $("#commentsModal"),
                edit: $("#editMemeModal")
            }

            // Show modals
            $("#loginBtn").click(() => {
                modals.login.removeClass("hidden")
                modals.login.find('.modal-enter').removeClass('modal-enter')
                setTimeout(() => modals.login.find('div').first().addClass('modal-enter'), 10)
            })
            
            $("#signupBtn").click(() => {
                modals.signup.removeClass("hidden")
                modals.signup.find('.modal-enter').removeClass('modal-enter')
                setTimeout(() => modals.signup.find('div').first().addClass('modal-enter'), 10)
            })
            
            $("#uploadMemeBtn").click(() => {
                // Check if user is logged in
                if (!isLoggedIn) {
                    showNotification("Please login first to upload memes! 🔐", "error")
                    modals.login.removeClass("hidden")
                    return
                }
                modals.upload.removeClass("hidden")
                modals.upload.find('.modal-enter').removeClass('modal-enter')
                setTimeout(() => modals.upload.find('div').first().addClass('modal-enter'), 10)
            })

            $(document).on("click", ".comment-btn", function() {
                if (!isLoggedIn) {
                    showNotification("Please login to view/add comments! 💬", "error")
                    modals.login.removeClass("hidden")
                    return
                }
                const memeId = $(this).closest(".meme-card").data("meme-id")
                $("#commentMemeId").val(memeId)
                fetchComments(memeId)
                modals.comments.removeClass("hidden")
                modals.comments.find('.modal-enter').removeClass('modal-enter')
                setTimeout(() => modals.comments.find('div').first().addClass('modal-enter'), 10)
            })

            $(document).on("click", ".edit-btn", function() {
                if (!isLoggedIn) {
                    showNotification("Please login to edit memes! ✏️", "error")
                    modals.login.removeClass("hidden")
                    return
                }
                const memeId = $(this).closest(".meme-card").data("meme-id")
                const memeTitle = $(this).closest(".meme-card").find("h3").text()
                $("#editMemeId").val(memeId)
                $("#editMemeTitle").val(memeTitle)
                $("#editFileInput").val('')
                $("#editFileInput").siblings("label").find("span").first().text("Click to upload new image")

                modals.edit.removeClass("hidden")
                modals.edit.find('.modal-enter').removeClass('modal-enter')
                setTimeout(() => modals.edit.find('div').first().addClass('modal-enter'), 10)
            })

            // Close modals
            $("#closeLogin, #loginModal").click((e) => {
                if (e.target.id === "closeLogin" || e.target.id === "loginModal") {
                    modals.login.addClass("hidden")
                }
            })

            $("#closeSignup, #signupModal").click((e) => {
                if (e.target.id === "closeSignup" || e.target.id === "signupModal") {
                    modals.signup.addClass("hidden")
                }
            })

            $("#closeUpload, #uploadModal").click((e) => {
                if (e.target.id === "closeUpload" || e.target.id === "uploadModal") {
                    modals.upload.addClass("hidden")
                }
            })

            $("#closeComments, #commentsModal").click((e) => {
                if (e.target.id === "closeComments" || e.target.id === "commentsModal") {
                    modals.comments.addClass("hidden")
                }
            })

            $("#closeEdit, #editMemeModal").click((e) => {
                if (e.target.id === "closeEdit" || e.target.id === "editMemeModal") {
                    modals.edit.addClass("hidden")
                }
            })

            // Form submissions
            $("#loginForm").submit(function (e) {
                e.preventDefault()
                const username = $(this).find('input[name="username"]').val()
                const password = $(this).find('input[name="password"]').val()

                if (!username || !password) {
                    showNotification("Please fill in all fields! ⚠️", "error")
                    return
                }

                const submitBtn = $(this).find('button[type="submit"]')
                submitBtn.html('<i class="fas fa-spinner fa-spin mr-2"></i>Signing In...')
                submitBtn.prop('disabled', true)

                // AJAX call for login
                $.ajax({
                    url: 'login.php', 
                    method: 'POST',
                    data: { username: username, password: password },
                    dataType: 'json',
                    success: function(response) {
                        if (response.success) {
                            showNotification(`Welcome back, ${response.username}! 🎉`, "success")
                            modals.login.addClass("hidden")
                            updateAuthState(true, response.username)
                            hideWelcomeBanner()
                            $("#memeGrid").empty()
                            currentPage = 0
                            fetchMemes(currentPage)
                        } else {
                            showNotification("Login failed: " + response.message, "error")
                        }
                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        showNotification("Failed to connect to the login server. (login.php error?)", "error")
                        console.error("AJAX error:", textStatus, errorThrown, jqXHR.responseText)
                    },
                    complete: function() {
                        $("#loginForm")[0].reset()
                        submitBtn.html('<i class="fas fa-sign-in-alt mr-2"></i>Sign In')
                        submitBtn.prop('disabled', false)
                    }
                })
            })

            $("#signupForm").submit(function (e) {
                e.preventDefault()
                const username = $(this).find('input[name="username"]').val()
                const email = $(this).find('input[name="email"]').val()
                const password = $(this).find('input[name="password"]').val()

                if (!username || !email || !password) {
                    showNotification("Please fill in all fields! ⚠️", "error")
                    return
                }

                // Check password strength
                const { strength } = checkPasswordStrength(password)
                if (strength < 5) {
                    showNotification("Please create a stronger password! 🔒", "error")
                    return
                }

                const submitBtn = $(this).find('button[type="submit"]')
                submitBtn.html('<i class="fas fa-spinner fa-spin mr-2"></i>Creating Account...')
                submitBtn.prop('disabled', true)

                // AJAX call for signup
                $.ajax({
                    url: 'register.php',
                    method: 'POST',
                    data: { username: username, email: email, password: password },
                    dataType: 'json',
                    success: function(response) {
                        if (response.success) {
                            showNotification(`Account created successfully! Welcome to ZedMemes, ${response.username}! 🚀`, "success")
                            modals.signup.addClass("hidden")
                            updateAuthState(true, response.username)
                            hideWelcomeBanner()
                            $("#memeGrid").empty()
                            currentPage = 0
                            fetchMemes(currentPage)
                        } else {
                            showNotification("Signup failed: " + response.message, "error")
                        }
                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        showNotification("Failed to connect to the registration server. Please check register.php.", "error")
                        console.error("AJAX error:", textStatus, errorThrown, jqXHR.responseText)
                    },
                    complete: function() {
                        $("#signupForm")[0].reset()
                        $("#strengthBar").removeClass("strength-weak strength-fair strength-good strength-strong").addClass("strength-weak")
                        $("#strengthText").text("Weak").css("color", "#DC143C")
                        $("#passwordRequirements").text("Must be at least 8 characters with uppercase, lowercase, number, and special character")
                        submitBtn.html('<i class="fas fa-user-plus mr-2"></i>Create Account')
                        submitBtn.prop('disabled', true)
                    }
                })
            })

            $("#uploadForm").submit(function (e) {
                e.preventDefault()
                const title = $(this).find('input[name="memeTitle"]').val()
                const file = $(this).find('input[name="memeImage"]')[0].files[0]

                if (!title || !file) {
                    showNotification("Please provide both image and title! ⚠️", "error")
                    return
                }

                const formData = new FormData()
                formData.append('meme', file)
                formData.append('title', title)

                const submitBtn = $(this).find('button[type="submit"]')
                submitBtn.html('<i class="fas fa-spinner fa-spin mr-2"></i>Uploading...')
                submitBtn.prop('disabled', true)

                $.ajax({
                    url: 'upload.php',
                    method: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    dataType: 'json',
                    success: function(response) {
                        if (response.success) {
                            showNotification("Meme uploaded successfully! 📸", "success")
                            modals.upload.addClass("hidden")
                            $("#memeGrid").empty()
                            currentPage = 0
                            fetchMemes(currentPage)
                        } else {
                            showNotification("Upload failed: " + response.message, "error")
                        }
                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        showNotification("Failed to connect to the upload server. Please check upload.php.", "error")
                        console.error("AJAX error:", textStatus, errorThrown, jqXHR.responseText)
                    },
                    complete: function() {
                        const fileInput = $("#fileInput")
                        fileInput.val('')
                        fileInput.siblings("label").find("span").first().text("Click to upload")
                        $("#uploadForm").find('input[name="memeTitle"]').val('')
                        submitBtn.html('<i class="fas fa-upload mr-2"></i>Upload Meme')
                        submitBtn.prop('disabled', false)
                    }
                })
            })

            $("#editForm").submit(function(e) {
                e.preventDefault()
                const memeId = $("#editMemeId").val()
                const newTitle = $("#editMemeTitle").val()
                const newFile = $("#editFileInput")[0].files[0]

                if (!newTitle && !newFile) {
                    showNotification("Please provide a new title or a new image! ⚠️", "error")
                    return
                }

                const formData = new FormData()
                formData.append('meme_id', memeId)
                formData.append('title', newTitle)
                if (newFile) {
                    formData.append('meme', newFile)
                }

                const submitBtn = $(this).find('button[type="submit"]')
                submitBtn.html('<i class="fas fa-spinner fa-spin mr-2"></i>Saving...')
                submitBtn.prop('disabled', true)

                $.ajax({
                    url: 'edit_meme.php',
                    method: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    dataType: 'json',
                    success: function(response) {
                        if (response.success) {
                            showNotification("Meme updated successfully! ✨", "success")
                            modals.edit.addClass("hidden")
                            $("#memeGrid").empty()
                            currentPage = 0
                            fetchMemes(currentPage)
                        } else {
                            showNotification("Edit failed: " + response.message, "error")
                        }
                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        showNotification("Failed to connect to the edit server. Please check edit_meme.php.", "error")
                        console.error("AJAX error:", textStatus, errorThrown, jqXHR.responseText)
                    },
                    complete: function() {
                        $("#editForm")[0].reset()
                        $("#editFileInput").siblings("label").find("span").first().text("Click to upload new image")
                        submitBtn.html('<i class="fas fa-save mr-2"></i>Save Changes')
                        submitBtn.prop('disabled', false)
                    }
                })
            })

            $("#addCommentForm").submit(function(e) {
                e.preventDefault()
                const memeId = $("#commentMemeId").val()
                const commentText = $("#commentInput").val().trim()

                if (!commentText) {
                    showNotification("Comment cannot be empty! 📝", "error")
                    return
                }

                const submitBtn = $(this).find('button[type="submit"]')
                submitBtn.html('<i class="fas fa-spinner fa-spin mr-2"></i>Posting...')
                submitBtn.prop('disabled', true)

                $.ajax({
                    url: 'add_comment.php',
                    method: 'POST',
                    data: { meme_id: memeId, comment: commentText },
                    dataType: 'json',
                    success: function(response) {
                        if (response.success) {
                            showNotification("Comment added! 🎉", "success")
                            $("#commentInput").val('')
                            fetchComments(memeId)
                        } else {
                            showNotification("Failed to add comment: " + response.message, "error")
                        }
                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        showNotification("Failed to connect to the comment server. Please check add_comment.php.", "error")
                        console.error("AJAX error:", textStatus, errorThrown, jqXHR.responseText)
                    },
                    complete: function() {
                        submitBtn.html('<i class="fas fa-paper-plane mr-2"></i>Post')
                        submitBtn.prop('disabled', false)
                    }
                })
            })

            // Reaction buttons (like, upvote)
            $(document).on("click", ".like-btn", function (e) {
                if (!isLoggedIn) {
                    e.preventDefault()
                    showNotification("Please login to like memes! ❤️", "error")
                    modals.login.removeClass("hidden")
                    return
                }

                const $btn = $(this)
                const memeId = $btn.closest(".meme-card").data("meme-id")
                const $count = $btn.find(".like-count")
                let currentCount = Number.parseInt($count.text())
                
                const isLiked = $btn.hasClass("liked")
                const newCount = isLiked ? currentCount - 1 : currentCount + 1
                const type = 'like'

                $.ajax({
                    url: 'react.php',
                    method: 'POST',
                    data: { meme_id: memeId, type: type },
                    dataType: 'json',
                    success: function(response) {
                        if (response.success) {
                            if (isLiked) {
                                $btn.removeClass("liked").removeClass("text-red-600").addClass("text-zambian-red")
                            } else {
                                $btn.addClass("liked").removeClass("text-zambian-red").addClass("text-red-600")
                            }
                            $count.text(newCount)
                            showNotification(isLiked ? "Unliked! 💔" : "Liked! ❤️", "success")
                        } else {
                            showNotification("Reaction failed: " + response.message, "error")
                        }
                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        showNotification("Failed to connect to the reaction server. Please check react.php.", "error")
                        console.error("AJAX error:", textStatus, errorThrown, jqXHR.responseText)
                    }
                })
                // Add reaction animation
                $btn.addClass("scale-125")
                setTimeout(() => $btn.removeClass("scale-125"), 200)
            })

            $(document).on("click", ".upvote-btn", function (e) {
                if (!isLoggedIn) {
                    e.preventDefault()
                    showNotification("Please login to upvote memes! ⬆️", "error")
                    modals.login.removeClass("hidden")
                    return
                }

                const $btn = $(this)
                const memeId = $btn.closest(".meme-card").data("meme-id")
                const $count = $btn.find(".upvote-count")
                let currentCount = Number.parseInt($count.text())

                const isUpvoted = $btn.hasClass("upvoted")
                const newCount = isUpvoted ? currentCount - 1 : currentCount + 1
                const type = 'upvote'

                $.ajax({
                    url: 'react.php',
                    method: 'POST',
                    data: { meme_id: memeId, type: type },
                    dataType: 'json',
                    success: function(response) {
                        if (response.success) {
                            if (isUpvoted) {
                                $btn.removeClass("upvoted").removeClass("text-orange-600").addClass("text-zambian-orange")
                            } else {
                                $btn.addClass("upvoted").removeClass("text-zambian-orange").addClass("text-orange-600")
                            }
                            $count.text(newCount)
                            showNotification(isUpvoted ? "Un-upvoted! ⬇️" : "Upvoted! ⬆️", "success")
                        } else {
                            showNotification("Reaction failed: " + response.message, "error")
                        }
                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        showNotification("Failed to connect to the reaction server. Please check react.php.", "error")
                        console.error("AJAX error:", textStatus, errorThrown, jqXHR.responseText)
                    }
                })
                // Add reaction animation
                $btn.addClass("scale-125")
                setTimeout(() => $btn.removeClass("scale-125"), 200)
            })

           $(document).on('click', '.delete-btn', function () {
    const memeCard = $(this).closest('.meme-card');
    const memeId = memeCard.data('meme-id');

    if (!memeId) {
        showNotification('Meme ID not found', 'error');
        return;
    }

    if (confirm("Are you sure you want to delete this meme?")) {
        $.ajax({
            url: 'delete_meme.php',
            method: 'POST',
            data: { meme_id: memeId },
            success: function (response) {
               if (response.success) {
    showNotification("Meme deleted successfully", "success");
    memeCard.closest('.meme-wrapper').remove(); // remove from UI

    totalMemes--; // 👈 Update total meme count

    // Optional: Try loading the next meme to fill the space
    const currentlyDisplayed = $(".meme-wrapper").length;
    if (currentlyDisplayed < totalMemes) {
        fetchMemes(currentPage, currentFilter); // 👈 Fetch one more to replace
    } else if (currentlyDisplayed === 0) {
        $("#loadMoreBtn").hide(); // No more memes to load
        showNotification("No memes left to show.", "info");
    }
}
            },
            error: function () {
                showNotification("Error deleting meme", "error");
            }
        });
    }
});


            $(document).on("click", ".share-btn", (e) => {
                if (!isLoggedIn) {
                    e.preventDefault()
                    showNotification("Please login to share memes! 🔗", "error")
                    modals.login.removeClass("hidden")
                    return
                }

                // Simulate sharing
                if (navigator.share) {
                    navigator.share({
                        title: "Check out this funny meme!",
                        text: "Found this hilarious meme on ZedMemes!",
                        url: window.location.href,
                    })
                } else {
                    // Fallback to clipboard
                    navigator.clipboard.writeText(window.location.href).then(() => {
                        showNotification("Link copied to clipboard! 📋", "success")
                    })
                }
            })

            $(document).on("click", ".download-btn", function (e) {
                if (!isLoggedIn) {
                    e.preventDefault()
                    showNotification("Please login to download memes! 📥", "error")
                    modals.login.removeClass("hidden")
                    return
                }

                const $card = $(this).closest(".meme-card")
                const $img = $card.find("img")
                const imgSrc = $img.attr("src")

                // Create download link
                const link = document.createElement("a")
                link.href = imgSrc
                link.download = "zedmeme.jpg" // You might want a dynamic filename here
                link.click()

                showNotification("Meme downloaded! 📥", "success")
            })

            // File input styling with image preview
            $("#fileInput").change(function () {
                const file = this.files[0]
                if (file) {
                    $(this).siblings("label").find("span").first().text(file.name)
                }
            })

            $("#editFileInput").change(function() {
                const file = this.files[0]
                if (file) {
                    $(this).siblings("label").find("span").first().text(file.name)
                }
            })

            // Load more button - now calls fetchMemes
            $("#loadMoreBtn").click(() => {
                fetchMemes(currentPage + 1)
            })

            // Logout functionality
            $("#logoutBtn").click(() => {
    $.post('logout.php', () => {
        updateAuthState(false);
        showNotification("Logged out!", "info");
        $("#welcomeBanner").removeClass("slide-out").show();
    });
});

            // Filter button click handlers
            $("#filter-all").click(() => {
                $("#memeGrid").empty();
                currentPage = 0;
                fetchMemes(currentPage, 'all');
                $(".filter-btn").removeClass("bg-zambian-green text-white").addClass("bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300");
                $("#filter-all").removeClass("bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300").addClass("bg-zambian-green text-white");
            });

            $("#filter-new").click(() => {
                $("#memeGrid").empty();
                currentPage = 0;
                fetchMemes(currentPage, 'new');
                $(".filter-btn").removeClass("bg-zambian-green text-white").addClass("bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300");
                $("#filter-new").removeClass("bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300").addClass("bg-zambian-green text-white");
            });

            $("#filter-trending").click(() => {
                $("#memeGrid").empty();
                currentPage = 0;
                fetchMemes(currentPage, 'trending');
                $(".filter-btn").removeClass("bg-zambian-green text-white").addClass("bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300");
                $("#filter-trending").removeClass("bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300").addClass("bg-zambian-green text-white");
            });

            $("#filter-popular").click(() => {
                $("#memeGrid").empty();
                currentPage = 0;
                fetchMemes(currentPage, 'popular');
                $(".filter-btn").removeClass("bg-zambian-green text-white").addClass("bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300");
                $("#filter-popular").removeClass("bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300").addClass("bg-zambian-green text-white");
            });

            $("#filter-hot").click(() => {
                $("#memeGrid").empty();
                currentPage = 0;
                fetchMemes(currentPage, 'hot');
                $(".filter-btn").removeClass("bg-zambian-green text-white").addClass("bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300");
                $("#filter-hot").removeClass("bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300").addClass("bg-zambian-green text-white");
            });

            // Utility functions
            function updateAuthState(loggedIn, username = '',userId = null) {
                isLoggedIn = loggedIn
                currentUser = username // Set current user globally
                currentUserId = userId // Set current user ID globally
                if (loggedIn) {
                    $("#authButtons").addClass("hidden")
                    $("#userProfile").removeClass("hidden")
                    $("#username").text(username)
                    $("#memeFilterPanel").removeClass("hidden") // Show filter panel when logged in
                } else {
                    $("#authButtons").removeClass("hidden")
                    $("#userProfile").addClass("hidden")
                    $("#memeFilterPanel").addClass("hidden") // Hide filter panel when logged out
                }
            }

            function hideWelcomeBanner() {
                $("#welcomeBanner").addClass("slide-out")
                setTimeout(() => {
                    $("#welcomeBanner").hide()
                }, 500)
            }

            function showNotification(message, type = "info") {
                const colors = {
                    success: "bg-zambian-green",
                    error: "bg-zambian-red",
                    info: "bg-zambian-orange",
                }

                const notification = $(`
                    <div class="fixed top-4 right-4 ${colors[type]} text-white px-6 py-4 rounded-lg shadow-lg z-50 transform translate-x-full transition-transform">
                        <div class="flex items-center">
                            <i class="fas fa-${type === 'success' ? 'check-circle' : type === 'error' ? 'exclamation-circle' : 'info-circle'} mr-3"></i>
                            <span>${message}</span>
                        </div>
                    </div>
                `)

                $("body").append(notification)

                setTimeout(() => notification.removeClass("translate-x-full"), 100)
                setTimeout(() => {
                    notification.addClass("translate-x-full")
                    setTimeout(() => notification.remove(), 300)
                }, 4000)
            }

            function getRandomTagColor() {
                const colors = ['zambian-black', 'zambian-green', 'zambian-red', 'zambian-orange']
                return colors[Math.floor(Math.random() * colors.length)]
            }

            function timeSince(dateString) {
                const now = new Date();
                const past = new Date(dateString);
                const seconds = Math.floor((now - past) / 1000);

                let interval = seconds / 31536000;
                if (interval > 1) {
                    return Math.floor(interval) + " years ago";
                }
                interval = seconds / 2592000;
                if (interval > 1) {
                    return Math.floor(interval) + " months ago";
                }
                interval = seconds / 86400;
                if (interval > 1) {
                    return Math.floor(interval) + " days ago";
                }
                interval = seconds / 3600;
                if (interval > 1) {
                    return Math.floor(interval) + " hours ago";
                }
                interval = seconds / 60;
                if (interval > 1) {
                    return Math.floor(interval) + " minutes ago";
                }
                return "Just now";
            }

            // Function to render a single meme card based on data from fetch_memes.php
 function renderMemeCard(meme) {
    const uploadedTime = timeSince(meme.uploaded_at)
    const imageUrl = `uploads/${meme.filename}`
    const memeTitle = meme.title || "Untitled Meme"
    const isOwner = isLoggedIn && currentUser === meme.username

    const ownerMenu = isOwner ? `
     <div class="absolute top-2 right-2 z-10">
            <button class="menu-btn p-2 rounded-full bg-black/20 backdrop-blur-sm hover:bg-black/30 text-white">
                <i class="fas fa-ellipsis-h"></i>
            </button>
            <div class="menu-dropdown absolute right-0 mt-2 w-28 rounded-md shadow-lg bg-white dark:bg-gray-800 ring-1 ring-black ring-opacity-5 hidden z-20">
                <div class="py-1 text-sm text-gray-700 dark:text-gray-200">
                    <button class="edit-btn block w-full text-left px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-700">Edit</button>
                    <button class="delete-btn block w-full text-left px-4 py-2 text-red-600 hover:bg-red-50 dark:hover:bg-red-700/50">Delete</button>
                </div>
            </div>
        </div>
    ` : ''

    const memeCardHtml = $(`
         <div class="meme-wrapper meme-card w-full" data-meme-id="${meme.id}">
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg overflow-hidden transition-colors duration-300 relative">
                ${ownerMenu}
                <div class="relative w-full">
                    <img src="${imageUrl}" alt="${memeTitle}" class="meme-image">
                </div>
                <div class="p-4">
                    <h3 class="font-semibold text-gray-800 dark:text-gray-200 mb-3">${memeTitle}</h3>
                    <div class="flex items-center justify-between mb-3">
                        <div class="flex items-center space-x-4">
                            <button class="reaction-btn like-btn flex items-center space-x-1 text-zambian-red hover:bg-red-50 dark:hover:bg-red-900/20 px-3 py-2 rounded-lg">
                                <i class="fas fa-heart"></i>
                                <span class="like-count text-sm font-medium">${meme.likes ?? 0}</span>
                            </button>
                            <button class="reaction-btn upvote-btn flex items-center space-x-1 text-zambian-orange hover:bg-orange-50 dark:hover:bg-orange-900/20 px-3 py-2 rounded-lg">
                                <i class="fas fa-arrow-up"></i>
                                <span class="upvote-count text-sm font-medium">${meme.upvotes}</span>
                            </button>
                        </div>
                        <div class="flex items-center space-x-2">
                            <button class="reaction-btn comment-btn text-blue-500 hover:bg-blue-50 dark:hover:bg-blue-900/20 p-2 rounded-lg">
                                <i class="fas fa-comment"></i>
                            </button>
                            <button class="reaction-btn share-btn text-zambian-green hover:bg-green-50 dark:hover:bg-green-900/20 p-2 rounded-lg">
                                <i class="fas fa-share-alt"></i>
                            </button>
                            <button class="reaction-btn download-btn text-gray-600 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-700 p-2 rounded-lg">
                                <i class="fas fa-download"></i>
                            </button>
                        </div>
                    </div>
                    <div class="flex items-center justify-between text-xs text-gray-500 dark:text-gray-400">
                        <span>By @${meme.username}</span>
                        <span>${uploadedTime}</span>
                    </div>
                </div>
            </div>
        </div>
    `)

                     
       

  




    


   


                $("#memeGrid").append(memeCardHtml)
                memeCardHtml.hide().fadeIn(500)
            }

            // Function to fetch memes from fetch_memes.php
            function fetchMemes(pageToLoad = 0, sort = 'all') {
    const btn = $("#loadMoreBtn")
    btn.html('<i class="fas fa-spinner fa-spin mr-2"></i>Loading...')
    btn.prop('disabled', true)

    $.ajax({
        url: 'fetch_memes.php',
        method: 'GET',
        data: { 
            page: pageToLoad, 
            limit: memesPerPage,
            sort: sort // <- important
        },
        dataType: 'json',
        success: function(response) {
            if (response.success) {
                if (pageToLoad === 0) {
                    $("#memeGrid").empty()
                }
                if (response.memes.length > 0) {
                    response.memes.forEach(meme => {
                        renderMemeCard(meme)
                    })
                    currentPage = pageToLoad
                    totalMemes = response.totalMemes

                    if ((currentPage + 1) * memesPerPage >= totalMemes) {
                        btn.hide()
                        showNotification("All memes loaded! 🎉", "info")
                    } else {
                        btn.show()
                    }
                } else if (pageToLoad === 0) {
                    showNotification("No memes found. Upload some to get started!", "info")
                    btn.hide()
                } else {
                    showNotification("No more memes to load.", "info")
                    btn.hide()
                }
            } else {
                showNotification("Error fetching memes: " + response.message, "error")
                btn.hide()
            }
        },
        error: function(jqXHR, textStatus, errorThrown) {
            showNotification("Failed to connect to the meme server. Please check fetch_memes.php.", "error")
            console.error("AJAX error:", textStatus, errorThrown, jqXHR.responseText)
            btn.hide()
        },
        complete: function() {
            btn.html('<i class="fas fa-plus mr-2"></i>Load More Memes')
            btn.prop('disabled', false)
        }
    })
}


            // Function to fetch comments for a specific meme
            function fetchComments(memeId) {
                $("#commentsList").empty() // Clear previous comments
                $("#commentsList").html('<div class="text-center text-gray-500 dark:text-gray-400"><i class="fas fa-spinner fa-spin mr-2"></i>Loading comments...</div>')

                $.ajax({
                    url: 'fetch_comments.php',
                    method: 'GET',
                    data: { meme_id: memeId },
                    dataType: 'json',
                    success: function(response) {
                        $("#commentsList").empty() // Clear loading message
                        if (response.success && response.comments.length > 0) {
                            response.comments.forEach(comment => {
                                const commentHtml = `
                                    <div class="bg-gray-100 dark:bg-gray-700 p-3 rounded-lg shadow-sm">
                                        <div class="flex justify-between items-center text-xs text-gray-600 dark:text-gray-400 mb-1">
                                            <span class="font-semibold text-gray-800 dark:text-gray-200">@${comment.username}</span>
                                            <span>${timeSince(comment.created_at)}</span>
                                        </div>
                                        <p class="text-gray-700 dark:text-gray-300 text-sm">${comment.comment}</p>
                                    </div>
                                `
                                $("#commentsList").append(commentHtml)
                            })
                            // Scroll to bottom of comments list
                            $("#commentsList").scrollTop($("#commentsList")[0].scrollHeight)
                        } else if (response.success && response.comments.length === 0) {
                            $("#commentsList").html('<p class="text-center text-gray-500 dark:text-gray-400 py-4">No comments yet. Be the first!</p>')
                        } else {
                            showNotification("Error fetching comments: " + response.message, "error")
                            $("#commentsList").html('<p class="text-center text-gray-500 dark:text-gray-400 py-4">Failed to load comments.</p>')
                        }
                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        showNotification("Failed to connect to the comment server. (fetch_comments.php error?)", "error")
                        console.error("AJAX error:", textStatus, errorThrown, jqXHR.responseText)
                        $("#commentsList").html('<p class="text-center text-gray-500 dark:text-gray-400 py-4">Failed to load comments due to network error.</p>')
                    }
                })
            }
            
            // Initial load of memes
            fetchMemes(0)
        })
    </script>
</body>
</html>
