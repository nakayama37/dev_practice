<script>
    /**
     * DOMが完全に読み込まれた後に実行される処理
     * @param  void
     * @return void
     */
      document.addEventListener('DOMContentLoaded', function() {
          //いいね機能の変数 
          const likeButton = document.getElementById('like-button');
          const likeToggle = document.getElementById('like-toggle');
          const likeIcon = document.getElementById('like-icon');
          const likeText = document.getElementById('like-text');
          const likeCount = document.getElementById('like-count');
          // コメント機能の変数
          const commentForm = document.getElementById('comment-form');
          const commentContent = document.getElementById('comment-content');
          const commentsList = document.getElementById('comments-list');
          const eventId = likeButton.getAttribute('data-event-id');

          //いいね登録時リクエストリクエストを複数送れないようにする
          let isRequestInProgress = false;
     /**
     * Toggle like
     * @param  event
     * @return text
     */
          likeToggle.addEventListener('click', function(event) {
            event.preventDefault();
            
            // リクエスト中は他のリクエストができないようにする
              if (isRequestInProgress) return;
              isRequestInProgress = true;

              likeToggle.classList.add('text-gray-200');  // クリックを無効化するためのクラスを追加

     /**
     * show likes
     * 
     */
              fetch(`/events/${eventId}/like`, {
                  method: 'POST',
                  headers: {
                      'Content-Type': 'application/json',
                      'X-CSRF-TOKEN': '{{ csrf_token() }}'
                  }
              })
              .then(response => response.json())
              .then(data => {      
                  likeButton.setAttribute('data-liked', data.liked);
                  likeCount.innerText = data.likeCount;

                  if (data.liked) {
                      likeIcon.innerText = '❤️';
                      likeText.innerText = 'いいねを取り消す';
                  } else {
                      likeIcon.innerText = '♡';
                      likeText.innerText = 'いいねを押してイベントを応援する';
                  }
              })
              .catch(error => {
                  console.error('Error:', error);
              })
                .finally(() => {
                  isRequestInProgress = false;
                  likeToggle.classList.remove('text-gray-200'); 
              });
          });
     /**
     * show comments
     * 
     */
            fetch(`/events/${eventId}/comments`)
                .then(response => response.json())
                .then(data => {
                    const { comments, userId } = data;
                    commentsList.innerHTML = comments.map(comment => `
                        <article id="comment-${comment.id}" class="border-t p-6 text-base bg-white rounded-lg dark:bg-gray-900">
                            <footer class="flex justify-between items-center mb-2">
                                <div class="flex items-center">
                                    <p class="inline-flex items-center mr-3 text-sm text-gray-900 dark:text-white font-semibold">${comment.user.name}</p>
                                    <p class="text-sm text-gray-600 dark:text-gray-400"><time pubdate datetime="2022-02-08"
                                            title="February 8th, 2022">${comment.formatted_created_at}</time></p>
                                </div>
                                ${comment.user.id === userId ? `
                                <button onclick="deleteComment(${comment.id})"
                                    class="inline-flex items-center p-2 text-sm font-medium text-center text-gray-500 dark:text-gray-400 bg-white rounded-lg hover:bg-gray-100 focus:ring-4 focus:outline-none focus:ring-gray-50 dark:bg-gray-900 dark:hover:bg-gray-700 dark:focus:ring-gray-600"
                                    type="button">
                                    削除
                                </button>
                                 ` : ''}
                            </footer>
                            <p class="text-gray-500 dark:text-gray-400">${comment.content}</p>
                            
                        </article>
                    `).join('');
                });

     /**
     * post comments
     * 
     */
            commentForm.addEventListener('submit', function(event) {
                event.preventDefault();
                fetch(`/events/${eventId}/comments`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({
                        content: commentContent.value
                    })
                })
                .then(response => response.json())
                  .then(data => {
                    const { comments, userId } = data;
                    commentsList.innerHTML = comments.map(comment => `
                          <article id="comment-${comment.id}" class="border-t p-6 text-base bg-white rounded-lg dark:bg-gray-900">
                            <footer class="flex justify-between items-center mb-2">
                                <div class="flex items-center">
                                    <p class="inline-flex items-center mr-3 text-sm text-gray-900 dark:text-white font-semibold">${comment.user.name}</p>
                                    <p class="text-sm text-gray-600 dark:text-gray-400"><time pubdate datetime="2022-02-08"
                                            title="February 8th, 2022">${comment.formatted_created_at}</time></p>
                                </div>
                                ${comment.user.id === userId ? `
                                <button onclick="deleteComment(${comment.id})"
                                    class="inline-flex items-center p-2 text-sm font-medium text-center text-gray-500 dark:text-gray-400 bg-white rounded-lg hover:bg-gray-100 focus:ring-4 focus:outline-none focus:ring-gray-50 dark:bg-gray-900 dark:hover:bg-gray-700 dark:focus:ring-gray-600"
                                    type="button">
                                    削除
                                </button>
                                 ` : ''}
                            </footer>
                            <p class="text-gray-500 dark:text-gray-400">${comment.content}</p>
                            
                        </article>
                    `).join('');

                    commentContent.value = '';
                    
                });
            });

     /**
     * delete comments
     * 
     */
            window.deleteComment = function(commentId) {
                if (!confirm('このコメントを削除してもよろしいですか？')) {
                    return;
                }

                fetch(`/comments/${commentId}`, {
                    method: 'DELETE',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        const commentElement = document.getElementById('comment-' + commentId);
                        commentElement.remove();
                    } else {
                        console.error('Error:', data.error);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                });
            };

      }); 
</script>