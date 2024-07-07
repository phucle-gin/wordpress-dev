(function main($) {
  $(() => {
    $('#pagination-container').on('click', 'a', function(event) {
      event.preventDefault();
      var page = $(this).attr('href').split('paged=')[1]; // Extract page number
      var keyword = $('#search-keyword').val(); // Get the keyword from the input field
      fetchPageData(page, keyword);
    });

    $('#search-button').on('click', function() {
      var keyword = $('#search-keyword').val();
      fetchPageData(1, keyword); // Always start from the first page when searching
    });
    $('.movie-box-3 .listing-content p').each(function() {
      var text = $(this).text();
      if (text.length > 100) {
        var truncatedText = text.substring(0, 100);
        var lastSpace = truncatedText.lastIndexOf(' ');
        truncatedText = truncatedText.substring(0, lastSpace) + '...';
        $(this).text(truncatedText);
      }
    });

    function fetchPageData(page, keyword) {
      $.ajax({
        url: ajax_object.ajax_url,
        type: 'GET',
        data: {
            action: 'fetch_movies',
            page: page,
            keyword: keyword,
            nonce: ajax_object.nonce,
        },
        success: function(response) {
          var moviesHtml = '';
          var movies = response.data.results;

          if (movies.length > 0) {
            $.each(movies, function(index, movie) {
              var imdbLink = movie.imdb_id ? '<a href="https://www.imdb.com/title/' + movie.imdb_id + '" target="_blank">Read More</a>' : '';
              moviesHtml += '<div class="movie"><h2>' + movie.title + '</h2><p>' + movie.overview + '</p>' + imdbLink + '</div>';
            });

            $('#movies-container').html(moviesHtml);
            updatePagination(response.total_pages, page);
          } else {
            $('#movies-container').html('<p>No movies found.</p>');
            $('#pagination-container').html('');
          }
        },
        error: function(xhr, status, error) {
          console.error('Error fetching movies:', error);
        }
    });
    }

    function updatePagination(totalPages, currentPage) {
      var maxPages = 6;
      var halfMax = Math.floor(maxPages / 2);
      var startPage = Math.max(1, currentPage - halfMax);
      var endPage = Math.min(totalPages, startPage + maxPages - 1);

      var paginationHtml = '';

      // Always show first page link
      if (startPage > 1) {
        paginationHtml += '<a href="?paged=1">1</a>';
        paginationHtml += '<span>...</span>';
      }

      // Show the range of pages around the current page
      for (var i = startPage; i <= endPage; i++) {
        paginationHtml += '<a href="?paged=' + i + '"' + (i === currentPage ? ' class="current"' : '') + '>' + i + '</a>';
      }

      // Always show last page link
      if (endPage < totalPages) {
        paginationHtml += '<span>...</span>';
        paginationHtml += '<a href="?paged=' + totalPages + '">' + totalPages + '</a>';
      }

      $('#pagination-container').html(paginationHtml);
    }
  });
}(jQuery));
