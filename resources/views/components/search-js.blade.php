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
                                    ${event.image ? 
                                        `<img src="/storage/events/${event.image}" alt="" class="w-full h-60 object-cover">` : 
                                        `<img src="/storage/No_Image.png" alt="" class="w-full h-60 object-cover">`
                                    }
                                    <div class="p-6">
                                        <div class="flex flex-wrap justify-start mx-2">
                                            ${event.categories.map(category => `
                                                <h2 class="tracking-widest text-xs title-font font-medium text-gray-400 mb-1">
                                                    ${category.name}
                                                </h2>
                                            `).join('')}
                                        </div>
                                        <h1 class="title-font text-lg font-medium text-gray-900 mb-3">${event.title}</h1>
                                        <p class="leading-relaxed mb-3">${event.event_date}</p>
                                        <p class="leading-relaxed mb-3">場所：${event.location.venue}</p>
                                        <p class="leading-relaxed mb-3">${event.formatted_price == 0 ? '無料' : event.formatted_price + '円'}</p>
                                        <div class="flex items-center flex-wrap ">
                                            <span class="text-gray-400 mr-3 inline-flex items-center lg:ml-auto md:ml-0 ml-auto leading-none text-sm pr-3 py-1 border-r-2 border-gray-200">
                                                ❤ ${event.like_count !== null ? event.like_count : '0'}
                                            </span>
                                            <span class="text-gray-400 inline-flex items-center leading-none text-sm">
                                                <svg class="w-4 h-4 mr-1" stroke="currentColor" stroke-width="2" fill="none"
                                                    stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
                                                    <path
                                                        d="M21 11.5a8.38 8.38 0 01-.9 3.8 8.5 8.5 0 01-7.6 4.7 8.38 8.38 0 01-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 01-.9-3.8 8.5 8.5 0 014.7-7.6 8.38 8.38 0 013.8-.9h.5a8.48 8.48 0 018 8v.5z">
                                                    </path>
                                                </svg>${event.comment_count !== null ? event.comment_count : '0'}
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