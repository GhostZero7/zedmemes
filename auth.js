$(document).ready(function () {
  // Register form submission
  $('#registerForm').on('submit', function (e) {
    e.preventDefault();
    $.ajax({
      url: 'register.php',
      type: 'POST',
      data: $(this).serialize(),
      dataType: 'json',
      success: function (res) {
        const msg = $('#registerMessage');
        if (res.success) {
          msg.removeClass('hidden text-red-600').addClass('text-green-600').text('Account registered successfully! Welcome ' + res.username);
          $('#registerForm')[0].reset();
          setTimeout(() => {
            $('#registerModal').addClass('hidden');
          }, 2000);
        } else {
          msg.removeClass('hidden text-green-600').addClass('text-red-600').text(res.message);
        }
      },
      error: function (xhr, status, error) {
        alert('Registration failed: ' + error);
      }
    });
  });

  // Login form submission
  $('#loginForm').on('submit', function (e) {
    e.preventDefault();
    $.ajax({
      url: 'login.php',
      type: 'POST',
      data: $(this).serialize(),
      dataType: 'json',
      success: function (res) {
        if (res.success) {
          alert('Login successful. Welcome back.');
          location.reload();
        } else {
          alert('Login error: ' + res.message);
        }
      },
      error: function (xhr, status, error) {
        alert('Login request failed: ' + error);
      }
    });
  });

  // Meme upload
  $('#uploadForm').on('submit', function (e) {
    e.preventDefault();
    const formData = new FormData(this);
    $.ajax({
      url: 'upload.php',
      type: 'POST',
      data: formData,
      contentType: false,
      processData: false,
      dataType: 'json',
      success: function (data) {
        const msg = $('#uploadMessage');
        if (data.success) {
          msg.removeClass('hidden text-red-600').addClass('text-green-600').text('Meme uploaded successfully!');
          $('#uploadForm')[0].reset();
          setTimeout(() => {
            $('#uploadModal').addClass('hidden');
            loadMemes();
          }, 2000);
        } else {
          msg.removeClass('hidden text-green-600').addClass('text-red-600').text(data.message);
        }
      },
      error: function (xhr, status, error) {
        alert('Upload failed: ' + error);
      }
    });
  });

  // Load memes initially
  loadMemes();
});
