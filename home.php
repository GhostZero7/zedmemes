<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>ZedMemes - Home</title>
  <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
  <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
  <style>
    .meme-card {
      max-width: 500px;
      margin: auto;
    }

    #backToTop {
      display: none;
    }
  </style>
</head>
<body class="bg-gray-100 min-h-screen">

  <!-- Header -->
  <div class="text-center p-4 bg-white shadow-md sticky top-0 z-50">
    <h1 class="text-3xl font-bold text-gray-800">ZedMemes</h1>
    <p class="text-sm text-gray-500">Share. Laugh. Repeat.</p>
  </div>

  <!-- Feed Container -->
  <div id="memeFeed" class="flex flex-col gap-6 items-center mt-6 px-4">
    <!-- Memes will be appended here -->
  </div>

  <!-- Back to top button -->
  <button id="backToTop" onclick="scrollToTop()" class="fixed bottom-6 right-6 bg-blue-600 text-white p-3 rounded-full shadow-lg">
    ↑
  </button>

  <!-- Dummy Meme Template for JS -->
  <template id="memeTemplate">
    <div class="bg-white rounded shadow p-4 meme-card">
      <img src="https://via.placeholder.com/500x400?text=Meme" class="rounded mb-3" alt="meme" />
      <div class="flex justify-between text-gray-600 text-sm">
        <span>Likes: <span class="like-count">0</span></span>
        <span>Upvotes: <span class="upvote-count">0</span></span>
      </div>
    </div>
  </template>

  <script>
    let page = 1;
    let loading = false;

    function loadDummyMemes() {
      if (loading) return;
      loading = true;

      for (let i = 0; i < 5; i++) {
        const template = document.getElementById('memeTemplate');
        const clone = template.content.cloneNode(true);
        document.getElementById('memeFeed').appendChild(clone);
      }

      loading = false;
    }

    // Infinite scroll
    $(window).on('scroll', function () {
      const nearBottom = $(window).scrollTop() + $(window).height() > $(document).height() - 100;
      if (nearBottom) loadDummyMemes();

      // Back to Top button visibility
      if ($(window).scrollTop() > 400) {
        $('#backToTop').fadeIn();
      } else {
        $('#backToTop').fadeOut();
      }
    });

    function scrollToTop() {
      $('html, body').animate({ scrollTop: 0 }, 'slow');
    }

    // Initial load
    $(document).ready(function () {
      loadDummyMemes();
    });
  </script>

</body>
</html>
