<script>
    'use strict';
      document.addEventListener('DOMContentLoaded', function() {
          const categorySelect = document.getElementById('category-select');
          const eventList = document.getElementById('event-list');
          const categoryTitle = document.getElementById('category-title');
          const searchForm = document.getElementById('search-form');

          searchForm.addEventListener('submit', function(event) {
              event.preventDefault();

              const formData = new FormData(searchForm);
              const params = new URLSearchParams(formData).toString();
              const selectedOptionText = categorySelect.options[categorySelect.selectedIndex].text;

              fetch(`/events/search?${params}`, {
                  headers: {
                      'X-Requested-With': 'XMLHttpRequest'
                  }
              })
              .then(response => response.json())
              .then(data => {
                  eventList.innerHTML = '';
                  categoryTitle.innerHTML = '';

                  if (data.length > 0) {
                      data.forEach(event => {
                          const eventItem = `
                              <div id="event-item" class="cursor-pointer p-4 md:w-1/3" onclick="location.href='${event.route}'">
                                  <div class="h-full border-2 border-gray-200 border-opacity-60 rounded-lg overflow-hidden">
                                      <img class="lg:h-48 md:h-36 w-full object-cover object-center" src="https://dummyimage.com/720x400" alt="blog">
                                      <div class="p-6">
                                          <div class="flex flex-wrap justify-start mx-2">
                                              <h2 class="tracking-widest text-xs title-font font-medium text-gray-400 mb-1">${(event.categories || []).map(cat => `<h2 class="tracking-widest text-xs title-font font-medium text-gray-400 mb-1">${cat.name}</h2>`)}
                                          </div>
                                          <h1 class="title-font text-lg font-medium text-gray-900 mb-3">${event.title}</h1>
                                          <p class="leading-relaxed mb-3">${event.event_date}</p>
                                          <div class="flex items-center flex-wrap ">
                                              <span class="text-gray-400 mr-3 inline-flex items-center lg:ml-auto md:ml-0 ml-auto leading-none text-sm pr-3 py-1 border-r-2 border-gray-200">
                                                  <svg class="w-4 h-4 mr-1" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
                                                      <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                                                      <circle cx="12" cy="12" r="3"></circle>
                                                  </svg>${event.like_count !== null ? event.like_count : ''}
                                              </span>
                                              <span class="text-gray-400 inline-flex items-center leading-none text-sm">
                                                  <svg class="w-4 h-4 mr-1" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
                                                      <path d="M21 11.5a8.38 8.38 0 01-.9 3.8 8.5 8.5 0 01-7.6 4.7 8.38 8.38 0 01-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 01-.9-3.8 8.5 8.5 0 014.7-7.6 8.38 8.38 0 013.8-.9h.5a8.48 8.48 0 018 8v.5z"></path>
                                                  </svg>${event.comment_count !== null ? event.comment_count : ''}
                                              </span>
                                          </div>
                                      </div>
                                  </div>
                              </div>
                          `;
                          eventList.innerHTML += eventItem;
                      });
                      categoryTitle.innerHTML = selectedOptionText + 'の検索結果';
                  } else {
                      eventList.innerHTML = '該当するイベントがありません。';
                  }
              })
              .catch(error => console.error('Error:', error));
          });
      });
</script>